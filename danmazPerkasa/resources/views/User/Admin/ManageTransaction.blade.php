@extends('layouts.BasicPage1')

@section('css')
<!-- <link rel="stylesheet" type="" href="{{asset('css/ManageProduct.css')}}"> -->
<!-- <link rel="stylesheet" href="{{ secure_asset('css/ManageProduct.css') }}"> -->
<link rel="stylesheet" href="{{ app()->environment('local')? asset('css/ManageProduct.css') : secure_asset('css/ManageProduct.css') }}">

@endsection

@section('content')
<div class="LandingPage">
    <div class="titled">
        Manage Transaction
        <!-- <div>
            <div class="active">All</div>
            <div class="">Need Action</div>
        </div> -->
    </div>
    <div class="theMainList" style="margin-top:30px;">
        <div class="TheList">
            @foreach($data as $d)
                <div class="theItems" onclick="viewProduct('{{{$d->id}}}','{{{$d->type_transaction}}}')">
                    <p class="name">{{{$d->namaUser}}}</p>
                    <p>{{{$d->type_transaction}}}</p>
                    <p>{{{$d->created_at}}}</p>
                    <p>{{{$d->TotalShopping}}}</p>
                    <p>{{{json_decode($d->Shipping)[2].' ('.json_decode($d->Shipping)[1].')'}}}</p>
                    <a   style="cursor: pointer;" href="/Transaction/{{ $d->id }}" class="theButtons">Detail</a>
                </div>
            @endforeach

        </div>
        
        <div class="bottomsArea">
            <p>No More User</p>
        </div>
    </div>
</div>
<script>

    
    function clearBottom(){
        console.log('clear bottom run')
        let bottom = document.querySelector('.toCheckout.transaction')
        let divs = bottom.querySelectorAll('div')
            divs.forEach(e=>{
                bottom.remove(e)
            })
        
    }
     async function AcceptOrder(event,idTransaction){
        event.stopPropagation();

        let response = await fetch('/Transaction/AcceptOrder/'+idTransaction);
        let data = await response.json();
        if(data=='Success'){
            let OrderStatus = document.querySelector('.OrderStatus')
            OrderStatus.textContent = "Accept";
            clearBottom()
        }
    }
    async function RejectOrder(event,idTransaction){
        event.stopPropagation();

        let response = await fetch('/Transaction/RejectOrder/'+idTransaction);
        let data = await response.json();
        if(data=='Success'){
            let OrderStatus = document.querySelector('.OrderStatus')
            OrderStatus.textContent = "Rejected";
            clearBottom()
        }
    }
    function viewProduct(id,type){
        DetilProductAdd(type)
        fetch('{{$TemplateRoute}}'+id)
            .then(response => response.text())
            .then(html => { 
                let show = document.querySelector('.BodyDetail');
                show.innerHTML = html;

                CountAllProduct();
                FixIDR();
        })
        .catch(err => {
            console.error('Gagal memuat konten:', err);
        });
    }

    function CountAllProduct(){
        let product = document.querySelectorAll('.theProduct')
        count = 0;
        product.forEach(e=>{
            // console.log('price',idrToInt(document.querySelector('.ProductPrice.Price').textContent))
            // console.log('qty',document.querySelector('.mid input').textContent)
            count += e.querySelector('.ProductTotal').textContent =
                idrToInt(e.querySelector('.ProductPrice.Price').textContent)
                *e.querySelector('.mid input').value;
            
                
        })
        document.querySelector('.totalProductPrice').textContent=toIdr(count);
    }

    function FixIDR(){
        let product = document.querySelectorAll('.theProduct')
        product.forEach(e=>{
            e.querySelector('.ProductPrice.Price').textContent = toIdr(idrToInt(e.querySelector('.ProductPrice.Price').textContent));
            e.querySelector('.ProductPrice.weight').textContent = Math.round(idrToInt(e.querySelector('.ProductPrice.weight').textContent)/1000);
            
            e.querySelector('.ProductTotal').textContent = toIdr(idrToInt(e.querySelector('.ProductTotal').textContent));
            // e.querySelector('.costsWeightSum').textContent = toIdr(idrToInt(e.querySelector('.costsWeightSum').textContent))
            
        })
        document.querySelector('.costsWeightSum').textContent = toIdr(idrToInt(document.querySelector('.costsWeightSum').textContent));
        document.querySelector('.totalShippingPrice').textContent = toIdr(idrToInt(document.querySelector('.totalShippingPrice').textContent));
        document.querySelector('.FinalSum').textContent = toIdr(idrToInt(document.querySelector('.FinalSum').textContent));
        
        
        // console.log(toIdr(idrToInt(document.querySelector('.costsWeightSum').textContent)))
    }

    function toIdr(number){
        let angka = number;

        let formattedAngka = angka.toLocaleString('id-ID');
        let formatted = "Rp. " + formattedAngka;

        return formatted;

    }

    function idrToInt(string) {
        let str = String(string);

        let angka = str.replace(/[^\d]/g, '');

        let parsedAngka = parseInt(angka, 10);

        return parsedAngka;
    }
    
    function DetilProductAdd(type){
        let container = document.querySelector(".maincontent");
        container.style.display = "flex !improtant";
        let div = document.createElement('div');
        div.className = 'ShowSomething';
        div.setAttribute('onclick', 'closeViewProduct()');
        div.innerHTML=`
            <div class="ShowContainer" >
                <div class="HeaderDetails">
                    <div class="TextDetails">
                        <p>${type} Detail</p>
                    </div>
                    <button class="">
                        X
                    </button>
                </div>
                <div class="BodyDetail" onclick="holdPrevent(event)">
                    
                </div>
            </div>

        `
        // container.appendChild(div);
        container.insertBefore(div, container.firstChild);

    }
    function closeViewProduct(){
        let container = document.querySelector(".maincontent");
        let show = document.querySelector('.ShowSomething'); 
        container.removeChild(show);
    }
    function holdPrevent(event){
        event.stopPropagation();


    }



    //////////////////////////
    


</script>
@endsection