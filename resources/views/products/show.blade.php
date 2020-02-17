
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
