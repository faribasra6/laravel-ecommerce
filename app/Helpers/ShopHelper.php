<?php

use App\Mail\OrderMails;
use App\Models\admin\Category;
use App\Models\admin\Page;
use App\Models\admin\ProductImage;
use App\Models\shop\Order;
use Illuminate\Support\Facades\Mail;

    function getCategories()
    {
        return Category::orderBy('name', 'DESC')
        ->where('status', 1)
        ->with('subcategories')
        ->orderBy('id', 'DESC')
        ->where('showHome', 'Yes')->get();
    }
    
    function getProductImage($productID){
        return ProductImage::where('product_id', $productID)->first();
    }
    
    function staticPages(){
        $pages = Page::orderBy('title','ASC')->get();
        return $pages;
    }
    


    
?>