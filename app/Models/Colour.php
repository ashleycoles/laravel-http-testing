<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Colour extends Model
{
    /** @use HasFactory<\Database\Factories\ColourFactory> */
    use HasFactory;

    protected $hidden = ['pivot', 'created_at', 'updated_at'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
