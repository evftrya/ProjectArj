@extends('layouts.BasicPage1')

@section('css')
<link rel="stylesheet" type="" href="{{asset('css/ManageProduct.css')}}">
@endsection

@section('content')

<div class="LandingPage">
    <div class="titled">
        Part
    </div>
    <div class="bottonsArea">
        <button onclick="TurnFormAdd()">Add Part</button>
    </div>
    <div class="theMainList">
        <div class="TheList">
            @foreach($data as $d)
            <div class="theItems">
                <p>{{{$d->nama_product}}}</p>
                <p>{{{$d->Category}}}</p>
                <p>{{{$d->price}}}</p>
                <p>{{{$d->stok}}} Items</p>
                <div class="theButtons">
                    <button></button>
                    <button onclick="TurnEdit('{{{$d->id_product}}}')">Edit</button>
                    <form action="/deletePart/{{{$d->id_product}}}" method="post">
                        @csrf
                        <button>Delete</button>
                    </form>
                </div>
            </div>
            @endforeach
            

        </div>
        
        <div class="bottomsArea">
            <p>No More Part</p>
        </div>
    </div>
</div>
<script>
    async function TurnEdit(idProduct){

        let response = await fetch('/getDataPart/'+idProduct);
            

        let data = await response.json();
        // console.log(data)
        // let photos = data[1]
        resetForm();

        FormEdit(data, null);
        ClosePopUp('open');
    }

    async function FormEdit(product, photos){
        // console.log(product[0].id_product)
            changeTitle('Edit Part');
        
            let container = document.querySelector(".NewProduct .containerd");
            let form = document.createElement('form');
            form.action = "/editPart/"+product[0].id_product;
            form.method = "POST";
            form.className = "formEditAdd";
            form.enctype = "multipart/form-data";
        
                let html = `
                @csrf
                        <div class="PhotosAdds">
                            <div class="PhotoAreaContainer">
                                <div class="imageContainer withfill Main">
                                    <div class="theImage" onclick="TurnInput(this)" style="background-image: url('{{asset('storage/images')}}/${product[0].PhotosName}'); display: flex;">

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
                            <p>Part Category</p>
                            <select name="product" id="">
                                <option value="Body Shape">Body Shape</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                        <div class="input-container">
                            <input type="text" name="ProductName" placeholder="" id="inputField" value="${product[0].nama_product}">
                            <label for="inputField">Part Name</label>
                        </div>
                        <div class="input-container">
                            <input type="text" name="ProductColor" value="${product[0].color}" placeholder="" id="inputField">
                            <label for="inputField">Part Color</label>
                        </div>
                        <div class="input-container">
                            <input type="number" name="weight" placeholder="" value="${product[0].weight}" id="inputField">
                            <label for="inputField">Part Weight (gram)</label>
                        </div>
                        <div class="input-container">
                            <input type="number" name="ProductPrice" value="${product[0].price}" placeholder="" id="inputField">
                            <label for="inputField">Part Price</label>
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
                                        <input type="number" name="stock" value="${product[0].stok}">
                                    </div>
                                    <button class="ActQty plus" onclick="changeQty('plus',this,event)">
                                        <svg width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.9883 5.25879V7.90771H0.805664V5.25879H12.9883ZM8.3252 0.27832V13.2178H5.48096V0.27832H8.3252Z" fill="black"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="input-container desc end">
                            <!-- <input style="display:none;" type="email" name="emailUser" placeholder="" id="inputField"> -->
                            <textarea  rows="4" cols="60" name="Description" id="">${product[0].detail_product}</textarea>
                            <label for="inputField">Description</label>
                        </div>
                
                `
            form.innerHTML = html;

            let theButton = document.createElement('div');
                theButton.className = 'TheButtons';
                theButton.setAttribute('onclick', 'formClick(this)');
                theButton.innerHTML = `
                    <button>Save Changes</button>
                `;
            container.appendChild(form);
            container.appendChild(theButton);
        
    
    // console.log("photo1 : "+photos[0].id_Photo)
    }
    function TurnInput(elemen){
        let div = elemen.closest('.imageContainer')
        let inp = div.querySelector('input');
        let img = div.querySelector('.theImage');
        inp.click();

        inp.addEventListener('change', function() {
            let file = URL.createObjectURL(inp.files[0]);  // Corrected file reference
            if(file){
                img.style.backgroundImage = `url(${file})`; 
            }
            
        });
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
function FormAdd(){
        let container = document.querySelector(".NewProduct .containerd");
        let form = document.createElement('form');
        form.action = "{{ route('add-product', ['wht' => 'Part' ]) }}";
        form.method = "POST";
        form.className = "formEditAdd";
        form.enctype = "multipart/form-data";
        changeTitle('Add Part');
        form.innerHTML=`
            @csrf
                        <div class="PhotosAdds">
                            <div class="PhotoAreaContainer">
                                <div class="imageContainer nofill Main">
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
                            <p>Part Category</p>
                            <select name="product" id="">
                                <option value="Body Shape">Body Shape</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                        <div class="input-container">
                        <!-- <p>Email</p> -->
                            <input type="text" name="ProductName" placeholder="" id="inputField">
                            <label for="inputField">Part Name</label>
                        <!-- <input type="email" name="" id="" placeholder="username@gmail.com"> -->
                        </div>
                        <div class="input-container">
                        <!-- <p>Email</p> -->
                            <input type="text" name="ProductColor" placeholder="" id="inputField">
                            <label for="inputField">Part Color</label>
                        <!-- <input type="email" name="" id="" placeholder="username@gmail.com"> -->
                        </div>
                        <div class="input-container">
                        <!-- <p>Email</p> -->
                            <input type="number" name="weight" placeholder="" id="inputField">
                            <label for="inputField">Part Weight (gram)</label>
                        </div>
                        <div class="input-container">
                        <!-- <p>Email</p> -->
                            <input type="number" name="ProductPrice" placeholder="" id="inputField">
                            <label for="inputField">Part Price</label>
                        <!-- <input type="email" name="" id="" placeholder="username@gmail.com"> -->
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
                                        <input type="number" name="stock" value="1">
                                    </div>
                                    <button class="ActQty plus" onclick="changeQty('plus',this,event)">
                                        <svg width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.9883 5.25879V7.90771H0.805664V5.25879H12.9883ZM8.3252 0.27832V13.2178H5.48096V0.27832H8.3252Z" fill="black"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="input-container desc end">
                        <!-- <p>Email</p> -->
                            <!-- <input style="display:none;" type="email" name="emailUser" placeholder="" id="inputField"> -->
                            <textarea  rows="4" cols="60" name="Description" id=""></textarea>
                            <label for="inputField">Description</label>
                        <!-- <input type="email" name="" id="" placeholder="username@gmail.com"> -->
                        </div>
        `;


        let theButton = document.createElement('div');
        theButton.className = 'TheButtons';
        theButton.setAttribute('onclick', 'formClick(this)');
        theButton.innerHTML = `
            <button>Save Changes</button>
        `;
        container.appendChild(form);
        container.appendChild(theButton);
        // scrollPhotos();

    }
    
</script>
@endsection