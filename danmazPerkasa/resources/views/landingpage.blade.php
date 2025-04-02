@extends('layouts.BasicPage1')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

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
                        <Button onclick="AddToCart(this, '{{{$s->id_product}}}', event)">
                            <p>ADD TO CART</p>
                        </Button>
                        <Button class="BuyNow" onclick="goCheckout('{{{$s->id_product}}}',event)">
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
    DeleteTempCheckout()
    async function DeleteTempCheckout(){
        let response = await fetch('/deleteTempCheckout');
    }

    async function AddToCart(elemen, id, event){
        event.preventDefault();
        id = parseInt(id);
        let cek = null;
        let response = await fetch(('/AddToCart/'+id),{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                qty: 1,
            })
        });

        let data = await response.json();
        
        if((data.message.includes('success'))){
            showPopup('successfully added to the cart');
        }
        else{
            showPopup('Please log in first to perform this action',0);
        }
        // showPopup('Please log in first to perform this action',0);
    }

    async function goCheckout(idProduct, event){
        // let session = "{{session('Role')}}";
        
        event.preventDefault();
        let adr = await fetch('/isNew');
        let isnew = await adr.json();
        if(isnew==1){
            console.log('jalannnnn')
            initializeLoadingIndicator();
            window.location.href='/Checkout-view-direct/'+idProduct;
        }
        else if(document.querySelector('.auth')){

            showPopup("Please log in first to perform this action)",0)
        }
        else{
            showPopup("Please set the address first (Setting>Account Settings>Address)",0)
        }
    }

    function initializeLoadingIndicator() {
        console.log('Initializing loading indicator');

        // Buat elemen loading indicator
        const loadingIndicator = document.createElement('div');
        loadingIndicator.id = 'loading-indicator';
        loadingIndicator.style.display = 'none';
        loadingIndicator.style.position = 'fixed';
        loadingIndicator.style.top = '0';
        loadingIndicator.style.left = '0';
        loadingIndicator.style.width = '100%';
        loadingIndicator.style.height = '100%';
        loadingIndicator.style.background = 'rgba(0, 0, 0, 0.5)';
        loadingIndicator.style.zIndex = '9999';
        loadingIndicator.style.display = 'flex';
        loadingIndicator.style.alignItems = 'center';
        loadingIndicator.style.justifyContent = 'center';
        loadingIndicator.style.flexDirection = 'column';
        loadingIndicator.style.color = 'white';
        loadingIndicator.style.fontFamily = 'Arial, sans-serif';
        loadingIndicator.style.textAlign = 'center';

        // Tambahkan spinner
        const spinner = document.createElement('div');
        spinner.style.border = '8px solid #f3f3f3';
        spinner.style.borderTop = '8px solid #3498db';
        spinner.style.borderRadius = '50%';
        spinner.style.width = '60px';
        spinner.style.height = '60px';
        spinner.style.animation = 'spin 1s linear infinite';

        // Tambahkan teks
        const text = document.createElement('p');
        text.textContent = 'We are preparing your data';
        text.style.marginTop = '20px';
        text.style.fontSize = '16px';

        // Masukkan spinner dan teks ke dalam loading indicator
        loadingIndicator.appendChild(spinner);
        loadingIndicator.appendChild(text);

        // Tambahkan loading indicator ke dalam body
        document.body.appendChild(loadingIndicator);

        const styleSheet = document.styleSheets[0];
        styleSheet.insertRule(`
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `, styleSheet.cssRules.length);

        // Event untuk menampilkan loading hanya jika bukan navigasi dari cache
        window.addEventListener('pagehide', function () {
            loadingIndicator.style.display = 'flex';
            });

            // Event untuk menyembunyikan loading saat halaman dimuat kembali
            window.addEventListener('pageshow', function (event) {
                if (event.persisted) {
                    // Jika halaman dimuat dari cache, sembunyikan loading
                    loadingIndicator.style.display = 'none';
                }
            });

            // Event untuk navigasi biasa (bukan back/forward)
            window.addEventListener('beforeunload', function () {
                loadingIndicator.style.display = 'flex';
            });
    }

    
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