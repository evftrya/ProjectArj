@extends('layouts.BasicPage1')

@section('css')
<link rel="stylesheet" type="" href="{{asset('css/ManageProduct.css')}}">
@endsection

@section('content')
<div class="LandingPage">
    <div class="titled">
        Manage User
    </div>
    <div class="theMainList" style="margin-top:30px;">
        <div class="TheList">
            @foreach($data as $d)
                <div class="theItems">
                    <p class="name">{{{$d->name}}}</p>
                    <p>{{{$d->email}}}</p>
                    <p>{{{$d->Phone}}}</p>
                    <p>{{{$d->role}}}</p>
                    <div class="theButtons">
                        <button>Detail</button>
                    </div>
                </div>
            @endforeach

        </div>
        
        <div class="bottomsArea">
            <p>No More User</p>
        </div>
    </div>
</div>
<script>
    
</script>
@endsection