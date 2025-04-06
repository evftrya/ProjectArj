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
                    <button class="active">Month</button>
                    <button>Year</button>
                </div>
            </div>
            <div class="footer">
                <h1>Rp25.000.000</h1>
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
                    <button class="active">Month</button>
                    <button>Year</button>
                </div>
            </div>
            <div class="footer">
                <h1>1.697.855</h1>
                <h2>Total Order</h2>
            </div>
        </div>
        <!-- product -->
        <div class="box product">
            <div class="iconButton">
                <img src="/assets/product.png" alt="product">
            </div>
            <h3>1.378 Item<br>Total Product Sales</h1>
        </div>
        <!-- customer -->
        <div class="box customer">
            <div class="iconButton">
                <img src="/assets/other.png" alt="other">
            </div>
            <h3>1.708 Item<br>Total Customer Sales</h1>
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
    </script>

@endsection