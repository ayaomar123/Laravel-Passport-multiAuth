<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function getProduct(){
        $path = public_path() . "/product.json";
        $products = \File::get($path);
        return  json_decode($products, true);

    }


    public function getPrice(){
        $path = public_path() . "/product.json";
        return collect(json_decode(\File::get($path), true))->map(function ($item) {
            return collect($item)->sum('price');
        });
    }


    public function getFilter(){
        $path = public_path() . "/product.json";
        $filtered = collect(json_decode(\File::get($path), true))->map(function ($item) {
            return collect($item)->filter(function ($item2) {
                return collect($item2)->search('Lamps') || collect($item2)->search('Pains');
//                return data_get($item2, 'product_type') == "Pains" || data_get($item2, 'product_type') == "Lamps";
//                return $item2['product_type'] == 'Lamps' || $item2['product_type'] == 'Pains';

            });
        });

            return $filtered->all();

    }

    public function walletAndPainsPrice(){
        $path = public_path() . "/product.json";
        $products=   json_decode(\File::get($path), true);
        $sum = 0;
        foreach ($products as $product){
            foreach ($product as $item){
                if($item['product_type'] == "Pains" || $item['product_type'] == "product_type"){
                    foreach ($item['options'] as $option){
                        $sum += $option['price'];
                    }
                }
            }
            return $sum;
        }
    }

    public function walletAndPainsPriceCollection(){
        $path = public_path() . "/product.json";
        $collections = collect(json_decode(\File::get($path), true))->map(function ($item) {
            return collect($item)->filter(function ($item2) {
//                return $item2['product_type'] == 'Wallet' || $item2['product_type'] == 'Pains';
                return collect($item2)->search('Wallet') || collect($item2)->search('Pains');
            });
        });

        return $collections->map(function ($data){
                return collect($data)->sum('price');
                });


    }
}
