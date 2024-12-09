<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    public $timestamps = false;

    public function getCategories($parentCategoryId)
    {
        $categories = Category::where('id_parent_category', $parentCategoryId)
            ->select('id', 'title')
            ->get();

        return response()->json($categories, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
