@extends('layouts.master')
@section('title', 'Products')
@section('content')
    
    <div class="container">
        
            <div class="row row-cols-3 g-y-2">
                @foreach ($products as $product)
                <div class="col">
                    <div class="card">
                        <div class="card-img-top"><img src="{{$product->photo}}" alt="{{$product->name}}" class="img-thumbnail"></div>
                        <div class="card-body">
                            <h2>{{$product->name}}</h2>
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