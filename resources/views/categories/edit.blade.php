@extends('layouts.app')
@section('title', 'Edit Kategori')
@section('heading', 'Edit Kategori')

@section('content')
<div class="panel">
    <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        @include('categories.form')
    </form>
</div>
@endsection