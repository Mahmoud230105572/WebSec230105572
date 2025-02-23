@extends('layouts.master')
    @section('title', 'Supermarket Bill')
    @section('content')
    <div class="card ">
        <div class="card-header">{{$bill->supermarket}} - {{$bill->pos}}</div>
        <div class="card-body">
            <table class="table .table-responsive-lg">
                @foreach ($bill->products as $product)
                <tr>
                    <td>{{$product->unit}}</td>
                    <td>{{$product->name}}</td>
                    <td>{{$product->price}} {{$product->unit}}</td>
                    <td>{{$product->price * $product->quantity}} {{$product->unit}}</td>
                </tr>
                @endforeach
            </table>
            Total : {{$bill->total}}
        </div>
    </div>
@endsection