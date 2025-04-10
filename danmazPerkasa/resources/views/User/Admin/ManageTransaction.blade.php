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
    </div>
    <div class="theMainList" style="margin-top:30px;">
        <div class="TheList">
            @foreach($data as $d)
                <div class="theItems" onclick="viewProduct('{{{$d->id}}}')">
                    <p class="name">{{{$d->namaUser}}}</p>
                    <p>{{{$d->created_at}}}</p>
                    <p>{{{$d->TotalShopping}}}</p>
                    <p>{{{$d->Shipping}}}</p>
                    <a href="" class="theButtons">Detail</a>
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
    function viewProduct(id){
        DetilProductAdd()
        fetch('{{$TemplateRoute}}'+id)
            .then(response => response.text())
            .then(html => { 
                let show = document.querySelector('.BodyDetail');
                show.innerHTML = html;
        })
        .catch(err => {
            console.error('Gagal memuat konten:', err);
        });
    }
    function DetilProductAdd(){
        let container = document.querySelector(".maincontent");
        container.style.display = "flex !improtant";
        let div = document.createElement('div');
        div.className = 'ShowSomething';
        div.setAttribute('onclick', 'closeViewProduct()');
        div.innerHTML=`
            <div class="ShowContainer" >
                <div class="HeaderDetails">
                    <div class="TextDetails">
                        <p>Product Detail</p>
                    </div>
                    <button>
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
        event.preventDefault();

    }
</script>
@endsection