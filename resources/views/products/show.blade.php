
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <table border="1" width="100%">
                        <tbody>
                            <tr>
                                <th>id</th>
                                <th>ProductName</th>
                                <th>ProductShortDescription</th>
                                <th>CSProductCategory</th>
                                <th>TopLevel</th>
                                <th>SKU</th>
                                <th>NewDate</th>
                                <th>Promotion</th>
                                <th>SAPrice</th>
                                <th>BotswanaPrice</th>
                                <th>NamibiaPrice</th>
                            </tr>
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->ProductName }}</td>
                                <td>{{ $product->ProductShortDescription }}</td>
                                <td>{{ $product->CSProductCategory }}</td>
                                <td>{{ $product->TopLevel }}</td>
                                <td>{{ $product->SKU }}</td>
                                <td>{{ $product->NewDate }}</td>
                                <td>{{ $product->Promotion }}</td>
                                <td>{{ $product->SAPrice }}</td>
                                <td>{{ $product->BotswanaPrice }}</td>
                                <td>{{ $product->NamibiaPrice }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <br>

                    <h2>Stock: {{ $stockQuantity }}</h2>

                    <br>
                    <hr>
                    <br>
    <!-- //@method('PUT') -->
                    <form action="/cart" method="POST">
                        @csrf
                        <input type="number" name="Quantity" id="Quantity" value="">
                        <input type="hidden" name="id" id="id" value="{{ $product->id }}">
                        <input type="submit" name="" value="Add to cart" style="btn btn-lg btn-success">
                    </form>

                    <br>
                    <br>
                    <br>

                    <table border="1" width="100%">
                        <tbody>
                            <tr>
                                <th>ProductName</th>
                                <th>SKU</th>
                                <th>SAPrice</th>
                                <th style="text-align: center;">Quantity</th>
                                <!-- <th></th> -->
                            </tr>
                            @foreach($Items as $item) 
                                <tr>
                                    <td>{{ $item->associatedModel->ProductName }}</td>
                                    <td>{{ $item->associatedModel->SKU }}</td>
                                    <td>{{ $item->associatedModel->SAPrice }}</td>
                                    <!-- <td>{{ $item->quantity }}</td> -->
                                    <td align="right">
                                        <form action="/cart/{{ $item->id }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                            <input type="number" name="Quantity" id="Quantity" value="{{ $item->quantity }}">
                                            <input type="hidden" name="productId" id="productId" value="{{ $item->associatedModel->id }}">
                                            <input type="submit" name="submit" class="btn btn-md btn-warning" value="Update">
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
