<?php
    namespace App\Http\Controllers\Web;
    use Illuminate\Http\Request;
    use DB;
    use App\Http\Controllers\Controller;
    use App\Models\Product;


    class ProductsController extends Controller{
    
        public function list() {
            // $products = Product::where("price",">",5000)->get(); // where
            // $products = Product::where("code","like","tv%")->get(); //where (like)
            // $products = Product::orderBy("price")->get(); // orderBy
            // $products = Product::where("price",">",8000)->where("price","<",12000)->get(); // and
            // $products = Product::where("price","=",1200)->orwhere("price","=",9000)->get(); // or
            $products = Product::where("price", ">", 5000)
                    ->orderBy("price", "desc")
                    ->get();


            return view("products.list", compact('products'));
        }
    }

