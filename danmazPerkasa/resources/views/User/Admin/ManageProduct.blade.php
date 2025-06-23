@php
$cont = app(\App\Http\Controllers\Controller::class);
$url = $cont->GetUrl();
@endphp

@extends('layouts.BasicPage1')

<!-- @section('css')
<link rel="stylesheet" type="" href="{{asset('css/ManageProduct.css')}}"> -->
<!-- <link rel="stylesheet" href="{{ secure_asset('css/ManageProduct.css') }}"> -->
<!-- <link rel="stylesheet" href="{{ app()->environment('local')? asset('css/ManageProudct.css') : secure_asset('css/ManageProduct.css') }}"> -->
<link rel="stylesheet" href="{{ app()->environment('local')? asset('css/ManageProduct.css') : secure_asset('css/ManageProduct.css') }}">


@endsection

@section('content')
<!-- <div class="ShowSomething" onclick="closeViewProduct()">
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
    
</div> -->
<div class="LandingPage">
    <div class="titled">
        Manage Product
    </div>
    <div class="bottonsArea">
        <button onclick="TurnFormAdd()">Add Product</button>
    </div>
    <div class="theMainList">
        <div class="TheList">
            @foreach($data as $d)
                    <div class="theItems" onclick="viewProduct('{{{$d->id_product}}}')">
                        <p class="name">{{{$d->nama_product}}}</p>
                        <p>{{{$d->Category}}}</p>
                        <p>{{{$d->stok}}} Items</p>
                        <div class="theButtons">
                            @php
                            $st = 'off';
                            $st2 = 'on';
                            if(!is_null($d->isContent)){
                                if($d->isContent == 1){
                                    $st = 'on';
                                    $st2 = 'off';
                                }
                            }
                            @endphp
                            <button class="IsContent {{{$st}}}" onclick="turnContent('{{{$st2}}}',this,'{{{$d->id_product}}}',event)">Turn Content</button>
                            <button class="justButton" onclick="TurnEdit('{{{$d->id_product}}}',event)">Edit</button>
                            <button onclick="DeleteProduct('{{{$d->id_product}}}',event)">Delete</button>
                        </div>
                    </div>
                @endforeach

            

        </div>
        
        <div class="bottomsArea">
            <p>No More Product</p>
        </div>
    </div>
</div>
<script>
    document.querySelector(".searchInp").setAttribute("onclick", "holdSearch(event)");
    function holdSearch(event){
        event.preventDefault()
    }
    document.querySelector(".searchInp").addEventListener("input", searchItems);

    function searchItems(){
        let text = document.querySelector('.searchInp');
        let allItems = document.querySelectorAll('.theItems');
        // console.log(allItems);
        allItems.forEach(item=>{
            let alltext = item.textContent.trim().toLowerCase();
            console.log(alltext.includes(text.value.toLowerCase()));
            if(alltext.includes(text.value.toLowerCase())){
                item.style.display = "flex";
            }
            else{
                item.style.display = "none";
            }
        })
    }
    function viewProduct(idProduct){
        DetilProductAdd()
        fetch('{{$TemplateRoute}}'+idProduct)
            .then(response => response.text())
            .then(html => { 
                let show = document.querySelector('.BodyDetail');
                show.innerHTML = html;
        })
        .catch(err => {
            // console.error('Gagal memuat konten:', err);
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
        event.stopPropagation();


    }
    function DeleteProduct($idProduct,event){
        event.stopPropagation();
        window.location.href='/deleteProduct/'+$idProduct;
    }
    function turnContent(wht,elemen,id,event){
        event.stopPropagation();
        let content= document.querySelectorAll('.theButtons .IsContent.on');
        if(wht=='on'){
            if(content.length<=6){
                elemen.className="IsContent on"
                elemen.setAttribute('onclick','turnContent("off",this,'+id+')');
                turnOnContent(id)
            }
            else{

                showPopup('Content has reached the limit of 7. Please deactivate one to add a new one!',0)
            }
        }
        else{
            elemen.setAttribute('onclick','turnContent("on",this,'+id+')');
            elemen.className="IsContent off";
            turnOffContent(id)
        }
    }

    async function turnOffContent(id){
            let respon = await fetch('/OffContent/'+id);
            if(respon.ok){
                let data = await respon.json();
                // console.log("message: "+data.message)
            }
            else{
                // console.log('no')
            }
    }

    async function turnOnContent(id){
            let respon = await fetch('/OnContent/'+id);
            if(respon.ok){
                let data = await respon.json();
                // console.log("message: "+data.message)
            }
            else{
                // console.log('no')
            }
    }

    function TurnFormAdd(){
        resetForm();
        FormAdd();
        ClosePopUp('open');
    }

    function resetForm(){
        let div = document.querySelector('.NewProduct .containerd');
        let form = div.querySelector('form');
        let button = div.querySelector('.TheButtons')
        if(form){
            div.removeChild(form);
            div.removeChild(button);

        }
    }

    async function TurnEdit(idProduct,event){
        event.stopPropagation();
        let response = await fetch('/getDataProduct/'+idProduct);
            

        let data = await response.json();
        // console.log(data)
        let product = data[0]
        let photos = data[1]
        resetForm();

        FormEdit(product, photos);
        ClosePopUp('open');
    }

    
    function FormAdd(){
        let container = document.querySelector(".NewProduct .containerd");
        container.style.display = "flex !improtant";
        let form = document.createElement('form');
        form.action = "{{ route('add-product', ['wht' => 'Product' ]) }}";
        form.method = "POST";
        form.className = "formEditAdd";
        form.enctype = "multipart/form-data";
        changeTitle('Add Product');
        form.innerHTML=`
            @csrf
            <div class="PhotosAdds">
                <div  class="move lefted" onclick="scrollButtonPhotos('left')">
                    <svg width="14" height="38" viewBox="0 0 14 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.928329 20.131L12.2485 36.7247C12.4374 37.0017 12.6883 37.1563 12.9493 37.1563C13.2102 37.1563 13.4612 37.0017 13.65 36.7247L13.6622 36.706C13.7541 36.5717 13.8273 36.41 13.8773 36.2308C13.9273 36.0517 13.9531 35.8587 13.9531 35.6638C13.9531 35.4688 13.9273 35.2758 13.8773 35.0967C13.8273 34.9175 13.7541 34.7559 13.6622 34.6216L3.00223 18.9966L13.6622 3.37783C13.7541 3.24354 13.8273 3.08189 13.8773 2.90272C13.9273 2.72356 13.9531 2.53062 13.9531 2.33564C13.9531 2.14066 13.9273 1.94772 13.8773 1.76856C13.8273 1.58939 13.7541 1.42774 13.6622 1.29345L13.65 1.2747C13.4612 0.997686 13.2102 0.843151 12.9493 0.843151C12.6883 0.843151 12.4374 0.997686 12.2485 1.2747L0.928329 17.8685C0.828773 18.0144 0.749515 18.1899 0.69536 18.3843C0.641206 18.5788 0.613281 18.7882 0.613281 18.9997C0.613281 19.2113 0.641206 19.4206 0.69536 19.6151C0.749515 19.8095 0.828773 19.985 0.928329 20.131Z" fill="black"/>
                    </svg>
                </div>
                <div class="move righted" onclick="scrollButtonPhotos('right')">
                    <svg width="14" height="38" viewBox="0 0 14 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.0717 17.869L1.75152 1.2753C1.56263 0.998283 1.31169 0.84375 1.05073 0.84375C0.789778 0.84375 0.538836 0.998283 0.349954 1.2753L0.337765 1.29405C0.245881 1.42834 0.172716 1.58998 0.12272 1.76915C0.0727231 1.94832 0.0469408 2.14126 0.0469408 2.33624C0.0469408 2.53121 0.0727231 2.72416 0.12272 2.90332C0.172716 3.08249 0.245881 3.24413 0.337765 3.37842L10.9978 19.0034L0.337765 34.6222C0.245881 34.7565 0.172716 34.9181 0.12272 35.0973C0.0727231 35.2764 0.0469408 35.4694 0.0469408 35.6644C0.0469408 35.8593 0.0727231 36.0523 0.12272 36.2314C0.172716 36.4106 0.245881 36.5723 0.337765 36.7065L0.349954 36.7253C0.538836 37.0023 0.789778 37.1568 1.05073 37.1568C1.31169 37.1568 1.56263 37.0023 1.75152 36.7253L13.0717 20.1315C13.1712 19.9856 13.2505 19.8101 13.3046 19.6157C13.3588 19.4212 13.3867 19.2118 13.3867 19.0003C13.3867 18.7887 13.3588 18.5794 13.3046 18.3849C13.2505 18.1905 13.1712 18.015 13.0717 17.869Z" fill="black"/>
                    </svg>
                </div>
                <div class="buletan">
                    <svg class="bulat first" width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="7.5" cy="7.5" r="7.5" fill="#B17457"/>
                    </svg>
                </div>
                <div class="PhotoAreaContainer">
                    <input type="text" name="mainPhoto" value="foto1" style="display: none;" required>
                    <div class="imageContainer nofill Main">
                        <button class="forMainPhoto" onclick="makeItMain(this, event)">Main Photo</button>    
                        <div class="theImage" onclick="TurnInput(this)">

                        </div>
                        <button class="forInputPhoto" onclick="fillInput(this, event)">
                            <svg width="66" height="66" viewBox="0 0 66 66" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M65.448 35.432H35.496V65.64H29.992V35.432H0.168V30.568H29.992V0.359993H35.496V30.568H65.448V35.432Z" fill="black"/>
                            </svg>
                        </button>
                        <input type="file" name="foto1" id="" required>
                    </div>                                
                </div>
            </div>
            <div class="productCategory">
                <p>Product Category</p>
                <select name="product" id="">
                    <option value="Guitar">Guitar</option>
                    <option value="Bass">Bass</option>
                </select>
            </div>
            <div class="input-container">
                <input required type="text" name="ProductName" maxlength="50" placeholder="" id="inputField">
                <label for="inputField">Product Name</label>
            </div>
            <div class="input-container">
                <input required type="text" name="shortQuotes" placeholder="" maxlength="50" id="inputField">
                <label for="inputField">Short Quotes About This Product</label>
            </div>
            <div class="input-container">
                <input required type="text" name="ProductColor" maxlength="20" placeholder="" id="inputField">
                <label for="inputField">Product Color</label>
            </div>
            <div class="input-container">
                <input required type="number" name="weight" placeholder="" id="inputField">
                <label for="inputField">Product Weight (gram)</label>
            </div>
            <div class="input-container">
                <input required type="number" name="ProductPrice" placeholder="" id="inputField">
                <label for="inputField">Product Price To Customer</label>
            </div>
            <div class="input-container">
                <input required type="number" name="originalPrice" placeholder="" id="inputField">
                <label for="inputField">original price of the product</label>
            </div>
            <div class="forQty">
                <p>Quantity</p>
                <div class="ProductQty">
                    <div class="inside">
                        <button class="ActQty minus" onclick="changeQty('min',this,event)">
                            <svg width="8" height="3" viewBox="0 0 8 3" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.43408 0.235352V2.5791H0.976562V0.235352H7.43408Z" fill="black"/>
                            </svg>
                        </button>
                        <div class="mid">
                            <input required type="number" name="stock" value="1">
                        </div>
                        <button class="ActQty plus" onclick="changeQty('plus',this,event)">
                            <svg width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.9883 5.25879V7.90771H0.805664V5.25879H12.9883ZM8.3252 0.27832V13.2178H5.48096V0.27832H8.3252Z" fill="black"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="input-container desc">
                <textarea  rows="4" cols="60" name="Description" id=""></textarea>
                <label for="inputField">Description</label>
            </div>
            <div class="input-container desc end">
                <textarea  rows="4" cols="60" name="Features" id="" ></textarea>
                <label for="inputField">Features</label>
            </div>
        `;


        let theButton = document.createElement('div');
        theButton.className = 'TheButtons';
        theButton.setAttribute('onclick', 'formClick(this)');
        theButton.innerHTML = `
            <button class="Save" >Save Changes</button>
        `;
        container.appendChild(form);
        container.appendChild(theButton);
        scrollPhotos();

    }

    async function FormEdit(product, photos){
        // console.log(product[0].originalPrice);
            changeTitle('Edit Product');
        
            let container = document.querySelector(".NewProduct .containerd");
            let form = document.createElement('form');
            form.action = "/editProduct/"+product[0].id_product;
            form.method = "POST";
            form.className = "formEditAdd";
            form.enctype = "multipart/form-data";
        
                let html = ""
                html+=`
                @csrf
                    <input required type="text" name="FotoAwal" placeholder="" id="inputField" value="${photos.length}" style="display:none;">

                    <div class="PhotosAdds">
                        <div  class="move lefted" onclick="scrollButtonPhotos('left')">
                            <svg width="14" height="38" viewBox="0 0 14 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.928329 20.131L12.2485 36.7247C12.4374 37.0017 12.6883 37.1563 12.9493 37.1563C13.2102 37.1563 13.4612 37.0017 13.65 36.7247L13.6622 36.706C13.7541 36.5717 13.8273 36.41 13.8773 36.2308C13.9273 36.0517 13.9531 35.8587 13.9531 35.6638C13.9531 35.4688 13.9273 35.2758 13.8773 35.0967C13.8273 34.9175 13.7541 34.7559 13.6622 34.6216L3.00223 18.9966L13.6622 3.37783C13.7541 3.24354 13.8273 3.08189 13.8773 2.90272C13.9273 2.72356 13.9531 2.53062 13.9531 2.33564C13.9531 2.14066 13.9273 1.94772 13.8773 1.76856C13.8273 1.58939 13.7541 1.42774 13.6622 1.29345L13.65 1.2747C13.4612 0.997686 13.2102 0.843151 12.9493 0.843151C12.6883 0.843151 12.4374 0.997686 12.2485 1.2747L0.928329 17.8685C0.828773 18.0144 0.749515 18.1899 0.69536 18.3843C0.641206 18.5788 0.613281 18.7882 0.613281 18.9997C0.613281 19.2113 0.641206 19.4206 0.69536 19.6151C0.749515 19.8095 0.828773 19.985 0.928329 20.131Z" fill="black"/>
                            </svg>
                        </div>
                        <div class="move righted" onclick="scrollButtonPhotos('right')">
                            <svg width="14" height="38" viewBox="0 0 14 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.0717 17.869L1.75152 1.2753C1.56263 0.998283 1.31169 0.84375 1.05073 0.84375C0.789778 0.84375 0.538836 0.998283 0.349954 1.2753L0.337765 1.29405C0.245881 1.42834 0.172716 1.58998 0.12272 1.76915C0.0727231 1.94832 0.0469408 2.14126 0.0469408 2.33624C0.0469408 2.53121 0.0727231 2.72416 0.12272 2.90332C0.172716 3.08249 0.245881 3.24413 0.337765 3.37842L10.9978 19.0034L0.337765 34.6222C0.245881 34.7565 0.172716 34.9181 0.12272 35.0973C0.0727231 35.2764 0.0469408 35.4694 0.0469408 35.6644C0.0469408 35.8593 0.0727231 36.0523 0.12272 36.2314C0.172716 36.4106 0.245881 36.5723 0.337765 36.7065L0.349954 36.7253C0.538836 37.0023 0.789778 37.1568 1.05073 37.1568C1.31169 37.1568 1.56263 37.0023 1.75152 36.7253L13.0717 20.1315C13.1712 19.9856 13.2505 19.8101 13.3046 19.6157C13.3588 19.4212 13.3867 19.2118 13.3867 19.0003C13.3867 18.7887 13.3588 18.5794 13.3046 18.3849C13.2505 18.1905 13.1712 18.015 13.0717 17.869Z" fill="black"/>
                            </svg>
                        </div>
                    `
            
                        let buletan = ""
                        buletan+=`<div class="buletan">`
                        for(let i =0;i<=photos.length;i++){
                            buletan+=`
                                <svg class="bulat first" width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="7.5" cy="7.5" r="7.5" fill="#B17457"/>
                                </svg>
                            `
                        }
                        buletan+=`</div>`
                    
                    html+=buletan
                
                    html+=`
                        <div class="PhotoAreaContainer">
                            <input required type="text" name="mainPhoto" value="foto0" style="display: none;">
                    `
                let thephoto = "";
                for(let i =0;i<=photos.length;i++){
                    let end = "withfill";
                    if(i==photos.length){
                        end = "nofill";
                    }

                    

                    if(i!=photos.length){

                        let main = "notMain"
                        // console.log("--------------")
                        // console.log(photos[i].isMain)
                        // console.log(photos[i].isMain==1);
                        // console.log("--------------")
                        if(photos[i].isMain==1){
                            main = "Main";
                        }

                        thephoto+=`
                            <div class="imageContainer withfill ${main}">
                                <button class="forMainPhoto" onclick="makeItMain(this, event)">Main Photo</button>    
                                <div class="theImage" onclick="TurnInput(this)" style="background-image: url('{{asset('storage/images')}}/${photos[i].PhotosName}'); display: flex;">

                                </div>
                                <button class="forInputPhoto" onclick="fillInput(this, event)">
                                    <svg width="66" height="66" viewBox="0 0 66 66" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M65.448 35.432H35.496V65.64H29.992V35.432H0.168V30.568H29.992V0.359993H35.496V30.568H65.448V35.432Z" fill="black"/>
                                    </svg>
                                </button>
                                <input type="file" name="foto${(i+1)}" id="" required>
                            </div>

                        `
                    }
                    else{
                        thephoto+=`
                            <div class="imageContainer nofill notMain">
                                <button class="forMainPhoto" onclick="makeItMain(this, event)">Set as Main</button>    
                                <div class="theImage">

                                </div>
                                <button class="forInputPhoto" onclick="fillInput(this, event)">
                                    <svg width="66" height="66" viewBox="0 0 66 66" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M65.448 35.432H35.496V65.64H29.992V35.432H0.168V30.568H29.992V0.359993H35.496V30.568H65.448V35.432Z" fill="black"></path>
                                    </svg>
                                </button>
                                
                                <input type="file" name="foto${(i+1)}" id="" required>
                            </div>
                        `
                    }
                    
                }
                // console.log(thephoto);
                html+=thephoto;
                html+=`
                
                    </div>
                        </div>
                        <div class="productCategory">
                            <p>Product Category</p>
                            <select name="product" id="">
                                <option value="Guitar">Guitar</option>
                                <option value="Bass">Bass</option>
                            </select>
                        </div>
                        <div class="input-container">
                            <input required type="text" name="ProductName" placeholder="" maxlength="50" id="inputField" value="${product[0].nama_product}">
                            <label for="inputField">Product Name</label>
                        </div>

                        <div class="input-container">
                            <input required type="text" name="shortQuotes" placeholder="" maxlength="50" id="inputField" value="${product[0].shortQuotes}">
                            <label for="inputField">Short Quotes About This Product</label>
                        </div>

                        <div class="input-container">
                            <input required type="text" name="ProductColor" maxlength="20" placeholder="" id="inputField" value="${product[0].nama_product}">
                            <label for="inputField">Product Color</label>
                        </div>
                        <div class="input-container">
                            <input required type="number" name="weight" placeholder="" id="inputField" value="${product[0].weight}">
                            <label for="inputField">Product Weight (gram)</label>
                        </div>
                        <div class="input-container">
                            <input required type="number" name="ProductPrice" placeholder="" id="inputField" value="${product[0].price}">
                            <label for="inputField">Product Price</label>
                        </div>
                        <div class="input-container">
                            <input required type="number" name="originalPrice" placeholder="" id="inputField" value="${product[0].originalPrice}">
                            <label for="inputField">original price of the product</label>
                        </div>
                        <div class="forQty">
                            <p>Quantity</p>
                            <div class="ProductQty">
                                <div class="inside">
                                    <button class="ActQty minus" onclick="changeQty('min',this,event)">
                                        <svg width="8" height="3" viewBox="0 0 8 3" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M7.43408 0.235352V2.5791H0.976562V0.235352H7.43408Z" fill="black"/>
                                        </svg>
                                    </button>
                                    <div class="mid">
                                        <input required type="number" name="stock" value="${product[0].stok}">
                                    </div>
                                    <button class="ActQty plus" onclick="changeQty('plus',this,event)">
                                        <svg width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.9883 5.25879V7.90771H0.805664V5.25879H12.9883ZM8.3252 0.27832V13.2178H5.48096V0.27832H8.3252Z" fill="black"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="input-container desc">
                            <textarea  rows="4" cols="60" name="Description" id="" >${product[0].detail_product}</textarea>
                            <label for="inputField">Description</label>
                        </div>
                        <div class="input-container desc end">
                            <textarea  rows="4" cols="60" name="Features" id="" >${product[0].Features}</textarea>
                            <label for="inputField">Features</label>
                        </div>
                `
                // console.log(html);
            form.innerHTML = html;

            let theButton = document.createElement('div');
                theButton.className = 'TheButtons';
                theButton.setAttribute('onclick', 'formClick(this)');
                theButton.innerHTML = `
                    <button class="Save">Save Changes</button>
                `;
            container.appendChild(form);
            container.appendChild(theButton);
        scrollPhotos();
        
    
    // console.log("photo1 : "+photos[0].id_Photo)
    }

    function scrollPhotos() {
        let photosCont = document.querySelector('.PhotoAreaContainer');
        // console.log(photosCont);
        
        let debounceTimeout;

        photosCont.addEventListener('scroll', function() {
            clearTimeout(debounceTimeout);

            debounceTimeout = setTimeout(function() {
                let closestPhoto = null;
                let closestDistance = Infinity;
                let closestIndex = -1;
                let photos = photosCont.querySelectorAll('.imageContainer');
                // console.log("panjang photos: "+photos.length)
                photos.forEach((p, index) => {
                    let photoRect = p.getBoundingClientRect();
                    let contRect = photosCont.getBoundingClientRect();
                    // console.log('photorect: '+photoRect.width);
                    // console.log('contrect: '+contRect.left);

                    let distanceToCenter = Math.abs(photoRect.left + photoRect.width / 2 - (contRect.left + contRect.width / 2));

                    if (distanceToCenter < closestDistance) {
                        closestDistance = distanceToCenter;
                        closestPhoto = p;
                        closestIndex = index;
                    }
                });

                if (closestPhoto) {
                    let ScrollAmt = closestPhoto.offsetLeft - (photosCont.offsetWidth / 2) + (closestPhoto.offsetWidth / 2);
                    // console.log("closestPhoto.offsetLeft : "+closestPhoto.offsetLeft)
                    // console.log("photosCont.offsetWidth : "+photosCont.offsetWidth)
                    // console.log("closestPhoto.offsetWidth : "+closestPhoto.offsetWidth)
                    // console.log("Scrolamt : "+ScrollAmt);
                    photosCont.scrollLeft = ScrollAmt;

                    // console.log("Indx: " + closestIndex);
                    moveTobulat(closestIndex);
                    
                }
            }, 100);
        });
    }

    function TurnInput(elemen){
        let div = elemen.closest('.imageContainer')
        let inp = div.querySelector('input');
        let img = div.querySelector('.theImage');
        inp.click();

        inp.addEventListener('change', function() {
            let file = URL.createObjectURL(inp.files[0]);
            // console.log(file);
            if(file){
                img.style.backgroundImage = `url(${file})`; 
            }
            
        });
    }

    function formClick(elemen) {
        let form = ((elemen.closest('.containerd')).querySelector('form'))
        let inp = null;
        if (document.querySelector('.TotalPhoto') == null) {
            inp = document.createElement("input");
            inp.setAttribute("name", "TotalPhoto");
            inp.className = 'TotalPhoto';
            inp.type = "text";
            inp.style.display = 'none';
            inp.value = (form.querySelectorAll('.PhotosAdds .imageContainer.withfill')).length;
            form.appendChild(inp);
        } else {
            inp = document.querySelector('.TotalPhoto');
        }

        // Mendapatkan semua elemen dengan atribut name di dalam elemen .NewProduct
        let elementsWithName = Array.from(document.querySelectorAll('.NewProduct [name]'));

        // Filter untuk memastikan name tidak kosong
        let elementsWithNonEmptyName = elementsWithName.filter(el => el.getAttribute('name').trim() !== "");
        let needFill = 0;
        let wht = null;
        console.log(elementsWithNonEmptyName);
        elementsWithNonEmptyName.forEach(y => {
            if (y.value == "") {
                //console.log(y.name.includes('foto'))
                if (y.name.includes('foto')) {
                    console.log(y.name.replace('foto', ''))
                    if (y.name.replace('foto', '') == 1) {
                        if (document.querySelector('.titlePopUp').textContent != 'Edit Product') {
                            needFill += 1;
                            console.log("masuk if: " + y)

                            wht = y;
                        }
                        //console.log(y)
                        //console.log('needfill tambah: '+needFill)
                    }
                } else {
                    console.log("masuk else: " + y)
                    needFill += 1;
                    wht = y;
                    //console.log(y)
                    //console.log('needfill tambah: '+needFill)
                }
            }
        })
        // Menampilkan elemen yang memenuhi kriteria
        //console.log('needfill: '+needFill)
        if (needFill == 0 || needFill == '0') {
            form.submit();
            // //console.log('masuk if')
        } else {
            //console.log('masuk else')
            showPopup('There are still empty fields', 0)
        }


    }

    
    // document.getElementById("myInput").addEventListener("input", a);
    // searchItems();
    // function searchItems(){
    //     let text = document.querySelector('.searchInp');
    //     let allItems = document.querySelectorAll('.theMainList theItems');
    //     // console.log(allItems);
    // }

    

    

</script>
@endsection