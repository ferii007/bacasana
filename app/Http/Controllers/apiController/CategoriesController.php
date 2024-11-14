<?php

namespace App\Http\Controllers\apiController;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
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

    public function getAllCategories()
    {
        try {
            $today = now()->format('Y-m-d');
            $yesterday = now()->subDay()->format('Y-m-d');
            $lastWeek = now()->subWeek()->format('Y-m-d');
            $lastMonth = now()->subMonth()->format('Y-m-d');

            // Set the bindings for DB::raw placeholders
            $bindings = [
                $today, $today,
                $yesterday, $yesterday,
                $lastWeek, $yesterday,
                $lastWeek, $yesterday,
                $lastMonth, $lastWeek,
                $lastMonth, $lastWeek,
            ];

            $categories = Categories::select([
                '*',
                DB::raw('(SELECT count(*) FROM categories WHERE deleted_at IS NULL) as totalCategories'),
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

            if ($categories->isEmpty()) {
                throw new \Exception('No categories found.');
            }

            $allCategories = $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'category_name' => $category->category_name,
                    'slug' => $category->slug,
                    'created_at' => $category->created_at,
                    'updated_at' => $category->updated_at,
                    'deleted_at' => $category->deleted_at,
                ];
            });

            $stats = $this->calculateDataValueInformation($categories);

            $response = [
                'allCategories' => $allCategories,
                'totalCategoriesActive' => $allCategories->whereNull('deleted_at')->count(),
                'footer' => $stats,
            ];

            return response()->json($response);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Failed Get All Categories', 'details' => $th->getMessage()], 500);
        }
    }

    public function fetchAllCategories(Request $request) {
        try {
            $categories = Categories::orderBy('created_at', 'desc');
            $perPage = $request->input('per_page', 25);
            $status = $request->input('status', 'active');
            $search = $request->input('search', '');
            
            if ($status === 'active') {
                $categories->whereNull('deleted_at');
            } elseif ($status === 'deleted') {
                $categories->whereNotNull('deleted_at');
            }

            if (!empty($search)) {
                $categories->where('title', 'like', '%' . $search . '%');
            }

            $categories = $categories->paginate($perPage);

            $response = [
                'allCategories' => $categories,
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

    public function addCategory(Request $request)
    {
        try {
            $qwe;
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Failed to add category.', 'details' => $th->getMessage()], 500);
        }
    }
}