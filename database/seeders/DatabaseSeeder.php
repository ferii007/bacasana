<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\Categories;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(5)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Add Categories
        $categories = Categories::factory(8)->create();

        // Add blog which each blog has random category
        // Blog::factory(50)->create()->each(function ($blog) use ($categories) {
        //     $blog->getBlogCategory()->attach(
        //         $categories->random(rand(1, 3))->pluck('id')->toArray()
        //     );
        // });

        User::factory(5)->create()->each(function ($user) {
            // Create blog for each user
            $blogs = Blog::factory(300)->create([
                'author_id' => $user->id,
            ]);
    
            // Create random category for each blog
            $categories = Categories::all();
            $blogs->each(function ($blog) use ($categories) {
                $blog->getBlogCategory()->attach(
                    $categories->random(rand(1, 3))->pluck('id')->toArray()
                );
            });
        });
    }
}
