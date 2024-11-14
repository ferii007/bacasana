<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function blogPost($request) {
        try {
            $blogs = Blog::get();
            $blogPost = $blogs->where('slug', '=', $request)->first();
            $blogPostCategory = $blogPost->getBlogCategory;

            $blogsWithoutCurrentPost = $blogs->where('slug', '!=', $blogPost->slug);
            $latestBlogs = $blogsWithoutCurrentPost->take(4);
            $randomBlogs = $blogsWithoutCurrentPost->shuffle()->take(3);

            // dd($request, $blogPost, $blogsWithoutCurrentPost, $latestBlogs);
            
            return view('blog/post', [
                'blogPost' => $blogPost,
                'blogPostCategory' => $blogPostCategory,
                'latestBlogs' => $latestBlogs,
                'randomBlogs' => $randomBlogs
            ]);
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    public function index() {
        try {
            return view('blog/index');
        } catch (\Throwable $th) {
            abort(404);
        }
    }
}
