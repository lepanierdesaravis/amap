<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['slug', 'title', 'intro', 'content'];

    public static function content(string $slug): ?self
    {
        return static::firstWhere('slug', $slug);
    }
}
