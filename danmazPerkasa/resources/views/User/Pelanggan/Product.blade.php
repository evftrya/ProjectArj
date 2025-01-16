@extends('layouts.BasicPage1')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" type="" href="{{asset('css/product.css')}}">
@endsection

@section('content')
<div class="LandingPage product">
    <div class="produkArea product">
        <div class="subCaption product">
            <svg width="93" height="4" viewBox="0 0 93 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="50" height="2" rx="2" fill="#B17457"/>
            </svg>
            @if($wht=='AllProduct')
            <p>All Product</p>
            @else
            <p>{{{$wht}}}</p>
            @endif
        </div>
        <div class="produks">
            @foreach($data as $d)
            <a href="/Detil-Product/{{{$d->id_product}}}" class="TheProduk special">
                <p class="nospecial"></p>
                <div class="imageProduct" style="background-image: url('{{asset('storage/images/'.$d->PhotosName)}}')">

                </div>
                <div class="descProduct">
                    <p class="descName">{{{$d->nama_product}}}</p>
                    <p class="narateDesc">{{{$d->detail_product}}}</p>
                </div>
                <div class="bottomProductArea">
                    <p>{{{$d->price}}}</p>
                    <div class="bottomButtonProduct">
                        <Button onclick="AddToCart(this, '{{{$d->id_product}}}', event)">
                            <p>ADD TO CART</p>
                        </Button>
                        <Button class="BuyNow" onclick="goCheckout('{{{$d->id_product}}}',event)">
                            <p>BUY NOW</p>
                        </Button>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div class="bottomProduct">
            <p>No More Result</p>
        </div>
    </div>
</div>

<script>
    function goCheckout(idProduct, event){
        event.preventDefault();
        window.location.href='/Checkout/'+idProduct+'/1';
    }

    function AddToCart(elemen, id, event){
        event.preventDefault();
        id = parseInt(id);
        fetch(('/AddToCart/'+id),{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                qty: 1,
            })
        }).then(response=>response.json()).then(data => {
        console.log('Success:', data.message);
        })

        showPopup('successfully added to the cart');
    }

    
</script>
@endsection