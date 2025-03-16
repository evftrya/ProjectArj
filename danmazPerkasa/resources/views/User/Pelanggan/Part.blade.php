@extends('layouts.BasicPage1')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" type="" href="{{asset('css/product.css')}}">
@endsection

@section('content')

<div class="LandingPage product">
    <p>tesss</p>
</div>
@endsection