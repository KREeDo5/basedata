<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Traits\Functions;

class Category extends Model
{
    use Functions;
    protected $table = 'category';
    public $timestamps = false;

    public function getAllCategories()
    {
        $categories = Category::select('id', 'title', 'id_parent_category')
            ->addSelect(DB::raw('(SELECT EXISTS(SELECT 1 FROM category AS sub WHERE sub.id_parent_category = category.id)) as has_subcategories'))
            ->get();

        $response = [
            'meta' => ['success' => true, 'error' => ''],
            'data' => ['categories' => $categories]
        ];
        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function getCategories($parentCategoryId)
    {
        $categories = Category::where('id_parent_category', $parentCategoryId)
            ->select('id', 'title')
            ->get();

        return response()->json($categories, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function createCategory(Request $data)
    {
        if (Category::where('id_parent_category', $data->input('parentCategoryId'))
            ->where('title', $data->input('title'))
            ->exists())
        {
            $response = [
                'meta' => ['success' => false, 'error' => 'this category is already exist'],
                'data' => (object) []
            ];
            return response()->json($response, 409);
        }
        Category::insert([
            'title' => $data->input('title'),
            'id_parent_category' => $data->input('parentCategoryId')
        ]);
        $response = [
            'meta' => ['success' => true, 'error' => ''],
            'data' => (object) []
        ];
        return response()->json($response, 200);
    }
}
