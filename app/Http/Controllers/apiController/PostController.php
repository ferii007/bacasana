<?php

namespace App\Http\Controllers\apiController;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    // public function getAllPosts() {
    //     try {
    //         $today = now()->format('Y-m-d');
    //         $yesterday = now()->subDay()->format('Y-m-d');
    //         $lastWeek = now()->subWeek()->format('Y-m-d');
    //         $lastMonth = now()->subMonth()->format('Y-m-d');

    //         // Query for calculate add/delete based on date
    //         $posts = Blog::selectRaw("
    //                 (SELECT count(*) FROM blog WHERE deleted_at IS NULL) as totalPosts,
    //                 sum(case when DATE(created_at) = '{$today}' then 1 else 0 end) as addedToday,
    //                 sum(case when DATE(deleted_at) = '{$today}' then 1 else 0 end) as deletedToday,
    //                 sum(case when DATE(created_at) = '{$yesterday}' then 1 else 0 end) as addedYesterday,
    //                 sum(case when DATE(deleted_at) = '{$yesterday}' then 1 else 0 end) as deletedYesterday,
    //                 sum(case when DATE(created_at) >= '{$lastWeek}' and DATE(created_at) < '{$yesterday}' then 1 else 0 end) as addedLastWeek,
    //                 sum(case when DATE(deleted_at) >= '{$lastWeek}' and DATE(deleted_at) < '{$yesterday}' then 1 else 0 end) as deletedLastWeek,
    //                 sum(case when DATE(created_at) >= '{$lastMonth}' and DATE(created_at) < '{$lastWeek}' then 1 else 0 end) as addedLastMonth,
    //                 sum(case when DATE(deleted_at) >= '{$lastMonth}' and DATE(deleted_at) < '{$lastWeek}' then 1 else 0 end) as deletedLastMonth
    //             ")
    //             ->first();
    
    //         // If $posts is nothing, throw it into catch block
    //         if (!$posts) {
    //             throw new \Exception('No posts found.');
    //         }

    //         $value = 0;
    //         $valueType = '';
    //         $label = '';
    
    //         if ($posts->addedToday > 0 || $posts->deletedToday > 0) {
    //             $value = $posts->addedToday - $posts->deletedToday;
    //             $label = 'today';
    //         } else if ($posts->addedYesterday > 0 || $posts->deletedYesterday > 0) {
    //             $value = $posts->addedYesterday - $posts->deletedYesterday;
    //             $label = 'than yesterday';
    //         } else if ($posts->addedLastWeek > 0 || $posts->deletedLastWeek > 0) {
    //             $value = $posts->addedLastWeek - $posts->deletedLastWeek;
    //             $label = 'than last week';
    //         } else if ($posts->addedLastMonth > 0 || $posts->deletedLastMonth > 0) {
    //             $value = $posts->addedLastMonth - $posts->deletedLastMonth;
    //             $label = 'than last month';
    //         }

    //         $value = ($value >= 0 ? '+' : '-') . abs($value);
    //         $valueType = $value >= 0 ? 'positive' : 'negative';
    
    //         $response = [
    //             'totalPosts' => $posts->totalPosts,
    //             'footer' => [
    //                 'valueType' => $valueType,
    //                 'value' => $value,
    //                 'label' => $label,
    //             ]
    //         ];
    
    //         return response()->json($response);
    //         // return view('test', [
    //         //     'response' => $response
    //         // ]);
    //     } catch (\Throwable $th) {
    //         return response()->json(['error' => 'Failed Get All Posts', 'details' => $th->getMessage()], 500);
    //     }
    // }    

    private function calculateDataValueInformation($data)
    {
        $addedToday = 0;
        $deletedToday = 0;
        $addedYesterday = 0;
        $deletedYesterday = 0;
        $addedLastWeek = 0;
        $deletedLastWeek = 0;
        $addedLastMonth = 0;
        $deletedLastMonth = 0;

        foreach ($data as $item) {
            $addedToday += $item->addedToday;
            $deletedToday += $item->deletedToday;
            $addedYesterday += $item->addedYesterday;
            $deletedYesterday += $item->deletedYesterday;
            $addedLastWeek += $item->addedLastWeek;
            $deletedLastWeek += $item->deletedLastWeek;
            $addedLastMonth += $item->addedLastMonth;
            $deletedLastMonth += $item->deletedLastMonth;
        }

        $value = 0;
        $label = '';

        if ($addedToday > 0 || $deletedToday > 0) {
            $value = $addedToday - $deletedToday;
            $label = 'today';
        } else if ($addedYesterday > 0 || $deletedYesterday > 0) {
            $value = $addedYesterday - $deletedYesterday;
            $label = 'than yesterday';
        } else if ($addedLastWeek > 0 || $deletedLastWeek > 0) {
            $value = $addedLastWeek - $deletedLastWeek;
            $label = 'than last week';
        } else if ($addedLastMonth > 0 || $deletedLastMonth > 0) {
            $value = $addedLastMonth - $deletedLastMonth;
            $label = 'than last month';
        }

        $value = ($value >= 0 ? '+' : '-') . abs($value);
        $valueType = $value >= 0 ? 'positive' : 'negative';

        return [
            'value' => $value,
            'valueType' => $valueType,
            'label' => $label,
        ];
    }

    public function getAllPosts() {
        try {
            $today = now()->format('Y-m-d');
            $yesterday = now()->subDay()->format('Y-m-d');
            $lastWeek = now()->subWeek()->format('Y-m-d');
            $lastMonth = now()->subMonth()->format('Y-m-d');

            $bindings = [
                $today, $today,
                $yesterday, $yesterday,
                $lastWeek, $yesterday,
                $lastWeek, $yesterday,
                $lastMonth, $lastWeek,
                $lastMonth, $lastWeek,
            ];

            // Query for calculate add/delete based on date
            $posts = Blog::select([
                '*',
                DB::raw('(SELECT count(*) FROM blog WHERE deleted_at IS NULL) as totalPosts'),
                DB::raw('sum(case when DATE(created_at) = ? then 1 else 0 end) as addedToday'),
                DB::raw('sum(case when DATE(deleted_at) = ? then 1 else 0 end) as deletedToday'),
                DB::raw('sum(case when DATE(created_at) = ? then 1 else 0 end) as addedYesterday'),
                DB::raw('sum(case when DATE(deleted_at) = ? then 1 else 0 end) as deletedYesterday'),
                DB::raw('sum(case when DATE(created_at) >= ? and DATE(created_at) < ? then 1 else 0 end) as addedLastWeek'),
                DB::raw('sum(case when DATE(deleted_at) >= ? and DATE(deleted_at) < ? then 1 else 0 end) as deletedLastWeek'),
                DB::raw('sum(case when DATE(created_at) >= ? and DATE(created_at) < ? then 1 else 0 end) as addedLastMonth'),
                DB::raw('sum(case when DATE(deleted_at) >= ? and DATE(deleted_at) < ? then 1 else 0 end) as deletedLastMonth'),
            ])
                ->groupBy('id')
                ->setBindings($bindings)
                ->get();
    
            // If $posts is nothing, throw it into catch block
            if ($posts->isEmpty()) {
                throw new \Exception('No categories found.');
            }

            $allPosts = $posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'read_duration' => $post->read_duration,
                    'read_duration_type' => $post->read_duration_type,
                    'image' => $post->image,
                    'created_at' => $post->created_at,
                    'updated_at' => $post->updated_at,
                    'deleted_at' => $post->deleted_at,
                ];
            });

            $stats = $this->calculateDataValueInformation($posts);

            $response = [
                'allPosts' => $allPosts,
                'totalPostsActive' => $allPosts->whereNull('deleted_at')->count(),
                'footer' => $stats,
            ];
    
            return response()->json($response);
            // return view('test', [
            //     'response' => $response
            // ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Failed Get All Posts', 'details' => $th->getMessage()], 500);
        }
    }

    public function fetchAllPosts(Request $request) {
        try {
            $posts = Blog::orderBy('created_at', 'desc');
            $perPage = $request->input('per_page', 25);
            $status = $request->input('status', 'active');
            $search = $request->input('search', '');
            
            if ($status === 'active') {
                $posts->whereNull('deleted_at');
            } elseif ($status === 'deleted') {
                $posts->whereNotNull('deleted_at');
            }

            if (!empty($search)) {
                $posts->where('title', 'like', '%' . $search . '%');
            }

            $posts = $posts->paginate($perPage);

            $response = [
                'allPosts' => $posts,
            ];
    
            return response()->json($response);
            // return view('test', [
            //     'response' => $response
            // ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => 'Failed Fetch All Posts', 'details' => $th->getMessage()], 500);
        }
    }

    public function addPost(Request $request)
    {
        try {
            $blog = new Blog();
            
            // Validation request
            $validatedData = $request->validate([
                'title' => 'required|string|max:255|unique:blog,title',
                'slug' => 'required|string|unique:blog,slug',
                'category' => 'required|array',
                'read_duration' => 'required|integer',
                // 'author' => 'required|exists:users,id',
                'thumbnail' => 'nullable|string', // string base64
                'content' => 'required|string',
            ]);

            // Store data blog
            $blog->author_id = $request->author;
            $blog->title = $validatedData['title'];
            $blog->slug = $validatedData['slug'];
            $blog->content = $validatedData['content'];
            $blog->read_duration = $validatedData['read_duration'];
            $blog->read_duration_type = $request->read_duration_type;

            // Process thumbnail if exist
            if (!empty($validatedData['thumbnail'])) {
                $imageData = $validatedData['thumbnail'];
                // Separate data base64 from prefix data URL
                list($type, $data) = explode(';', $imageData);
                list(, $data) = explode(',', $data);
                $data = base64_decode($data);
    
                // Create unique name file 
                $imageName = uniqid() . '.png';
                Storage::put('public/thumbnails/' . $imageName, $data);
                $blog->image = 'thumbnails/' . $imageName;
            }

            $blog->save();

            // Store blog_category
            $categories = $validatedData['category'];
            foreach ($categories as $categoryId) {
                $blog->getBlogCategory()->attach($categoryId);
            }

            return response()->json(['message' => 'Add Post successfully', $blog], 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Failed to add post.', 'details' => $e->validator->errors()->first()], 500);
        } 
        
        catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => 'Failed to add post.', 'details' => $th->getMessage()], 500);
        }
    }
}