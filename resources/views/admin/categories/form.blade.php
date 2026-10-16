@extends('admin.layouts.app')

@section('heading', $category->exists ? 'Kategoriyani tahrirlash' : 'Yangi kategoriya')

@section('content')
    @include('admin.categories._form')
@endsection
