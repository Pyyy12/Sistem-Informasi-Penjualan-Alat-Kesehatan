@extends('layouts.app')
@section('title', 'Tambah Pelanggan')
@section('heading', 'Tambah Pelanggan')

@section('content')
<div class="panel">
    <form action="{{ route('customers.store') }}" method="POST">
        @csrf
        @include('customers.form')
    </form>
</div>
@endsection