@extends('layouts.BasicPage1')

@section('css')
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
                <div class="imageProduct" style="background-image: url('{{asset('storage/images/'.$d->PhotosName)}}');">

                </div>
                <div class="descProduct">
                    <p class="descName">{{{$d->nama_product}}}</p>
                    <p class="narateDesc">{{{$d->detail_product}}}</p>
                </div>
                <div class="bottomProductArea">
                    <p>{{{$d->price}}}</p>
                    <div class="bottomButtonProduct">
                        <Button onclick="window.open('')">
                            <p>ADD TO CART</p>
                        </Button>
                        <Button class="BuyNow" onclick="window.open('')">
                            <p>BUY NOW</p>
                        </Button>
                    </div>
                </div>
            </a>
            @endforeach
            <!-- <a href="" class="TheProduk special">
                <p>NEW</p>
                <div class="imageProduct" style="background-image: url('https://i.pinimg.com/564x/c8/74/92/c8749256de694117b358abb8be45b303.jpg');">

                </div>
                <div class="descProduct">
                    <p class="descName">Accoustic Guitar</p>
                    <p class="narateDesc">Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
                </div>
                <div class="bottomProductArea">
                    <p>Rp. 1.500.000</p>
                    <div class="bottomButtonProduct">
                        <Button onclick="window.open('')">
                            <p>ADD TO CART</p>
                        </Button>
                        <Button class="BuyNow" onclick="window.open('')">
                            <p>BUY NOW</p>
                        </Button>
                    </div>
                </div>
            </a> -->
        </div>
        <div class="bottomProduct">
            <p>No More Result</p>
        </div>
    </div>
</div>
@endsection