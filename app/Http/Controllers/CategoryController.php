<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getCategories()
    {
        $categories = Category::where('delete_flag', 0)
            ->get(['category_id as id', 'category_name as name']);

        return response()->json(['categories' => $categories]);
    }

    public function addCategory(Request $request)
    {
        $response = [
            'success' => false,
            'message' => 'Cannot complete request'
        ];
        if (Auth::check() && Auth::user()->type == 2) {
            if ($request->isMethod('post')) {
                $categoryName = $request->input('category-name');

                try {
                    $category = new Category();
                    $category->category_name = $categoryName;
                    $category->save();

                    $response = [
                        'success' => true,
                        'message' => 'Category added successfully.'
                    ];
                } catch (\Exception $e) {
                    Log::error('Error adding category: ' . $e->getMessage());
                    $response = [
                        'success' => false,
                        'message' => $e->getMessage()
                    ];
                }
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Invalid request method'
                ];
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Cannot process your request'
            ];
        }


        return response()->json($response);
    }
}
