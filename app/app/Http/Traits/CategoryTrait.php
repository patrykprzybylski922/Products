<?php

namespace App\Http\Traits;

use App\Models\Category;
use Illuminate\Support\Facades\Http;

trait CategoryTrait {
    public function getCategories($token)
    {
        $categories_url = 'https://devshop-376948.shoparena.pl/webapi/rest/categories?limit=50';
        $response_categories = Http::withOptions([
            'verify' => false
        ])->withToken($token)->get($categories_url);

        $this->createCategories($response_categories->json('list'));
    }

    public static function createCategories($categories)
    {
        foreach($categories as $category)
        {
            //check if category exist, if not add to db
            $existing_category = Category::where('category_id', $category['category_id'])->first();
            if(empty($existing_category))
            {
                $new_category = new Category();
                $new_category->category_id = $category['category_id'];
                $new_category->name = $category['translations']['pl_PL']['name'];
                $new_category->save();
            }
        }
    }
}
