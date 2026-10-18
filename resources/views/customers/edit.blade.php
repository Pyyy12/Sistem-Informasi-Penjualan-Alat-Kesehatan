@extends('layouts.app')
@section('title', 'Edit Pelanggan')
@section('heading', 'Edit Pelanggan')

@section('content')
<div class="panel">
    <form action="{{ route('customers.update', $customer) }}" method="POST">
        @csrf
        @method('PUT')
        @include('customers.form')
    </form>
</div>
@endsection