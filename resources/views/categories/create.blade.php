@extends('layouts.app')
@section('title', 'Tambah Kategori')
@section('heading', 'Tambah Kategori')

@section('content')
<div class="panel">
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        @include('categories.form')
    </form>
</div>
@endsection