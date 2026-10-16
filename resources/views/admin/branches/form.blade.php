@extends('admin.layouts.app')

@section('heading', $branch->exists ? 'Filialni tahrirlash' : 'Yangi filial')

@section('content')
    @include('admin.branches._form')
@endsection
