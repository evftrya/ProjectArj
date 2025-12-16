@extends('layouts.BasicPage1')

@section('css')
<!-- <link rel="stylesheet" type="" href="{{asset('css/ManageProduct.css')}}"> -->
<!-- <link rel="stylesheet" href="{{ secure_asset('css/ManageProduct.css') }}"> -->
<!-- <link rel="stylesheet" href="{{ app()->environment('local')? asset('css/ManageProduct.css') : secure_asset('css/ManageProduct.css') }}"> -->
<link rel="stylesheet" href="{{ app()->environment('local')? asset('css/ManageProduct.css') : secure_asset('css/ManageProduct.css') }}">


@endsection

@section('content')
<div class="LandingPage">
    <div class="titled">
        Part
    </div>
    <div class="bottonsArea">
        <button class="" onclick="TurnFormAdd()">Add Part</button>
    </div>
    <div class="theMainList part">
        @foreach($data[1] as $t)
        <div class="category1"  onclick="HideArea(this, 'hide')">
            <p>▾ {{{$t->Area}}}</p>
            @foreach($data[2] as $q)
                @if($q->Area==$t->Area)
                <div class="category2 {{{$t->Area}}}" onclick="HideCategory(this, 'hide', event)">
                    <p>▾ {{{$q->CAtegory." (".$q->Types.")"}}}</p>
                    <div class="TheList" onclick="Demand(event)">
                        @foreach($data[0] as $d)
                           @if($d->category_description==$t->Area && $d->category_name==$q->CAtegory && $d->category_types==$q->Types)
                                <div class="theItems" onclick="viewProduct('{{{$d->id_product}}}')">
                                    <p>{{{$d->nama_product}}}</p>
                                    <p>{{{$d->category_name}}}</p>
                                    <p>{{{$d->category_types}}}</p>
                                    <p>{{{$d->price}}}</p>
                                    <p>{{{$d->stok}}} Items</p>
                                    <div class="theButtons">
                                        <button class=""></button>
                                        <button class="" onclick="TurnEdit('{{{$d->id_product}}}',event)">Edit</button>
                                        <!-- <button onclick="TurnDelete('{{{$d->id_product}}}')">Delete</button> -->
                                        <button class="" onclick="DeletePart('{{{$d->id_product}}}',event)">Delete</button>

                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
            @endforeach 
        </div>
        @endforeach
        
        <div class="bottomsArea">
            <p>No More Part</p>
        </div>
    </div>
</div>
<script>

    function viewProduct(idProduct){
        DetilProductAdd()
        fetch('{{$TemplateRoute}}'+idProduct)
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
    @if(session('pesan'))
        let pesan = "{{ session('pesan') }}";
        showPopup(pesan, 1);

        function showPopup(wht,which) {
            const popup = document.getElementById('popup');
            //console.log(popup);
            if(which==0){
                popup.style.backgroundColor="#b32323";
            }
            else{
                popup.style.backgroundColor="#4caf50";
            }
            popup.textContent = wht
            popup.classList.add('show');

            // Hilangkan pop-up setelah 3 detik
            setTimeout(() => {
                popup.classList.remove('show');
            }, 1500);
        }
    @endif

    function Demand(event){
        event.stopPropagation();
    }
    function HideArea(elemen, wht){
        // console.log(elemen);
        let divs = elemen.querySelectorAll(':scope > div')
        // console.log(divs)
        let p = elemen.querySelector('p')

        divs.forEach(k=>{
            if(wht=='hide'){
                k.style.display = 'none'
                elemen.setAttribute('onclick', "HideArea(this, 'open')");
                p.textContent = p.textContent.replace("▾","▸")

            }
            else{
                k.style.display = 'flex'
                elemen.setAttribute('onclick', "HideArea(this, 'hide')");
                p.textContent = p.textContent.replace("▸","▾")

            }
        })
    }

    function HideCategory(elemen, wht, event){
        event.stopPropagation();
        // console.log(elemen);
        let divs = elemen.querySelectorAll(':scope > div')
        // console.log(divs)
        let p = elemen.querySelector('p')

        divs.forEach(k=>{
            if(wht=='hide'){
                k.style.display = 'none'
                elemen.setAttribute('onclick', "HideCategory(this, 'open', event)");
                p.textContent = p.textContent.replace("▾","▸")
            }
            else{
                k.style.display = 'flex'
                elemen.setAttribute('onclick', "HideCategory(this, 'hide', event)");
                p.textContent = p.textContent.replace("▸","▾")
            }
        })
        // let category1 = elemen.closest('category1')
        // category1.setAttribute(category1.getAttribute('onclick'))
    }
    async function TurnEdit(idProduct,event){
        event.stopPropagation();

        let response = await fetch('/getDataPart/'+idProduct);
            

        let data = await response.json();
        // console.log(data)
        // let photos = data[1]
        resetForm();
        // console.log(data);

        FormEdit(data, null,data);


        // // console.log(response);
        ClosePopUp('open');
    }

    function DeletePart(idProduct,event){
        event.stopPropagation();
        window.location.href='/deletePart/'+idProduct;
    }

    async function FormEdit(product, photos, data){
        // console.log(product[0].id_product)
            changeTitle('Edit Part');

            let category = @json($Category);
            console.log('category :>> ', category);

            
            let container = document.querySelector(".NewProduct .containerd");
            let form = document.createElement('form');
            form.action = "/editPart/"+product[0].id_product;
            form.method = "POST";
            form.className = "formEditAdd";
            form.enctype = "multipart/form-data";
        
            form.innerHTML=`
            @csrf
                        <div class="PhotosAdds">
                            <div class="PhotoAreaContainer">
                                <div class="imageContainer nofill Main">
                                    <div class="theImage" data-base-url="{{ asset('storage/images') }}" onclick="TurnInput(this)">

                                    </div>
                                    <button class="forInputPhoto " onclick="fillInput(this, event)">
                                        <svg width="66" height="66" viewBox="0 0 66 66" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M65.448 35.432H35.496V65.64H29.992V35.432H0.168V30.568H29.992V0.359993H35.496V30.568H65.448V35.432Z" fill="black"/>
                                        </svg>
                                    </button>
                                    <input type="file" name="foto1" id="" required>
                                </div>                                
                            </div>
                        </div>`
                        let div = document.createElement('div')
                        div.className="productCategory"
                        let p = document.createElement('p');
                        p.textContent="Part Category";
                        div.appendChild(p);

                            let select = document.createElement('select')
                            select.name = 'product'
                            for(let i=0;i<category.length;i++){
                                if(i==0){
                                    let option = document.createElement('option')
                                    option.value = "0"
                                    option.textContent = "Select Category"
                                    select.appendChild(option)
                                }

                                let y = category[i]
                                let option = document.createElement('option')
                                option.value = `${y.id_category_part}`
                                option.textContent = `${y.Area} - ${y.Category} (${y.Types})`
                                select.appendChild(option)
                            }
                            div.appendChild(select);
                            form.appendChild(div)

                        form.innerHTML+=`</div>
                        <div class="input-container">
                        <!-- <p>Email</p> -->
                            <input type="text" name="ProductName" placeholder="" id="inputField">
                            <label for="inputField">Part Name ("," for same category with different color)</label>
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
                                    <button class="ActQty minus " onclick="changeQty('min',this,event)">
                                        <svg width="8" height="3" viewBox="0 0 8 3" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M7.43408 0.235352V2.5791H0.976562V0.235352H7.43408Z" fill="black"/>
                                        </svg>
                                    </button>
                                    <div class="mid">
                                        <input type="number" name="stock" value="1">
                                    </div>
                                    <button class="ActQty plus " onclick="changeQty('plus',this,event)">
                                        <svg width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.9883 5.25879V7.90771H0.805664V5.25879H12.9883ZM8.3252 0.27832V13.2178H5.48096V0.27832H8.3252Z" fill="black"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="input-container desc end part">
                            <textarea  rows="4" cols="60" name="Description" id="" style="padding-top: 40px !important;"></textarea>
                            <label for="inputField">Description</label>
                        </div>
        `;

            let theButton = document.createElement('div');
                theButton.className = 'TheButtons';
                theButton.setAttribute('onclick', 'formClick(this)');
                // theButton.setAttribute('onclick', 'formClick(this)');
                theButton.innerHTML = `
                    <button class="">Save Changes</button>
                `;
            container.appendChild(form);
            container.appendChild(theButton);
        // console.log(form);
                            
        fillForm(data, form);


    // console.log("photo1 : "+photos[0].id_Photo)
    }

    function fillForm(data, form){
        let go = data[0];
        console.log(go)

        // let inpPhoto = form.querySelector('.imageContainer>input');
        // let Ctgr = document.querySelector('')
        let inps = form.querySelectorAll('input');
        // console.log(inps);
        // console.log(inps[0]);

        (go.nama_product!=null)? inps[2].value = go.nama_product : null;
        (go.color!=null || go.color!="-")? inps[3].value = go.color : null;
        (go.weight!=null)? inps[4].value = go.weight : null;
        (go.price!=null)? inps[5].value = go.price : null;
        (go.stok!=null)? inps[6].value = go.stok : null;
        let desc = form.querySelector('textarea');
        let types = form.querySelector('select');
        (go.Category!=null)? types.value = go.Category : null;
        
        // console.log(desc);
        (go.detail_product!=null)? desc.value = go.detail_product : null;
        (go.PhotosName!=null)? setPhoto() : null ;
        
        
        function setPhoto(){
            let photo = form.querySelector('.theImage');
            let baseURL = photo.getAttribute("data-base-url");
            photo.style.backgroundImage = `url(${baseURL}/${go.PhotosName})`;
            photo.style.display="flex";

            let cont = photo.closest('.imageContainer');
            cont.classList.replace('nofill', 'withfill');
        }
        

        
        
        
        // Name.value = go.nama_product;
        
        
        
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
        let category = @json($Category);
        console.log('category :>> ', category[0]);
        // console.log(category);
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
                                    <button class="forInputPhoto " onclick="fillInput(this, event)">
                                        <svg width="66" height="66" viewBox="0 0 66 66" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M65.448 35.432H35.496V65.64H29.992V35.432H0.168V30.568H29.992V0.359993H35.496V30.568H65.448V35.432Z" fill="black"/>
                                        </svg>
                                    </button>
                                    <input type="file" name="foto1" id="" required>
                                </div>                                
                            </div>
                        </div>`
                        let div = document.createElement('div')
                        div.className="productCategory"
                        let p = document.createElement('p');
                        p.textContent="Part Category";
                        div.appendChild(p);

                            let select = document.createElement('select')
                            select.name = 'product'
                            for(let i=0;i<category.length;i++){
                                if(i==0){
                                    let option = document.createElement('option')
                                    option.value = "0"
                                    option.textContent = "Select Category"
                                    select.appendChild(option)
                                }

                                let y = category[i]
                                let option = document.createElement('option')
                                option.value = `${y.id_category_part}`
                                option.textContent = `${y.Area} - ${y.Category} (${y.Types})`
                                select.appendChild(option)
                            }
                            div.appendChild(select);
                            form.appendChild(div)

                        form.innerHTML+=`</div>
                        <div class="input-container">
                        <!-- <p>Email</p> -->
                            <input type="text" name="ProductName" placeholder="" id="inputField">
                            <label for="inputField">Part Name ("," for same category with different color)</label>
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
                                    <button class="ActQty minus " onclick="changeQty('min',this,event)">
                                        <svg width="8" height="3" viewBox="0 0 8 3" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M7.43408 0.235352V2.5791H0.976562V0.235352H7.43408Z" fill="black"/>
                                        </svg>
                                    </button>
                                    <div class="mid">
                                        <input type="number" name="stock" value="1">
                                    </div>
                                    <button class="ActQty plus " onclick="changeQty('plus',this,event)">
                                        <svg width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.9883 5.25879V7.90771H0.805664V5.25879H12.9883ZM8.3252 0.27832V13.2178H5.48096V0.27832H8.3252Z" fill="black"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="input-container desc end part">
                            <textarea  rows="4" cols="60" name="Description" id=""></textarea>
                            <label for="inputField">Description</label>
                        </div>
        `;


        let theButton = document.createElement('div');
        theButton.className = 'TheButtons';
        theButton.setAttribute('onclick', 'formClick(this)');
        theButton.innerHTML = `
            <button class="">Save Changes</button>
        `;
        container.appendChild(form);
        container.appendChild(theButton);
        // console.log(form.innerHTML)
        // scrollPhotos();

    }

    document.querySelector(".searchInp").setAttribute("onclick", "holdSearch(event)");
    function holdSearch(event){
        event.preventDefault()
    }

    // document.querySelector(".searchInp").setAttribute("onclick", "holdSearch(event)");

    // function holdSearch(event) {
    //     event.preventDefault();
    // }
    document.querySelector(".searchInp").addEventListener("input", searchItems);
    
    function searchItems(){
        let text = document.querySelector('.searchInp');
        // let allItems = document.querySelectorAll('.theItems');
        // // console.log(allItems);
        // allItems.forEach(item=>{
            // let alltext = item.textContent.trim().toLowerCase();
        //     console.log(alltext.includes(text.value.toLowerCase()));
        //     if(alltext.includes(text.value.toLowerCase())){
        //         item.style.display = "flex";
        //     }
        //     else{
        //         item.style.display = "none";
        //     }
        // })
        let all = document.querySelectorAll('.category1');
        all.forEach(e=>{
            count1 = 0;
            document.querySelectorAll('.category2').forEach(f=>{
                count2 = 0
                // console.log(f)
                f.querySelectorAll('.theItems').forEach(item=>{
                    let alltext = item.textContent.trim().toLowerCase();
                    console.log(alltext.includes(text.value.toLowerCase()));
                    if(alltext.includes(text.value.toLowerCase())){
                        count1++
                        count2++
                        item.style.display = "flex";
                    }
                    else{
                        item.style.display = "none";
                    }
                })
                console.log(count1,count2)
                if(count2 > 0){
                    f.style.display = "flex"
                }
                else{
                    f.style.display = "none"
                }
            })

            if(count1 > 0){
                e.style.display = "flex"
            }
            else{
                e.style.display = "none"
            }
        })
        
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
        // console.log(elementsWithNonEmptyName);////
        elementsWithNonEmptyName.forEach(y => {
            if (y.value == "") {
                console.log('ini',y.name.includes('foto'),y, 'ini')

                if (!y.name.includes('foto')) {
                    console.log("masuk else: " + y)
                    needFill += 1;
                    wht = y;
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
    

</script>
@endsection