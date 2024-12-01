@extends('layouts.BasicPage1')

@section('css')
<link rel="stylesheet" type="" href="{{asset('css/productDetil.css')}}">
@endsection

@section('content')
<div class="containerProductDetil">
    <div class="mainArea">
        <div class="PhotosArea">
            <div class="MainPhotos" >
                <div class="fillMainPhotos" id="MainPhoto" style="background-image: url('https://i.pinimg.com/474x/73/5d/68/735d68b0a2300b697ebb8055ee5a9e6b.jpg');">
                </div>
            </div>
            <div class="thePhotosArea">
                <div class="TheScroll">
                    <svg width="14" height="38" viewBox="0 0 14 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.928085 20.131L12.2482 36.7247C12.4371 37.0017 12.6881 37.1562 12.949 37.1562C13.21 37.1562 13.4609 37.0017 13.6498 36.7247L13.662 36.706C13.7539 36.5717 13.827 36.41 13.877 36.2308C13.927 36.0517 13.9528 35.8587 13.9528 35.6638C13.9528 35.4688 13.927 35.2758 13.877 35.0967C13.827 34.9175 13.7539 34.7559 13.662 34.6216L3.00199 18.9966L13.662 3.37783C13.7539 3.24353 13.827 3.08189 13.877 2.90273C13.927 2.72356 13.9528 2.53062 13.9528 2.33564C13.9528 2.14066 13.927 1.94772 13.877 1.76855C13.827 1.58939 13.7539 1.42774 13.662 1.29345L13.6498 1.2747C13.4609 0.997684 13.21 0.843151 12.949 0.843151C12.6881 0.843151 12.4371 0.997684 12.2482 1.2747L0.928085 17.8685C0.828528 18.0144 0.749271 18.1899 0.695116 18.3843C0.640961 18.5788 0.613037 18.7882 0.613037 18.9997C0.613037 19.2113 0.640961 19.4206 0.695116 19.6151C0.749271 19.8095 0.828528 19.985 0.928085 20.131Z" fill="black"/>
                    </svg>
                </div>
                <div class="ThePhotos">
                    <div class="justContainer">
                        <div onclick="changeMainPhoto(this)" class="ThePhoto" style="background-image: url('https://i.pinimg.com/236x/db/c0/c1/dbc0c1f90a8c52398430adccb266a8f7.jpg');">
                        </div>
                        <div onclick="changeMainPhoto(this)" class="ThePhoto" style="background-image: url('https://i.pinimg.com/236x/08/63/b9/0863b9f7a2676d951462504aaeac21a1.jpg');">
                        </div>
                        <div onclick="changeMainPhoto(this)" class="ThePhoto" style="background-image: url('https://i.pinimg.com/474x/b6/65/5b/b6655bcf74c5b4bcb1570eb676595afa.jpg');">
                        </div>
                        <div onclick="changeMainPhoto(this)" class="ThePhoto" style="background-image: url('https://i.pinimg.com/236x/db/c0/c1/dbc0c1f90a8c52398430adccb266a8f7.jpg');">
                        </div>
                        <div onclick="changeMainPhoto(this)" class="ThePhoto" style="background-image: url('https://i.pinimg.com/236x/db/c0/c1/dbc0c1f90a8c52398430adccb266a8f7.jpg');">
                        </div>
                        <div onclick="changeMainPhoto(this)" class="ThePhoto" style="background-image: url('https://i.pinimg.com/236x/db/c0/c1/dbc0c1f90a8c52398430adccb266a8f7.jpg');">
                        </div>
                        <div onclick="changeMainPhoto(this)" class="ThePhoto" style="background-image: url('https://i.pinimg.com/236x/db/c0/c1/dbc0c1f90a8c52398430adccb266a8f7.jpg');">
                        </div>
                    </div>
                </div>
                <div class="TheScroll">
                    <svg width="14" height="38" viewBox="0 0 14 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.0717 17.869L1.75152 1.2753C1.56263 0.998283 1.31169 0.84375 1.05073 0.84375C0.789778 0.84375 0.538836 0.998283 0.349954 1.2753L0.337765 1.29405C0.245881 1.42834 0.172716 1.58998 0.12272 1.76915C0.0727234 1.94832 0.0469408 2.14126 0.0469408 2.33624C0.0469408 2.53121 0.0727234 2.72415 0.12272 2.90332C0.172716 3.08249 0.245881 3.24413 0.337765 3.37842L10.9978 19.0034L0.337765 34.6222C0.245881 34.7565 0.172716 34.9181 0.12272 35.0973C0.0727234 35.2764 0.0469408 35.4694 0.0469408 35.6644C0.0469408 35.8593 0.0727234 36.0523 0.12272 36.2314C0.172716 36.4106 0.245881 36.5723 0.337765 36.7065L0.349954 36.7253C0.538836 37.0023 0.789778 37.1568 1.05073 37.1568C1.31169 37.1568 1.56263 37.0023 1.75152 36.7253L13.0717 20.1315C13.1712 19.9856 13.2505 19.8101 13.3046 19.6157C13.3588 19.4212 13.3867 19.2118 13.3867 19.0003C13.3867 18.7887 13.3588 18.5794 13.3046 18.3849C13.2505 18.1905 13.1712 18.015 13.0717 17.869Z" fill="black"/>
                    </svg>

                </div>
            </div>
            
        </div>
        <div class="ActionArea">
            <p class="ProductName">ThunderBird Basses Sunbrust</p>
            <p class="ProductPrice">Rp. 2.500.000</p>
            <div class="ProductQty">
                <p>Quantity</p>
                <div class="qtynumbers">
                    <button class="start">
                        <svg width="8" height="3" viewBox="0 0 8 3" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.43408 0.235352V2.5791H0.976562V0.235352H7.43408Z" fill="black"/>
                        </svg>
                    </button>
                    <div class="mid">
                        <p>1</p>
                    </div>
                    <button class="end">
                        <svg width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.9883 5.25879V7.90771H0.805664V5.25879H12.9883ZM8.3252 0.27832V13.2178H5.48096V0.27832H8.3252Z" fill="black"/>
                        </svg>
                    </button>
                </div>
                <p class="withpad">5 Unit left</p>
            </div>
            <div class="buttonArea">
                <button class="atc">
                    <p>Add To Cart</p>
                </button>

                <button class="co">
                    <p>Checkout</p>
                </button>
            </div>
        </div>
    </div>
    <div class="descArea">
        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ex eveniet blanditiis eaque aliquam fugit vel cupiditate voluptatibus veniam? Similique voluptates odio itaque sequi perferendis ab a hic aliquam magnam consequatur? Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi non odio architecto voluptates magni odit nobis quibusdam quam beatae exercitationem dolorem fuga nam sunt officia, doloremque eveniet eius illum ipsum. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eveniet quibusdam tempora ipsam, incidunt sequi dolorum voluptatem dolores atque repellendus vel tempore praesentium facilis rem sapiente id illo enim accusamus et. Lorem ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis aperiam laboriosam fugit aspernatur ad placeat, voluptas quia minus neque assumenda dignissimos deserunt nam esse sit, quas, odit asperiores veritatis iste. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dignissimos obcaecati non recusandae quo in dolorum nemo excepturi aperiam itaque qui assumenda voluptates natus aliquam dicta perspiciatis delectus corporis, vitae maiores? Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum dolorem alias labore. Consequuntur explicabo architecto earum cumque sequi accusantium dolorem quasi excepturi deleniti pariatur totam vel, quae vero error laudantium.</p>
        <div class="specs">
        </div>
    </div>
</div>

<script>
    function changeMainPhoto(e){
        let mainphoto = document.getElementById('MainPhoto');
        let change = (e.style.backgroundImage);
        mainphoto.style.backgroundImage = change;
    }
</script>
@endsection