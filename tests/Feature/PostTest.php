<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class PostTest extends TestCase
{
    use DatabaseMigrations; // Tells laravel to run the migrations for this test

    public function test_get_all_posts_success(): void
    {
        // Using our factory to create three posts in the posts
        // table of our test database
        Post::factory()->count(3)->create();

        // I need to tell laravel that I'm asking for JSON
        $response = $this->getJson('/api/posts');

        // assertJson allows us to make assertions about the structure
        // and contents of the JSON we got back
        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                // asserting that the JSON we got back has a message key and a data key
                $json->hasAll(['message', 'data'])
                    // Asserting that the data key contains 3 items
                    ->has('data', 3, function (AssertableJson $data) {
                        // This function is now 'scoped' into the data from the response
                        // In here I can write assertions about each individual post
                        $data->hasAll(['id', 'title', 'image', 'excerpt', 'author']);
                    });
            });
    }

    public function test_find_post_success(): void
    {
        $post = Post::factory()->create();

        $response = $this->getJson('/api/posts/'.$post->id);

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    // Using has without a number means it is NOT an array
                    ->has('data', function (AssertableJson $data) {
                        $data->hasAll([
                            'id',
                            'title',
                            'image',
                            'excerpt',
                            'author',
                            'content',
                            'created_at',
                            'updated_at',
                        ]);
                    });
            });
    }

    public function test_find_post_not_found(): void
    {
        $response = $this->getJson('/api/posts/100000');

        $response->assertStatus(404);
    }

    public function test_create_post_missing_data(): void
    {
        $response = $this->postJson('/api/posts', []);

        $response->assertInvalid(['title', 'content', 'excerpt', 'author']);
    }

    public function test_create_post_invalid_data(): void
    {
        $response = $this->postJson('/api/posts', [
            'title' => 'a',
            'content' => 'a',
            'image' => 'notaurl',
            'excerpt' => 'a',
            'author' => 'a',
        ]);

        $response->assertInvalid(['title', 'content', 'image', 'excerpt', 'author']);
    }

    public function test_create_post_success(): void
    {
        $testData = [
            'title' => 'abc',
            'content' => str_repeat('a', 60),
            'image' => 'http://google.com/what.jpg',
            'excerpt' => str_repeat('a', 60),
            'author' => 'abc',
        ];

        $response = $this->postJson('/api/posts', $testData);

        $response->assertStatus(201)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message']);
            });

        $this->assertDatabaseHas('posts', $testData);
    }
}
