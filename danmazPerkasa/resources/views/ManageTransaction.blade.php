@extends('layouts.BasicPage1')

@section('css')
<link rel="stylesheet" type="" href="{{asset('css/ManageProduct.css')}}">
@endsection

@section('content')
<div class="LandingPage">
    <div class="titled">
        Manage Transaction
    </div>
    <div class="theMainList" style="margin-top:30px;">
        <div class="TheList">
            @foreach($data as $d)
                <div class="theItems">
                    <p class="name">{{{$d->namaUser}}}</p>
                    <p>{{{$d->created_at}}}</p>
                    <p>{{{$d->Total}}}</p>
                    <p>{{{$d->Shipping}}}</p>
                    <a href="" class="theButtons">Detail</a>
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