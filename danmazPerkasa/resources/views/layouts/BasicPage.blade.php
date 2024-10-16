<html>
    <title>Landing Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="" href="{{asset('css/LandingPageMin.css')}}">
    @yield('css')

    <body>

        <div class="MainArea">
            <div class="topBar">
                <div class="namaCv">
                    <p>CV. Danmaz Perkasa</p>
                </div>
                <div class="topSearch">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" id="search">
                            <g fill="none" fill-rule="evenodd" stroke="#200E32" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="1" transform="translate(2 2)">
                                <circle cx="9.767" cy="9.767" r="8.989"></circle>
                                <line x1="16.018" x2="19.542" y1="16.485" y2="20"></line>
                            </g>
                        </svg>
                        <input type="text" id="SearchBox">
                    </div>

                </div>
                <div class="AccountArea">
                    <div class="NonLogin">
                        <a href="/Register">
                            <p>Register</p>
                        </a>
                        <a href="/Login">
                            <p>Login</p>
                        </a>
                    </div>
                    <div class="WithLogin">
                        <a href="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="black" class="bi bi-person" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                            </svg>
                            <p>Arjun Prasetio</p>
                        </a>
                    </div>
                    
                    <button class="burger" onclick="LeftBar('Open')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" viewBox="0 0 24 24" fill="none">
                            <path d="M4 18L20 18" stroke="#000000" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M4 12L20 12" stroke="#000000" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M4 6L20 6" stroke="#000000" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>

            </div>
            <div class="ContainerFill">
                @yield('content')
            </div>
            <!-- <div class="footer">
                <p>Arjun</p>
            </div> -->
        </div>
        <div class="DarkArea" id="DarkArea"></div>
        <div class="LeftBar" id="LeftBar">
            <div class="TopLeftBar">
                <div class="namaBrand">
                    <p>CV. Danmaz Perkasa</p>
                </div>
                <button id="toLeftBar" onclick="LeftBar('Close')">
                    <svg enable-background="new 0 0 26 26" id="Слой_1" version="1.1" viewBox="0 0 26 26"
                        xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <path
                            d="M14.0605469,13L24.7802734,2.2802734c0.2929688-0.2929688,0.2929688-0.7675781,0-1.0605469  s-0.7675781-0.2929688-1.0605469,0L13,11.9394531L2.2802734,1.2197266c-0.2929688-0.2929688-0.7675781-0.2929688-1.0605469,0  s-0.2929688,0.7675781,0,1.0605469L11.9394531,13L1.2197266,23.7197266c-0.2929688,0.2929688-0.2929688,0.7675781,0,1.0605469  C1.3662109,24.9267578,1.5576172,25,1.75,25s0.3837891-0.0732422,0.5302734-0.2197266L13,14.0605469l10.7197266,10.7197266  C23.8662109,24.9267578,24.0576172,25,24.25,25s0.3837891-0.0732422,0.5302734-0.2197266  c0.2929688-0.2929688,0.2929688-0.7675781,0-1.0605469L14.0605469,13z"
                            fill="#1D1D1B"></path>
                    </svg>
                </button>
            </div>
            <div class="MenuArea">
                <div class="TheMenu">
                    <a href=""><p>Home</p></a>
                    <a href=""><p>Cart</p></a>
                    <div class="ProductBar">
                        <div class="productBarArea">
                            <a href=""><p>Product</p></a>
                            <button id="svgOpenCategory" onclick="Category('Open')">
                                <svg  xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/>
                                </svg>
                            </button>
                            <button id="svgCloseCategory" onclick="Category('Close')">
                                <svg  xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
                                </svg>
                            </button>
                        </div>
                        <div class="ProductCategory" id="ProductCategory">
                            <a href=""><p>Electric Basses</p></a>
                            <a href=""><p>Electric Guitars</p></a>
                        </div>
                    </div>
                
                <a href=""><p>Custom</p></a>
                <a href=""><p>History</p></a>
                <a href=""><p>Logout</p></a>
                
                </div>
            </div>
            

        </div>



        <!-- <img src="foto.png" alt=""> -->
    </body>

    <style>
        
    </style>

    <script>

        document.getElementById("SearchBox").addEventListener("keydown", function(event) {
            // Mencegah perilaku default (agar kita bisa mengontrol input sendiri)
            event.preventDefault();
            
            // Ambil karakter yang sesuai dengan tombol yang ditekan
            var keyPressed = event.key;
            
            // Tambahkan karakter tersebut ke dalam nilai input
            this.value += keyPressed;
        });
        function Category(a){
            let displayCategory = document.getElementById('ProductCategory');
            let buttonDisplayCategoryon = document.getElementById('svgCloseCategory');
            let buttonDisplayCategoryoff = document.getElementById('svgOpenCategory');
            if(a=="Open"){
                displayCategory.style.display="flex";
                buttonDisplayCategoryon.style.display="flex";
                buttonDisplayCategoryoff.style.display="none";
            }
            else{
                displayCategory.style.display="none";
                buttonDisplayCategoryon.style.display="none";
                buttonDisplayCategoryoff.style.display="flex";
            }
        }

        function LeftBar(a){
            let close = document.getElementById('LeftBar');
            let darkArea = document.getElementById('DarkArea');

            if(a=="Close"){
                close.style.display="none";
                darkArea.style.display="none";
            }
            else{
                close.style.display="flex";
                darkArea.style.display="flex";


            }
        }
    </script>

</html>