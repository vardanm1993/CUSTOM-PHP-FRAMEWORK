@php use Core\Session; @endphp

@extends('layouts.app')

@section('content')
    <h2>The request method is {{ $method }}</h2>

    @session('id')
        <h3>Session redirect data test id: {{ Session::get('id') }}</h3>
    @endsession

    <form action="/store" method="post">
        <input type="text" name="name" value="{{old('name')}}">
        @error('name')
        <div>{{$message}}</div>
        @enderror
        <input type="text" name="description" value="{{old('description')}}">
        @error('description')
        <div>{{$message}}</div>
        @enderror
        <button type="submit">send</button>
    </form>

@endsection
