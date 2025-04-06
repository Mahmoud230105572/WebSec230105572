@extends('layouts.master')

@section('title', 'Bought Products')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Bought Products</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Model</th>
                <th>Price</th>
                <th>Purchase Date</th>
                @can("edit_products")
                    <th>User Name</th>
                @endcan
            </tr>
        </thead>
        <tbody>
            @foreach($boughtProducts as $boughtProduct)
            <tr>
                <td>{{ $boughtProduct->product->name }}</td>
                <td>{{ $boughtProduct->product->model }}</td>
                <td>{{ $boughtProduct->product->price }}</td>
                <td>{{ $boughtProduct->created_at }}</td>
                @can("edit_products")
                    <td>{{ $boughtProduct->user->name }}</td>
                @endcan
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
