<?php
    namespace App\Http\Controllers\Web;
    use Illuminate\Http\Request;
    use DB;
    use App\Http\Controllers\Controller;
    use App\Models\Product;


    class ProductsController extends Controller{
    
        public function list(Request $request) {
            $query = Product::select("products.*");

            // Filter by keywords (search in name)
            $query->when($request->keywords, fn($q) => 
                $q->where("name", "like", "%{$request->keywords}%")
            );
            
            // Filter by min price
            $query->when($request->min_price, fn($q) => 
                $q->where("price", ">=", $request->min_price)
            );
            
            // Filter by max price
            $query->when($request->max_price, fn($q) => 
                $q->where("price", "<=", $request->max_price)
            );
            
            // Sorting (order by column)
            $query->when($request->order_by, fn($q) => 
                $q->orderBy($request->order_by, $request->order_direction ?? "ASC")
            );
            
            // Get the filtered results
            $products = $query->get();
            
            


            return view("products.list", compact('products'));
        }
    }

