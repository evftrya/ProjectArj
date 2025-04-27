@extends('layouts.BasicPage1')

@section('css')
<!-- <link rel="stylesheet" type="" href="{{asset('css/productDetil.css')}}"> -->
<!-- <link rel="stylesheet" href="{{ secure_asset('css/productDetil.css') }}"> -->
<link rel="stylesheet" href="{{ app()->environment('local')? asset('css/productDetil.css') : secure_asset('css/productDetil.css') }}">


<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')

<div class="containerProductDetil">
    @if($isNull==0)
        <div class="mainArea">
            <div class="PhotosArea">
                <div class="MainPhotos" >
                    @if($product->PhotosName)
                    <div class="fillMainPhotos" id="MainPhoto" style="background-image: url('{{ asset('storage/images/'.$product->PhotosName) }}');">
                    </div>
                    @else
                    <div class="fillMainPhotos NoPhoto" id="MainPhoto">

                        No Photo Yet
                    </div>
                    @endif
                </div>
                @if($product->PhotosName)
                    <div class="thePhotosArea">
                        <div class="TheScroll">
                            <svg width="14" height="38" viewBox="0 0 14 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.928085 20.131L12.2482 36.7247C12.4371 37.0017 12.6881 37.1562 12.949 37.1562C13.21 37.1562 13.4609 37.0017 13.6498 36.7247L13.662 36.706C13.7539 36.5717 13.827 36.41 13.877 36.2308C13.927 36.0517 13.9528 35.8587 13.9528 35.6638C13.9528 35.4688 13.927 35.2758 13.877 35.0967C13.827 34.9175 13.7539 34.7559 13.662 34.6216L3.00199 18.9966L13.662 3.37783C13.7539 3.24353 13.827 3.08189 13.877 2.90273C13.927 2.72356 13.9528 2.53062 13.9528 2.33564C13.9528 2.14066 13.927 1.94772 13.877 1.76855C13.827 1.58939 13.7539 1.42774 13.662 1.29345L13.6498 1.2747C13.4609 0.997684 13.21 0.843151 12.949 0.843151C12.6881 0.843151 12.4371 0.997684 12.2482 1.2747L0.928085 17.8685C0.828528 18.0144 0.749271 18.1899 0.695116 18.3843C0.640961 18.5788 0.613037 18.7882 0.613037 18.9997C0.613037 19.2113 0.640961 19.4206 0.695116 19.6151C0.749271 19.8095 0.828528 19.985 0.928085 20.131Z" fill="black"/>
                            </svg>
                        </div>
                        <div class="ThePhotos">
                            <div class="justContainer">
                                <div onclick="changeMainPhoto(this)" class="ThePhoto" style="background-image: url('{{ asset('storage/images/'.$product->PhotosName) }}');">
                                </div>
                            </div>
                            @foreach($photos as $photo)
                            <div class="justContainer">
                                <div onclick="changeMainPhoto(this)" class="ThePhoto" style="background-image: url('{{ asset('storage/images/'.$photo->PhotosName) }}');">
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="TheScroll">
                            <svg width="14" height="38" viewBox="0 0 14 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.0717 17.869L1.75152 1.2753C1.56263 0.998283 1.31169 0.84375 1.05073 0.84375C0.789778 0.84375 0.538836 0.998283 0.349954 1.2753L0.337765 1.29405C0.245881 1.42834 0.172716 1.58998 0.12272 1.76915C0.0727234 1.94832 0.0469408 2.14126 0.0469408 2.33624C0.0469408 2.53121 0.0727234 2.72415 0.12272 2.90332C0.172716 3.08249 0.245881 3.24413 0.337765 3.37842L10.9978 19.0034L0.337765 34.6222C0.245881 34.7565 0.172716 34.9181 0.12272 35.0973C0.0727234 35.2764 0.0469408 35.4694 0.0469408 35.6644C0.0469408 35.8593 0.0727234 36.0523 0.12272 36.2314C0.172716 36.4106 0.245881 36.5723 0.337765 36.7065L0.349954 36.7253C0.538836 37.0023 0.789778 37.1568 1.05073 37.1568C1.31169 37.1568 1.56263 37.0023 1.75152 36.7253L13.0717 20.1315C13.1712 19.9856 13.2505 19.8101 13.3046 19.6157C13.3588 19.4212 13.3867 19.2118 13.3867 19.0003C13.3867 18.7887 13.3588 18.5794 13.3046 18.3849C13.2505 18.1905 13.1712 18.015 13.0717 17.869Z" fill="black"/>
                            </svg>

                        </div>
                        
                    </div>
                @endif
                
            </div>
            <div class="ActionArea">
                <p class="ProductName">{{{$product->nama_product}}}</p>
                <p class="ProductPrice">{{{$product->price}}}</p>
                <div class="ProductQty">
                    <div class="textDetil">
                        <p class="withpad">{{{$product->stok}}} Unit left</p>
                        <p class="withpad berat">({{{$product->weight_kg}}} kg/Product)</p>
                    </div>
                </div>
                <div class="buttonArea">
                    <!-- <form action=""></form> -->
                    @if(session('user_id')==0)
                        <button onclick="toLogin(event)" style="margin-left:50px; background-Color: #B17457;color: white;">
                            Login to Buy
                        </button>
                    @else
                        <!-- <button class="atc" onclick="AddToCart(this, '{{{$product->id_product}}}')">
                            <p>Add To Cart</p>
                        </button>

                        <button class="co" onclick="goCheckout('{{{$product->id_product}}}',event)">
                            <p>Checkout</p>
                        </button> -->

                        <button class="justButton" onclick="TurnEdit('{{{$product->id_product}}}',event)">Edit</button>
                        <button onclick="DeleteProduct('{{{$product->id_product}}}',event)">Delete</button>
                    @endif
                    
                </div>
            </div>
        </div>
        <div class="descArea">
            <p>{{{$product->detail_product}}}</p>
            <p>{{{$product->Features}}}</p>
            <div class="specs">
            </div>
        </div>
    @else
        <div class="whenNull">
            Sorry, the product has been deleted or is out of stock
        </div>
    
    @endif
</div>


<script>

    

    // Tampilkan pop-up setelah halaman dimuat
    
    TideUp();

    DeleteTempCheckout();

    function toLogin(event){
        event.preventDefault();
        window.location.href='/Login';
    }

    async function DeleteTempCheckout(){
        let response = await fetch('/deleteTempCheckout');
    }
    function changeMainPhoto(e){
        let mainphoto = document.getElementById('MainPhoto');
        let change = (e.style.backgroundImage);
        mainphoto.style.backgroundImage = change;
    }

    async function goCheckout(idProduct, event){
        event.preventDefault();
        let adr = await fetch('/isNew');
        let isnew = await adr.json();
        if(isnew==1){
            console.log('jalannnnn')
            initializeLoadingIndicator();
            window.location.href='/Checkout-view-direct/'+idProduct;
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
    document.addEventListener('DOMContentLoaded',function(){
        const minusBtn = document.querySelector('.start.minus');
        const plusbtn = document.querySelector('.end.plus');

        minusBtn.addEventListener('click', function(event){
            changeQty('minus', event);
            console.log('masuk');
        })
        plusbtn.addEventListener('click', function(event){
            changeQty('plus', event);
        })
    })

    function changeQty(wht,event){
        event.preventDefault();
        console.log('masuk');
        let qty = document.querySelector('.qtynumbers .mid p');
        let max = parseInt(document.querySelector('.ProductQty .withpad').textContent.trim().match(/\d+/)[0]);
        let nmr = parseInt(qty.textContent)
        if(wht=='plus'){
            if(nmr!=max){
                nmr+=1;
            }
        }
        else{
            if(nmr!=1){
                nmr-=1;
            }
        }
        qty.textContent = nmr;
    }

    function AddToCart(elemen, id){
        console.log('id: '+id);
        id = parseInt(id);
        let qty = document.querySelector('.qtynumbers .mid p');
        fetch(('/AddToCart/'+id),{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                qty: parseInt(qty.textContent),
            })
        }).then(response=>response.json()).then(data => {
            console.log('Success:', data.message);
            showPopup('successfully added to the cart');
        })

    }


    function TideUp(){
        let price = document.querySelector('.ProductPrice');
        price.textContent = fixMoney(price.textContent)
    }
    function fixMoney(number){
        let angka = parseFloat(number);
        if(isNaN(angka)){
            return number;
        }

        let formattedAngka = angka.toLocaleString('id-ID',{minimumFractionDigits: 0});
        let formatted = "Rp. " + formattedAngka;

        return formatted;
    }
</script>
@endsection