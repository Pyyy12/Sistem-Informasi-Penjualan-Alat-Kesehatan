@extends('layouts.app')
@section('title', 'Edit Produk')
@section('heading', 'Edit Produk')

@section('content')
<div class="panel">
    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')
        @include('products.form')
    </form>
</div>
@endsection