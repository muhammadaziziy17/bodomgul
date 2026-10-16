@extends('admin.layouts.app')

@section('heading', $locale->exists ? 'Tilni tahrirlash' : 'Yangi til')

@section('content')
    @include('admin.locales._form')
@endsection
