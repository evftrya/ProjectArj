@extends('layouts.BasicPage1')

@section('css')
<link rel="stylesheet" type="" href="{{asset('css/ManageProduct.css')}}">
@endsection

@section('content')

<div class="LandingPage">
    <div class="titled">
        Part
    </div>
    <div class="bottonsArea">
        <button onclick="ClosePopUp('open')">Add Part</button>
    </div>
    <div class="theMainList">
        <div class="TheList">
            @foreach($data as $d)
            <div class="theItems">
                <p>{{{$d->nama_product}}}</p>
                <p>{{{$d->Category}}}</p>
                <p>{{{$d->price}}}</p>
                <p>{{{$d->stok}}} Items</p>
                <div class="theButtons">
                    <button>Edit</button>
                    <button>Delete</button>
                </div>
            </div>
            @endforeach
            

        </div>
        
        <div class="bottomsArea">
            <p>No More Part</p>
        </div>
    </div>
</div>
<script>
    
</script>
@endsection