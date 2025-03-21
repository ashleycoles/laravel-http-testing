<?php

namespace Tests\Feature;

use App\Models\Colour;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use DatabaseMigrations;

    public function test_get_all_products_no_filters(): void
    {
        Product::factory()
            ->count(2)
            // Has accepts a factory and will use it to create a many-to-many relationship
            ->has(Colour::factory()->count(2))
            ->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->has('data', 2, function (AssertableJson $data) {
                        $data->hasAll(['id', 'name', 'price', 'stock', 'category', 'colours'])
                            ->has('category', function (AssertableJson $category) {
                                $category->hasAll(['id', 'name']);
                            })
                            ->has('colours', 2, function (AssertableJson $colours) {
                                $colours->hasAll(['id', 'name']);
                            });
                    });
            });
    }

    public function test_get_all_products_stock_filter(): void
    {
        $inStockProduct = Product::factory()->create();
        $inStockProduct->stock = 10;
        $inStockProduct->save();

        $outOfStockproduct = Product::factory()->create();
        $outOfStockproduct->stock = 0;
        $outOfStockproduct->save();

        $response = $this->getJson('/api/products?instock=1');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', 1)
                    ->etc();
            });
    }

    public function test_get_all_products_with_search(): void
    {
        $searchProduct = Product::factory()->create();
        $searchProduct->name = 'Hello World';
        $searchProduct->save();

        $otherProduct = Product::factory()->create();
        $otherProduct->name = 'test';
        $otherProduct->save();

        $response = $this->getJson('/api/products?search=World');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', 1)
                    ->etc();
            });
    }

    public function test_get_all_products_with_search_and_stock_filter(): void
    {
        $searchProduct = Product::factory()->create();
        $searchProduct->name = 'Hello World';
        $searchProduct->stock = 0;
        $searchProduct->save();

        $sameNameInstockProduct = Product::factory()->create();
        $sameNameInstockProduct->name = 'Hello World';
        $sameNameInstockProduct->stock = 10;
        $sameNameInstockProduct->save();

        $otherNameOutOfStock = Product::factory()->create();
        $otherNameOutOfStock->name = 'test';
        $otherNameOutOfStock->stock = 0;
        $otherNameOutOfStock->save();

        $response = $this->getJson('/api/products?search=World&instock=0');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', 1)
                    ->etc();
            });
    }

    public function test_get_all_products_invalid_stock_filter(): void
    {
        $response = $this->getJson('/api/products?instock=hi');

        $response->assertInvalid(['instock']);
    }
}
