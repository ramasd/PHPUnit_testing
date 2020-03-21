@extends('layouts.app')

@section('content')
    <div class="container">
        
        @if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
                @php
                    Session::forget('success');
                @endphp
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Products</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (auth()->user()->is_admin)
                            <a href="{{ route('products.create') }}" class="btn btn-primary">Add new product</a>
                            <br /><br />
                        @endif

                        <table class="table">
                            @if ($products->count())
                                <tr>
                                    <th>Product name</th>
                                    <th>Price</th>
                                    <th>Price (EUR)</th>
                                </tr>
                            @endif
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $product->name}}</td>
                                    <td>{{ $product->price}}</td>
                                    <td>{{ $product->price_eur}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">No products found.</td>
                                </tr>
                            @endforelse
                        </table>

                        {{ $products->links() }}

                    </div>
                </div>
            </div>
        </div>
    </div> 
@endsection