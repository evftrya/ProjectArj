@extends('layouts.BasicPage1')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- <link rel="stylesheet" type="" href="{{asset('css/Custom.css')}}"> -->
<!-- <link rel="stylesheet" href="{{ secure_asset('css/Custom.css') }}"> -->
<link rel="stylesheet" href="{{ app()->environment('local')? asset('css/Custom.css') : secure_asset('css/Custom.css') }}">
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


@endsection

@section('content')
<div class="ContainerCustom">
    <div class="Title d-flex gap-3">
        <p>CUSTOM INSTRUMENT</p>
        <div class="dropdown" >
            <button class="btn-pointer"  class="btn btn-secondary dropdown-toggle border-0 text-black" type="button" data-bs-toggle="dropdown" aria-expanded="false"
            style="background-color: #d8d2c2;">
                {{{$active}}}
            </button>
            <ul class="dropdown-menu">
                <li><a   class="dropdown-item" href="/Custom/Guitar">Guitar</a></li>
                <li><a   class="dropdown-item" href="/Custom/Bass">Bass</a></li>
            </ul>
        </div>
    </div>
    <div class="theContent">
        <form action="" class="custom">
            <div class="customFields">
                @foreach($Areas as $a)
                <div class="customField">
                    @if($a->Area=='General')
                    <p class="TitleCustomField start">{{{$a->Area}}}</p>
                    @else
                    <p class="TitleCustomField">{{{$a->Area}}}</p>
                    @endif
                    <div>
                        @foreach($Categorys as $b)
                            @if($b->Area==$a->Area)
                                <div class="containerSelect">
                                    <p>{{{$b->Category}}}</p>
                                    <select name="{{{$b->Category}}}" data-Amount="0" data-Weight="0" data-id="{{{$b->id_category_part}}}" onchange="Save(this)" id="">
                                        <option value="0" 
                                            data-stock="0" 
                                            data-price="0" 
                                            data-weight="0" 
                                            data-photo="-"
                                            data-Category="{{{$b->id_category_part}}}"
                                            data-id="-">
                                            Select
                                        </option>
                                        @foreach($Parts as $c)
                                            @if($c->id_category_part==$b->id_category_part)
                                                <option value="{{{$c->id_product}}}" 
                                                    data-stock="{{{$c->stok}}}" 
                                                    data-price="{{{$c->price}}}" 
                                                    data-weight="{{{($c->weight)}}}" 
                                                    data-photo="{{{$c->PhotosName}}}"
                                                    data-Category="{{{$b->id_category_part}}}"
                                                    data-id="{{{$c->id_product}}}">
                                                    {{{$c->nama_product}}}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endforeach
                
            </div>
            
        </form>
        <div class="Photo">
            <div data-base-url="{{ asset('storage/images') }}" style="background-image: url('https://images.unsplash.com/photo-1510915361894-db8b60106cb1?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');">

            </div>
        </div>
    </div>
    <div class="bottomArea">
        <div class="Weight">
            <div class="text">
                <p>Weight: </p>
                <p class="TotalWeight">0</p>
                <p>Kg</p>
                <div class="text no">
                    <p>(</p>
                    <p class="CountPart">0</p>
                </div>
                <p>Parts) </p>
            </div>
        </div>
        <div class="RightSide">
            
            <div>
                <div class="text">
                    <p>Total: </p>
                    <p class="TotalAmount">0</p>
                </div>
            </div>
            <button class="btn-pointer"  type="submit" class="Checkout" data-parts="" onclick="Checkout(this)">Checkout</button>
        </div>
    </div>
    <form action="/CheckoutCustom" method="POST" class="formCustom" hidden>
        @csrf
        <input type="text" class="InputForm" name="dataPart">
    </form>
    
</div>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function Save(elemen){
        let selected = elemen.options[elemen.selectedIndex];
        let photo = selected.getAttribute('data-photo')
        let id = selected.value
        let stock = selected.getAttribute('data-stock')
        let price = selected.getAttribute('data-price')
        let weight = selected.getAttribute('data-weight')
        let category = selected.getAttribute('data-Category')
        console.log(photo, id, stock, price, weight)
        ChangePhoto(photo);
        CountTotalAmountAndTotalWeight();
        InsertFinal(id)
    }

    function CountTotalAmountAndTotalWeight(){
        // console.log('----------------------------------------------')
        let elemens = document.querySelectorAll('.containerSelect select')
        let TotalAmount = 0;
        let TotalWeight = 0;
        let Part = 0;
        elemens.forEach(e=>{
            let elemen = e.options[e.selectedIndex]
            // console.log('weight: '+elemen.getAttribute('data-weight'))
            // console.log('total: '+elemen.getAttribute('data-price'))
            TotalWeight += parseInt(elemen.getAttribute('data-weight'))
            TotalAmount += parseInt(elemen.getAttribute('data-price'))
            if(elemen.getAttribute('data-price')>0){
                Part+=1;
            }
        });

        
        document.querySelector('.TotalWeight').textContent = (TotalWeight/1000)
        document.querySelector('.TotalAmount').textContent = toIdr(TotalAmount)
        document.querySelector('.CountPart').textContent = Part


    }
    function toIdr(number){
        let angka = number;
        
        let formattedAngka = angka.toLocaleString('id-ID');
        let formatted = "Rp. " + formattedAngka;
        
        return formatted;
        
    }
    function idrToInt(string){
        let str = string;
        
        let angka = str.replace(/[^\d]/g, '');
        
        let parsedAngka = parseInt(angka, 10);
        
        return parsedAngka;
    }
    function InsertFinal(idPart){
        let elemen = document.querySelector('.Checkout')
        let dataPart = elemen.getAttribute('data-parts')
        // console.log('=====================================');
        // console.log('dataPart awal: '+dataPart);
        dataPart==''? dataPart=idPart : dataPart=(dataPart+"-"+idPart);
        // console.log('dataPart akhir: '+dataPart);
        elemen.setAttribute('data-parts', dataPart);
        // console.log('=====================================');
    }

    function ChangePhoto(Photo){
        let container = document.querySelector('.Photo div')
        let baseURL = container.getAttribute('data-base-url')
        container.style.backgroundImage = `url(${baseURL}/${Photo})`;
        // container.style.display="flex";
    }
    async function Checkout(elemen){
        let dataProduct = elemen.getAttribute('data-parts')
        
        let form = document.querySelector('.formCustom')
        let input = form.querySelector('.InputForm')
        input.value = dataProduct
        let loading = null;
        if(loading==null){
            loading=1;
            initializeLoadingIndicator();
            
        }
        else{
            showLoadingIndicator();
        }
        // console.log(form)
        let adr = await fetch('/isNew/CekAddress');
        let isnew = await adr.json();
        if(isnew==1){
            if(parseInt(document.querySelector('.TotalWeight').textContent)>0){
                form.submit();
            }
            else{
                hideLoadingIndicator();
                showPopup('Please select at least one part', 0)
            }
        }
        else{
            hideLoadingIndicator();
            showPopup("Please set the address first (Setting>Account Settings>Address)",0)
        }
        
        
    }

    function initializeLoadingIndicator() {
        hideLoadingIndicator()
        //console.log('Initializing loading indicator');

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
    
    function hideLoadingIndicator() {
        const loadingIndicator = document.getElementById('loading-indicator');
        console.log('masuk hapuss 1')
        if (loadingIndicator) {
            // loadingIndicator.style.display = 'none';
            console.log('masuk hapuss 2')
            loadingIndicator.remove();
            // loadingIndicator.style.display = 'none';
        }
    }
    function showLoadingIndicator() {
        const loadingIndicator = document.getElementById('loading-indicator');
        if (loadingIndicator) {
            loadingIndicator.style.display = 'flex'; // atau 'block' jika diperlukan
        }
    }
</script>
@endsection