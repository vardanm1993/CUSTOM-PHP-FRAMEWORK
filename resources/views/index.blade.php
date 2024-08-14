@php use Core\Session; @endphp

@extends('layouts.app')

@section('content')
    <h2>The request method is {{ $method }}</h2>

    @session('id')
        <h3>Session redirect data test id: {{ Session::get('id') }}</h3>
    @endsession
@endsection
