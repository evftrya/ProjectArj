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
            <p>All Product ({{{count($data)}}})</p>
            @else
            <p>{{{$wht}}} ({{{count($data)}}})</p>
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
    async function goCheckout(idProduct, event){
        event.preventDefault();
        let adr = await fetch('/isNew');
        let isnew = await adr.json();
        if(isnew==1){
            console.log('jalannnnn')
            initializeLoadingIndicator();
            window.location.href='/Checkout/'+idProduct+'/1';
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

<script>
    // search 
    @if(isset($search))
    fillSearch('{{$search}}');

    function fillSearch(text){
        let srch = document.querySelector('.searchInp');
        srch.value = text;
        searchProduct(text);
    }
    @endif
    function searchProduct(search){
        let a = document.querySelectorAll('.TheProduk');
        a.forEach(r=>{

            let text = r.textContent.trim();
            console.log(text);
            if(text.includes(search)){
                r.style.display = 'flex';
            }
            else{
                r.style.display = 'none';

            }
        })
    }
</script>
@endsection