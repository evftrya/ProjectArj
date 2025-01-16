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
            <svg class="bulat first" width="7" height="7" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="7" cy="7" r="7" fill="#ffffff"/>
            </svg>
            <svg class="bulat first" width="7" height="7" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="7" cy="7" r="7" fill="#ffffff"/>
            </svg>
            <svg class="bulat first" width="7" height="7" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="7" cy="7" r="7" fill="#ffffff"/>
            </svg>
            
        </div>
    </div>
    <div class="produkArea">
        <div class="subCaption">
            <svg width="93" height="4" viewBox="0 0 93 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="50" height="2" rx="2" fill="#B17457"/>
            </svg>
            <p>NEW PRODUCT</p>
        </div>
        <div class="produks">
            <a href="" class="TheProduk">
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
            </a>

            <a href="" class="TheProduk">
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
            </a>

            <a href="" class="TheProduk">
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
            </a>

            <a href="" class="TheProduk">
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
            </a>

            <a href="" class="TheProduk">
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
            </a>

            <a href="" class="TheProduk">
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
            </a>
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