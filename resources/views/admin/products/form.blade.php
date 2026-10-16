@extends('admin.layouts.app')

@section('heading', $product->exists ? 'Mahsulotni tahrirlash' : 'Yangi mahsulot')

@section('content')
    @include('admin.products._form')
@endsection
