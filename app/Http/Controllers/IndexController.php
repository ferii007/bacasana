<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Blog;

class IndexController extends Controller
{
    public function index(Request $request) {
        try {
            $blogs = Blog::latest()->take(10)->get();

            if ($blogs->isNotEmpty()) {
                // Extract the first element
                $blog = $blogs->shift();
            } else {
                $blog = null;
                $blogs = null;
            }

            return view('index', [
                'blog' => $blog,
                'remainingBlogs' => $blogs,
            ]);
        } catch (\Throwable $th) {
            abort(404);
        }
    }
}
