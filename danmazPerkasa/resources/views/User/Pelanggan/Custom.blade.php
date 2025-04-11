@extends('layouts.BasicPage1')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- <link rel="stylesheet" type="" href="{{asset('css/Custom.css')}}"> -->
<!-- <link rel="stylesheet" href="{{ secure_asset('css/Custom.css') }}"> -->
<link rel="stylesheet" href="{{ app()->environment('local')? asset('css/Custom.css') : secure_asset('css/Custom.css') }}">

@endsection

@section('content')
<div class="ContainerCustom">
    <div class="Title">
        <p>CUSTOM INSTRUMENT</p>
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
                                    <select name="{{{$b->Category}}}" data-Amount="0" data-Weight="0" data-id="{{{$b->id}}}" onchange="Save(this)" id="">
                                        <option value="0" 
                                            data-stock="0" 
                                            data-price="0" 
                                            data-weight="0" 
                                            data-photo="-"
                                            data-Category="{{{$b->id}}}"
                                            data-id="-">
                                            Select
                                        </option>
                                        @foreach($Parts as $c)
                                            @if($c->Category==$b->id)
                                                <option value="{{{$c->id_product}}}" 
                                                    data-stock="{{{$c->stok}}}" 
                                                    data-price="{{{$c->price}}}" 
                                                    data-weight="{{{($c->weight)}}}" 
                                                    data-photo="{{{$c->PhotosName}}}"
                                                    data-Category="{{{$b->id}}}"
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
            <button type="submit" class="Checkout" data-parts="" onclick="Checkout(this)">Checkout</button>
        </div>
    </div>
    <form action="/CheckoutCustom" method="POST" class="formCustom" hidden>
        @csrf
        <input type="text" class="InputForm" name="dataPart">
    </form>
    
</div>

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
        CountTotalAmountAndTotalWeight()
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
        document.querySelector('.TotalAmount').textContent = TotalAmount
        document.querySelector('.CountPart').textContent = Part
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
    function Checkout(elemen){
        let dataProduct = elemen.getAttribute('data-parts')
        
        let form = document.querySelector('.formCustom')
        let input = form.querySelector('.InputForm')
        input.value = dataProduct
        // console.log(form)
        form.submit();
        
    }
</script>
@endsection