@extends('layouts/app')

@section('content')
    <h2>The request method is {{ $method }}</h2>

    @isset($_SESSION['id'])
        <h3>Session redirect data test id: {{ $_SESSION['id'] }}</h3>
    @endisset
@endsection
