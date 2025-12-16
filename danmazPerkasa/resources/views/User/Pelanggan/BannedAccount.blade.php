@extends('layouts.BasicPage1')

@section('css')
    <!-- <link rel="stylesheet" type="" href="{{ asset('css/ManageProduct.css') }}"> -->
    <!-- <link rel="stylesheet" href="{{ secure_asset('css/ManageProduct.css') }}"> -->
    <link rel="stylesheet"
        href="{{ app()->environment('local') ? asset('css/ManageProduct.css') : secure_asset('css/ManageProduct.css') }}">
@endsection

@section('content')
    <div class="LandingPage"
        style="display: flex; align-items:center;justify-content:center; font-size:30px; text-align:center;">
        <h1>Important Notice: </h1>
        <h1 style="color:red">Your Account is Currently Banned</h1>
        <p>We would like to inform you that your account is currently restricted and unable to make transactions or checkout
            items. However, you can still view the products available on our platform. We recommend contacting our customer
            service team for further clarification regarding your account status.
            Thank you for your understanding.</p>
    </div>

    <script>
        async function refreshActive() {
            console.log('masuk');
            let response = await fetch('/cekAktif');
            // let cek2 = await fetch('/set_Active_Status');
            // let getStatus = await fetch('/now_status');
            let data = await response.json();
            // console.log('data :>> ', data,cek2.json(),getStatus.json());
            if (data == 0) {
                window.location.href='/';
            }
        }
        setInterval(refreshActive, 1000);
    </script>
@endsection
