@extends('layouts/app')

@section('content')
    <h2>This is show page with id: {{ $test->id }} </h2>
    <h3>Name: {{ $test->name }}</h3>
    <h4>Description: {{ $test->description }}</h4>
@endsection
