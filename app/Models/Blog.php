<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blog';

    public function getBlogCategory()
    {
        return $this->belongsToMany(Categories::class, 'blog_category', 'blog_id', 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
