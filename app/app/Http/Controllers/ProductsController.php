<?php

namespace App\Http\Controllers;

use App\Http\Traits\CategoryTrait;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class ProductsController extends Controller
{
    use CategoryTrait;

    public function getProducts(Request $request)
    {
        $url = 'https://devshop-376948.shoparena.pl/webapi/rest/auth';
        $response = Http::withOptions([
            'verify' => false
    ])->withBasicAuth('webapi', 'Webapi4321;')->post($url);

        $token = $response->json('access_token');

        $products_url = 'https://devshop-376948.shoparena.pl/webapi/rest/products?limit=50';
        $response_products = Http::withOptions([
            'verify' => false
        ])->withToken($token)->get($products_url);

        //create products
        $this->createProducts($response_products->json('list'));
        //get categories
        $this->getCategories($token);

        return redirect('/')->with('success','Poprawnie pobrano produkty');
    }

    public static function createProducts($products)
    {
        //delete products from db
        Product::truncate();

        //add 20 random products
        $randomItems = Arr::random($products, 20);
        foreach($randomItems as $product)
        {
            $new_product = new Product;
            $new_product->name = $product['translations']['pl_PL']['name'];
            $new_product->product_id =  $product['product_id'];
            $new_product->description = $product['translations']['pl_PL']['description'];
            $new_product->price = $product['stock']['price'];
            $new_product->category_id =  $product['category_id'];
            $new_product->add_date = Carbon::parse( $product['add_date'])->format('Y-m-d H:i');
            $new_product->save();
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();

        return redirect('/')
            ->with('success','Produkt usunięty poprawnie!');
    }


}
