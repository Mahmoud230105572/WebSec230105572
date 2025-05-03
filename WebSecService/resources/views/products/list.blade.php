@extends('layouts.master')
@section('title', 'Products')
@section('content')
    
    <div class="container">

        <div class="row">
            <div class="col col-10">
                <h1>Products</h1>
            </div>
            @can("edit_products")
            <div class="col col-2">
                <a href="{{route('products_edit')}}" class="btn btn-success form-control">Add Product</a>
            </div>
            @endcan
        </div>


        <div class="card">
            <div class="card-body">

                <form>
                    <div class="row">
                        <div class="col col-sm-2">
                            <input name="keywords" type="text"  class="form-control" placeholder="Search Keywords" value="{{ request()->keywords }}" />
                        </div>
                        <div class="col col-sm-2">
                            <input name="min_price" type="numeric"  class="form-control" placeholder="Min Price" value="{{ request()->min_price }}"/>
                        </div>
                        <div class="col col-sm-2">
                            <input name="max_price" type="numeric"  class="form-control" placeholder="Max Price" value="{{ request()->max_price }}"/>
                        </div>
                        <div class="col col-sm-2">
                            <select name="order_by" class="form-select">
                                <option value="" {{ request()->order_by==""?"selected":"" }} disabled>Order By</option>
                                <option value="name" {{ request()->order_by=="name"?"selected":"" }}>Name</option>
                                <option value="price" {{ request()->order_by=="price"?"selected":"" }}>Price</option>
                            </select>
                        </div>
                        <div class="col col-sm-2">
                            <select name="order_direction" class="form-select">
                                <option value="" {{ request()->order_direction==""?"selected":"" }} disabled>Order Direction</option>
                                <option value="ASC" {{ request()->order_direction=="ASC"?"selected":"" }}>ASC</option>
                                <option value="DESC" {{ request()->order_direction=="DESC"?"selected":"" }}>DESC</option>
                            </select>
                        </div>
                        <div class="col col-sm-1">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        <div class="col col-sm-1">
                            <button type="reset" class="btn btn-danger">Reset</button>
                        </div>
                    </div>
                </form>

                <div class="card mt-2">
                    <div class="card-body">
                    View search result of keywords: <span id='keywords'></span>
                    </div>
                </div>
                <script>
                    const urlParams = new URLSearchParams(window.location.search);
                    const keywords = urlParams.get('keywords');
                    document.getElementById('keywords').innerHTML = keywords;
                </script>
                
            </div>
        </div>



        <div class="row row-cols-3 g-y-2">
            @foreach ($products as $product)
            <div class="col">
                <div class="card">
                    <div class="card-img-top"><img src="{{$product->photo}}" alt="{{$product->name}}" class="img-thumbnail"></div>
                    <div class="card-body">
                        <h3>{{$product->name}}</h3>
                        
                        <div class="row mb-2">
                            @can("edit_products")
                            <div class="col">
                                <a href="{{route('products_edit', $product->id)}}" class="btn btn-success form-control">Edit</a>
                            </div>
                            @endcan

                            @can("delete_products")
                            <div class="col">
                                <a href="{{route('products_delete', $product->id)}}" class="btn btn-danger form-control">Delete</a>
                            </div>
                            @endcan
                        </div>
                        
                        
                        <table class="table table-striped">
                            <tr>
                                <td>Name</td>
                                <td>{{$product->name}}</td>
                            </tr>
                            <tr>
                                <td>Model</td>
                                <td>{{$product->model}}</td>
                            </tr>
                            <tr>
                                <td>Code</td>
                                <td>{{$product->code}}</td>
                            </tr>
                            <tr>
                                <td>Price</td>
                                <td style="color:rgb(41, 219, 41); font-weight:bold;">{{$product->price}}</td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td>{{$product->description}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

                @endforeach
            </div>
            
    
        
    </div>

@endsection