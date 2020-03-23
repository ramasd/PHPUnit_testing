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

        @if(Session::has('deleted'))
            <div class="alert alert-warning">
                {{ Session::get('deleted') }}
                @php
                    Session::forget('deleted');
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
                                    @if (auth()->user()->is_admin)
                                        <th>Actions</th>
                                    @endif
                                </tr>
                            @endif
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $product->name}}</td>
                                    <td>{{ $product->price}}</td>
                                    <td>{{ $product->price_eur}}</td>
                                    @if (auth()->user()->is_admin)
                                        <td>
                                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary">Edit</a>

                                            <form action="{{ route('products.destroy', $product->id) }}"
                                                method="POST" onsubmit="return confirm('Are You sure?');" style="display:inline-block">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="submit" class="btn btn-sm btn-danger" value="Delete">
                                            </form>
                                        </td>
                                    @endif
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