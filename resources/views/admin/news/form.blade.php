@extends('admin.layouts.app')

@section('heading', $item->exists ? 'Yangilikni tahrirlash' : 'Yangi yangilik')

@section('content')
    @include('admin.news._form')
@endsection
