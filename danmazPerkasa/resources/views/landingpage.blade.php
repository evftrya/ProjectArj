@extends('layouts.BasicPage1')
@section('content')
<div class="LandingPage">
    <div class="freePoster" >
        <div class="contentContainer">
            @foreach($Content as $a)
            <div class="theContent" style="background-image: url('{{ asset('storage/images/' . $a->PhotosName) }}');">
                <p>{{{$a->shortQuotes}}}</p>
                <a href="/Detil-Product/{{{$a->id_product}}}">
                    <p>SHOP NOW</p>
                </a>
            </div>
            @endforeach
        </div>
        
        <div class="buletan">
        @foreach($Content as $a)
            <svg class="bulat first" width="7" height="7" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="7" cy="7" r="7" fill="#ffffff"/>
            </svg>
        @endforeach
            
        </div>
    </div>
    <div class="produkArea">
        <div class="subCaption">
            <svg width="93" height="4" viewBox="0 0 93 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="50" height="2" rx="2" fill="#B17457"/>
            </svg>
            <p>SPECIAL PRODUCT</p>
        </div>
        <div class="produks">
            @foreach($Special as $s)
            <a href="/Detil-Product/{{{$s->id_product}}}" class="TheProduk">
                <p>{{{$s->isSpecial}}}</p>
                <div class="imageProduct" style="background-image: url('{{asset('storage/images/'.$s->PhotosName)}}');">

                </div>
                <div class="descProduct">
                    <p class="descName">{{{$s->nama_product}}}</p>
                    <p class="narateDesc">{{{$s->detail_product}}}</p>
                </div>
                <div class="bottomProductArea">
                    <p>{{{$s->price}}}</p>
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
        </div>
    </div>
</div>

<script>
scrollPhotos();
function scrollPhotos() {
        let photosCont = document.querySelector('.contentContainer');
        console.log(photosCont);
        
        let debounceTimeout;

        photosCont.addEventListener('scroll', function() {
            clearTimeout(debounceTimeout);

            debounceTimeout = setTimeout(function() {
                let closestPhoto = null;
                let closestDistance = Infinity;
                let closestIndex = -1;
                let photos = photosCont.querySelectorAll('.theContent');
                console.log("panjang photos: "+photos.length)
                photos.forEach((p, index) => {
                    let photoRect = p.getBoundingClientRect();
                    let contRect = photosCont.getBoundingClientRect();
                    // console.log('photorect: '+photoRect.width);
                    // console.log('contrect: '+contRect.left);

                    let distanceToCenter = Math.abs(photoRect.left + photoRect.width / 2 - (contRect.left + contRect.width / 2));

                    if (distanceToCenter < closestDistance) {
                        closestDistance = distanceToCenter;
                        closestPhoto = p;
                        closestIndex = index;
                    }
                });

                if (closestPhoto) {
                    let ScrollAmt = closestPhoto.offsetLeft - (photosCont.offsetWidth / 2) + (closestPhoto.offsetWidth / 2);
                    photosCont.scrollLeft = ScrollAmt;
                    
                }
            }, 100);
        });
    }
</script>
@endsection