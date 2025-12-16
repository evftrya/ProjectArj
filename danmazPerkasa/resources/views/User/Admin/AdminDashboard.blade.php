@extends('layouts.BasicPage1')

@section('css')
<!-- <link rel="stylesheet" type="" href="{{asset('css/dashboardAdmin.css')}}"> -->
<!-- <link rel="stylesheet" href="{{ secure_asset('css/dashboardAdmin.css') }}"> -->
<link rel="stylesheet" href="{{ app()->environment('local')? asset('css/dashboardAdmin.css') : secure_asset('css/dashboardAdmin.css') }}">


@endsection

@section('content')
    <div class="frame">
        <div class="containerDashboard">
            <!-- earning -->
            <div class="box earning">
                <div class="header">
                    <div class="iconButton">
                        <img src="/assets/earning.png" alt="earning">
                    </div>
                    <div class="action-button dashboard">
                        <button onclick="earning('day')" class="active btn-pointer">Day</button>
                        <button onclick="earning('month')" class="btn-pointer">Month</button>
                        <button onclick="earning('year')" class="btn-pointer">Years</button>
                    </div>
                </div>
                <div class="footer">
                    <h1 class="day show">{{{$Data[0][0][0]->laba==null?0:$Data[0][0][0]->laba}}}</h1>
                    <h1 class="month">{{{$Data[0][1][0]->laba==null?0:$Data[0][1][0]->laba}}}</h1>
                    <h1 class="year">{{{$Data[0][2][0]->laba==null?0:$Data[0][2][0]->laba}}}</h1>
                    <h2>Total Earning</h2>
                </div>
            </div>
            <!-- order -->
            <div class="box order">
                <div class="header">
                    <div class="iconButton">
                        <img src="/assets/order.png" alt="order">
                    </div>
                    <div class="action-button dashboard">
                        <button onclick="order('day')" class="active btn-pointer">Day</button>
                        <button onclick="order('month')" class="btn-pointer">Month</button>
                        <button onclick="order('year')" class="btn-pointer">Years</button>
                    </div>
                </div>
                <div class="footer">
                    <h1 class="day show">{{{intval($Data[1][0][0])}}}</h1>
                    <h1 class="month">{{{$Data[1][1][0]}}}</h1>
                    <h1 class="year">{{{$Data[1][2][0]}}}</h1>
                    <h2>Total Order</h2>
                </div>
            </div>
            <!-- product -->
            <div class="box product">
                <div class="iconButton">
                    <img src="/assets/product.png" alt="product">
                </div>
                <h3>{{{$Data[2][0][0]}}} Item ({{{$Data[2][1][0]}}} Products, {{{$Data[2][2][0]}}} Customs, {{{$Data[2][3][0]}}} Parts)<br>Total Product Sales</h1>
            </div>
            <!-- customer -->
            <div class="box customer">
                <div class="iconButton">
                    <img src="/assets/other.png" alt="other">
                </div>
                <h3>{{{$Data[3]}}} Transactions<br>Total Customer Sales</h1>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.action-button').forEach(actionButton => {
            actionButton.querySelectorAll('button').forEach(button => {
                button.addEventListener('click', function () {
                    actionButton.querySelectorAll('button').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    this.classList.add('active');
                });
            });
        });

        
        function toIdr(number){
            let angka = number;
            
            let formattedAngka = angka.toLocaleString('id-ID');
            
            return formattedAngka;    
        }
        TideUp();
        function TideUp(){
            let tide = document.querySelectorAll('.footer>h1')
            console.log(tide);
            tide.forEach(e=>{
                e.innerText = (toIdr(parseInt(e.innerText)));
            })
        } 

        function earning(wht){
            let elemen = document.querySelectorAll('.box.earning .footer>*');
            elemen.forEach(e=>{
                e.classList.remove('show')
                e.classList.contains(wht)?e.classList.add('show'):null;
            })
        }

        function order(wht){
            let elemen = document.querySelectorAll('.box.order .footer>*');
            elemen.forEach(e=>{
                e.classList.remove('show')
                e.classList.contains(wht)?e.classList.add('show'):null;
            })
        }
    </script>

@endsection