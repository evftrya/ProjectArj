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
                    <p>BELANJA SEKARANG</p>
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
            <p>PRODUK SPESIAL</p>
        </div>

        <div class="produks">
            @foreach($Special as $s)
            <a href="/Detil-Product/{{{$s->id_product}}}" class="TheProduk {{{$s->stok==0?'Sold':''}}}">
                <p>{{{$s->isSpecial}}}</p>

                <div class="imageProduct" style="background-image: url('{{asset('storage/images/'.$s->PhotosName)}}');"></div>

                <div class="descProduct">
                    <p class="descName">{{{$s->nama_product}}}</p>
                    <p class="narateDesc">{{{$s->detail_product}}}</p>
                </div>

                <div class="bottomProductArea">
                    <p>{{{$s->price}}}</p>
                    <div class="bottomButtonProduct">
                        <button onclick="AddToCart(this, '{{{$s->id_product}}}', event)" {{{($s->stok==0||(session('isActive')=='nonActive'))?'disabled':''}}}>
                            <p>TAMBAH KE KERANJANG</p>
                        </button>
                        <button class="BuyNow" onclick="goCheckout('{{{$s->id_product}}}',event)" {{{($s->stok==0||(session('isActive')=='nonActive'))?'disabled':''}}}>
                            <p>BUY NOW</p>
                        </button>
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
        console.log(data);
        
        if((data.message.includes('success'))){
            showPopup('Berhasil ditambahkan ke keranjang');
        }
        else if(data.message.includes('NoStock')){
            showPopup('Maaf, stok produk ini habis', 0);
        }
        else{
            showPopup('Silakan login terlebih dahulu untuk melakukan aksi ini', 0);
        }
    }
    
    async function goCheckout(idProduct, event){
        event.preventDefault();
        console.log('masuk fungsi')
        let adr = await fetch('/isNew/'+idProduct);
        let isnew = await adr.json();
        console.log(isnew);

        if(isnew==1){
            console.log('masuk if')
            console.log('jalannnnn')
            initializeLoadingIndicator();
            window.location.href='/Checkout-view-direct/'+idProduct;
        }
        else if(document.querySelector('.auth')){
            console.log('masuk elif 1')
            showPopup("Silakan login terlebih dahulu untuk melakukan aksi ini", 0)
        }
        else if(isnew==2){
            console.log('masuk elif 2')
            showPopup('Maaf, stok produk ini habis', 0);
        }
        else{
            console.log('masuk else')
            showPopup("Silakan atur alamat terlebih dahulu (Pengaturan > Pengaturan Akun > Alamat)", 0)
        }
    }

    function initializeLoadingIndicator() {
        console.log('Initializing loading indicator');

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

        const spinner = document.createElement('div');
        spinner.style.border = '8px solid #f3f3f3';
        spinner.style.borderTop = '8px solid #3498db';
        spinner.style.borderRadius = '50%';
        spinner.style.width = '60px';
        spinner.style.height = '60px';
        spinner.style.animation = 'spin 1s linear infinite';

        const text = document.createElement('p');
        text.textContent = 'Kami sedang menyiapkan data Anda';
        text.style.marginTop = '20px';
        text.style.fontSize = '16px';

        loadingIndicator.appendChild(spinner);
        loadingIndicator.appendChild(text);

        document.body.appendChild(loadingIndicator);

        const styleSheet = document.styleSheets[0];
        styleSheet.insertRule(`
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `, styleSheet.cssRules.length);

        window.addEventListener('pagehide', function () {
            loadingIndicator.style.display = 'flex';
        });

        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                loadingIndicator.style.display = 'none';
            }
        });

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
