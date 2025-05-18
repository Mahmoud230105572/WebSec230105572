<?php
    namespace App\Http\Controllers\Web;
    use Illuminate\Http\Request;
    use DB;
    use App\Http\Controllers\Controller;
    use App\Models\Product;
    use Illuminate\Foundation\Validation\ValidatesRequests;
    use Illuminate\Support\Facades\Validator;




    class ProductsController extends Controller{
        use ValidatesRequests;
        
        public function __construct(){
            $this->middleware("auth:web")->except("list");
        }
    
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

        public function edit(Request $request, Product $product = null) {
            
            if (!  auth()->user()->hasPermissionTo('edit_products')) {
                abort(401);
            }

            $product = $product??new Product();
            return view("products.edit", compact('product'));
        }


        public function save(Request $request, Product $product = null) {
            $this->validate($request, [
                'code' => ['required', 'string', 'max:32'],
                'name' => ['required', 'string', 'max:128'],
                'model' => ['required', 'string', 'max:256'],
                'description' => ['required', 'string', 'max:1024'],
                'price' => ['required', 'numeric'],
            ]);



            $product = $product??new Product();
            $product->fill($request->all());
            $product->save();


            return redirect()->route('products_list');
        }


        public function delete(Request $request, Product $product) {

            if (!  auth()->user()->hasPermissionTo('delete_products')) {
                abort(401);
            }

            $product->delete();
            return redirect()->route('products_list');
        }


        public function toggleFavourite(Product $product)   {
                if (!auth()->user()->can('select_favourite')) {
                    abort(403, 'Unauthorized action.');
                }
                $product->is_favourite = !$product->is_favourite;
                $product->save();

                return back();
            }

    }


