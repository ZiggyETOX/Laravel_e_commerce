@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard

                    <br>
                    <br>

                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <br>
                    <br>
                    <ul>
                        @foreach($products as $product)

                        <li><a href="/products/{{ $product->id }}">{{ $product->SKU }} : {{ $product->ProductName }}</a></li>
                        
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
