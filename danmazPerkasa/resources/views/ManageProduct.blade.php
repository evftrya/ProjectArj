@extends('layouts.BasicPage1')

@section('css')
<link rel="stylesheet" type="" href="{{asset('css/ManageProduct.css')}}">
@endsection

@section('content')

<div class="LandingPage">
    <div class="titled">
        Manage Product
    </div>
    <div class="bottonsArea">
        <button onclick="ClosePopUp('open')">Add Product</button>
    </div>
    <div class="theMainList">
        <div class="TheList">
            @foreach($data as $d)
                <div class="theItems">
                    <p class="name">{{{$d->nama_product}}}</p>
                    <p>{{{$d->Category}}}</p>
                    <p>{{{$d->stok}}} Items</p>
                    <div class="theButtons">
                        <button>Edit</button>
                        <button>Delete</button>
                    </div>
                </div>
            @endforeach

            

        </div>
        
        <div class="bottomsArea">
            <p>No More Product</p>
        </div>
    </div>
</div>
<script>
    
</script>
@endsection