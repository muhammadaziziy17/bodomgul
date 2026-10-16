@extends('admin.layouts.app')

@section('heading', $slide->exists ? 'Slaydni tahrirlash' : 'Yangi slayd')

@section('content')
    @include('admin.hero-slides._form')
@endsection
