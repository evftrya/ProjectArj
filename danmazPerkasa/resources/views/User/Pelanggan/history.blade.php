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
            @if(count($data)==0)
            <div class="bottomsArea">
                <p>No Transaction</p>
            </div>
            @else
                @foreach($data as $d)
                    <div class="theItems {{{$d->Status_Pembayaran}}}" onclick="viewProduct('{{{$d->id}}}')">
                        <p>{{{$d->created_at}}}</p>
                        <p>{{{$d->type_transaction}}}</p>
                        <p class="totalIdr">{{{intval($d->TotalShopping)}}}</p>
                        <p>{{{$d->Shipping}}}</p>
                        <p class="name">{{{$d->Status_Pembayaran}}}</p>
                        <a style="cursor: pointer;" href="/Transaction/{{ $d->id }}" class="theButtons">Detail</a>
                    </div>
                @endforeach
            @endif
        </div>
        @if(count($data)!=0)
            <div class="bottomsArea">
                <p>No More Transaction</p>
            </div>
        @endif
    </div>
</div>
<script>
    function viewProduct(id){
        window.location.href="/Transaction/"+id;
    }
    tydeup();
    function tydeup(){
        let all = document.querySelectorAll(".totalIdr");
        all.forEach(a=>{
            a.textContent = toIdr(a.textContent);
        })
    }

    function toIdr(number) {
        if (typeof number !== "number") {
            number = parseInt(number);
        }

        return "Rp. " + number.toLocaleString("id-ID");
    }

</script>
@endsection