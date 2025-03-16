@extends('layouts.BasicPage1')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" type="" href="{{asset('css/Cart.css')}}">
@endsection

@section('content')
<div class="CartContainer">
    
    <div class="Tables">
        <div class="THead" id="thead">
            <div class="forCBVar"><input type="checkbox" id="inCo" onclick="checkedAll('check', this)"></div>
            <div class="forProdVar">Product</div>
            <div class="forPriceVar">Unit Price</div>
            <div class="forPriceVar">Unit Weight</div>
            <div class="forQtyVar">Quantity</div>
            <div class="forSumVar">Total Price</div>
            <div class="forActVar">Action</div>
        </div>
        <div class="Tbody" id="tbody">
            @foreach($data as $d)
            <div class="theProduct">
                <!-- <input type="text" > -->
                <div class="cb">
                    <input type="checkbox" name="check2" onclick="getChecked()" value="{{{$d->id_Detail_transaction}}}">
                    <p class="Pdi" style="display:none;">{{{$d->id_product}}}</p>
                </div>
                <div class="prodDesc">
                    <div class="ProductPhoto" style="background-image: url('asset('storage/images/'.$d->PhotosName)');">
                        
                    </div>
                    <div class="ProductDesc">
                        <p>{{{$d->nama_product}}}</p>
                    </div>
                    
                </div>
                <div class="ProductPrice" id="ProductPrice">
                    {{{$d->price}}}
                </div>
                <div class="ProductPrice weight" id="ProductPrice">
                    <div class="text">
                        <p class="weightProduct">{{{$d->weight}}}</p>
                    </div>
                    <div class="text">
                        <p>kg</p>
                    </div>
                    
                    
                </div>
                <div class="ProductQty">
                    <div class="inside">
                        <button class="ActQty minus" onclick="changeQty('min',this,'{{{$d->id_product}}}','{{{$d->id_Detail_transaction}}}','{{{$d->stok}}}')">
                            <svg width="8" height="3" viewBox="0 0 8 3" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.43408 0.235352V2.5791H0.976562V0.235352H7.43408Z" fill="black"/>
                            </svg>
                        </button>
                        <div class="mid">
                            <input type="text" onchange="changeQty('inp',this,'{{{$d->id_product}}}','{{{$d->id_Detail_transaction}}}','{{{$d->stok}}}')" value="{{{$d->qty}}}">
                        </div>
                        <button class="ActQty plus" onclick="changeQty('plus',this,'{{{$d->id_product}}}','{{{$d->id_Detail_transaction}}}','{{{$d->stok}}}')">
                            <svg width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.9883 5.25879V7.90771H0.805664V5.25879H12.9883ZM8.3252 0.27832V13.2178H5.48096V0.27832H8.3252Z" fill="black"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="ProductTotal">
                    Rp. 3.000.000
                </div>
                <div class="ProductAct">
                    <form action="/DeleteCart/{{{$d->id_Detail_transaction}}}" method="POST">
                        @csrf
                        <button type="submit">Delete</button>
                    </form>
                </div>
            </div>
            @endforeach
            
            
        </div>
    </div>
    <div class="toCheckout">
        <div class="lefT">
            <div class="forCBVar"><input type="checkbox" id="inThead" onclick="checkedAll('check', this)"></div>
            <div class="text">
                <p>Choose All (</p>
                <p id="totalChecked">0</p>
                <p>)</p>
            </div>
            
        </div>
        
        <div class="sidE">
            <div class="text roboto" style="gap: 5px;">
                <p>Total </p>
                <div class="text" style="gap: 5px">
                    <div class="text">
                        <p>(</p>
                        <p id="qtys">0 </p>
                    </div>
                    
                    <div class="text">
                        <p> product &</p>
                    
                    </div >
                    <div class="text">
                        <p id="weights">0</p>
                        <p> kg</p>
                        <p>) :</p>

                    </div>
                </div>
                <p class="FinalSum" id="FinalSum">Rp. 0</p>
            </div>
            <form action="/Checkout/null/null" method="POST" class="formCheckout">
                
                @csrf
                <div class="inps" style="display: none;">
                    @foreach($data as $d)
                        <input type="checkbox" name="{{{$d->id_Detail_transaction}}}" value="{{{$d->id_Detail_transaction}}}">
                    @endforeach
                </div>
                <input type="text" id="toCheckout" style="Display: none">
                <button onclick="gotoCheckout(event)">Checkout</button>
            </form>
        </div>
    </div>
</div>

<script>
    function initializeLoadingIndicator() {
        //console.log('Initializing loading indicator');

        // Buat elemen loading indicator
        const loadingIndicator = document.createElement('div');
        loadingIndicator.id = 'loading-indicator';
        loadingIndicator.style.display = 'none';
        loadingIndicator.style.position = 'fixed';
        loadingIndicator.style.top = '0';
        loadingIndicator.style.left = '0';
        loadingIndicator.style.width = '100%';
        loadingIndicator.style.height = '100%';
        loadingIndicator.style.background = 'rgba(0, 0, 0, 0.5)';
        loadingIndicator.style.zIndex = '9999';
        loadingIndicator.style.display = 'flex';
        loadingIndicator.style.alignItems = 'center';
        loadingIndicator.style.justifyContent = 'center';
        loadingIndicator.style.flexDirection = 'column';
        loadingIndicator.style.color = 'white';
        loadingIndicator.style.fontFamily = 'Arial, sans-serif';
        loadingIndicator.style.textAlign = 'center';

        // Tambahkan spinner
        const spinner = document.createElement('div');
        spinner.style.border = '8px solid #f3f3f3';
        spinner.style.borderTop = '8px solid #3498db';
        spinner.style.borderRadius = '50%';
        spinner.style.width = '60px';
        spinner.style.height = '60px';
        spinner.style.animation = 'spin 1s linear infinite';

        // Tambahkan teks
        const text = document.createElement('p');
        text.textContent = 'We are preparing your data';
        text.style.marginTop = '20px';
        text.style.fontSize = '16px';

        // Masukkan spinner dan teks ke dalam loading indicator
        loadingIndicator.appendChild(spinner);
        loadingIndicator.appendChild(text);

        // Tambahkan loading indicator ke dalam body
        document.body.appendChild(loadingIndicator);

        const styleSheet = document.styleSheets[0];
        styleSheet.insertRule(`
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `, styleSheet.cssRules.length);

        // Event untuk menampilkan loading hanya jika bukan navigasi dari cache
        window.addEventListener('pagehide', function () {
            loadingIndicator.style.display = 'flex';
            });

            // Event untuk menyembunyikan loading saat halaman dimuat kembali
            window.addEventListener('pageshow', function (event) {
                if (event.persisted) {
                    // Jika halaman dimuat dari cache, sembunyikan loading
                    loadingIndicator.style.display = 'none';
                }
            });

            // Event untuk navigasi biasa (bukan back/forward)
            window.addEventListener('beforeunload', function () {
                loadingIndicator.style.display = 'flex';
            });
    }




    // Panggil function saat halaman selesai dimuat\
    
</script>


<script>
    Count();
    allInputQty();
    tydeUp();
    

    function tydeUp(){
        let elements = document.querySelectorAll('.ProductPrice');

        let individu = Array.from(elements).filter(element => !element.classList.contains('weight'));

        // //console.log(filteredElements);

        
        individu.forEach(e=>{
            e.textContent = toIdr(e.textContent);
        })
    }
    async function gotoCheckout(event){
        event.preventDefault();
        let totalChecked = document.getElementById("totalChecked");
        //console.log("checkout :"+ !(totalChecked.textContent==0))
        if(!(totalChecked.textContent==0)){
            let adr = await fetch('/isNew');
            let isnew = await adr.json();
            if(isnew==1){
                //console.log('jalannnnn')
                initializeLoadingIndicator();
                window.location.href='/Checkout/null/null';
            }
            else{
                showPopup("Please set the address first (Setting>Account Settings>Address)",0)
            }
        }
        else{
            showPopup("Mohon pilih product untuk dicheckout terlebih dahulu!")
        }
        
    }
    function changeQty(wht, elemen,idproduct,idDT,maxstok){
        let number = (elemen.closest('.theProduct')).querySelector('.ProductQty .mid input');
        // //console.log(number.value)
        let temp = parseInt(number.value);
        //console.log('temp: '+temp);
        //console.log('max: '+parseInt(maxstok));
        //console.log('bool: '+temp<=parseInt(maxstok));
        if(wht=='plus'){
            
            if(temp<parseInt(maxstok)){
                temp+=1;
                number.value=temp;
            }
            else{
                showPopup('Only '+maxstok+' items left in stock!',0)
            }
            
        }
        else if(wht=='min'){
            if(temp!=0){
                temp-=1;
                number.value=temp;
            }
            
        }
        else{
            temp = elemen.value
        }

        fetch(('/UpdateCart/'+idproduct+'/'+idDT),{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                qty: temp,
            })
        }).then(response=>response.json()).then(data => {
        //console.log('Success:', data.message);
        })

        Count();

    }

    function checkedAll(wht, elemen){
        let theCBs = document.querySelectorAll('.CartContainer .cb input[type="checkbox"]');
        let len = theCBs.length;
        let sumCheck = document.getElementById('totalChecked');
        // //console.log("total : "+sumCheck.textContent)
        let cbTop = document.querySelectorAll(".forCBVar input");

        
        let totalProduct = document.getElementById('qtys');weights
        let totalweight = document.getElementById('weights');
        let inpForm = document.querySelector('.formCheckout').querySelectorAll('.inps input');
        // //console.log(theCBs);
        theCBs.forEach(e=> {
            // //console.log(e.value);
            if(wht=="check"){
                e.checked = true;
                // sumCheck.textContent = len - 2;
                //console.log("inp: ",inpForm)
                inpForm.forEach(i=>{
                    // //console.log(i);
                    // //console.log('i: '+i.getAttribute('name'));
                    // //console.log('bc: '+cb.value);
                    //console.log("i checked: "+i)
                    if(i.getAttribute('name')==e.value){
                        // //console.log('masuk');
                        i.checked=true;
                        // //console.log(e.closest('.cb'))
                        // //console.log("nilai p: "+e.closest('.cb').querySelector('p').textContent)
                        let idproduct = e.closest('.cb').querySelector('.Pdi').textContent; 
                        updateStatus(idproduct,e.checked);
                    }
                })
                cbTop.forEach(y=>{
                    y.setAttribute("onclick", "checkedAll('uncheck', this)");
                    y.checked=true;
                })


            }
            else{
                
                inpForm.forEach(i=>{
                    i.checked=false;
                })
                e.checked = false;
                //console.log()
                let idproduct = e.closest('.cb').querySelector('.Pdi').textContent; 
                updateStatus(idproduct,e.checked);
                cbTop.forEach(y=>{
                    y.setAttribute("onclick", "checkedAll('check', this)");
                    y.checked=false;
                })
                // sumCheck.textContent = 0;
            }
        });
        getChecked();
    }
    function toIdr(number){
        let angka = number;
        
        let formattedAngka = angka.toLocaleString('id-ID');
        let formatted = "Rp. " + formattedAngka;
        
        return formatted;
        
    }
    function idrToInt(string){
        let str = string;
        
        let angka = str.replace(/[^\d]/g, '');
        
        let parsedAngka = parseInt(angka, 10);
        
        return parsedAngka;
    }
    
    function getChecked(){
        // //console.log("getchecked aktif")
        let theqtys = document.getElementById('qtys')
        let weightd = document.getElementById('weights')
        let TotalChecked = document.getElementById('totalChecked')
        let theFinalSum = document.getElementById('FinalSum')
        // //console.log("the final sum: "+theFinalSum.textContent);
        let theCBs = document.querySelectorAll('.theProduct');
        let checked = 0;
        let qtys = 0;
        let weigths = 0.0;
        let prices = 0;
        let inpForm = document.querySelector('.formCheckout').querySelectorAll('.inps input');
        theCBs.forEach(e=>{
            let cb = e.querySelector('.cb input[type="checkbox"]')
            if(cb.checked==true){
                // //console.log('awal wg: '+weigths)
                
                checked+=1;
                let qty = e.querySelector('.mid input')
                // //console.log("qty")
                // //console.log("qty val: "+qty.value);
                let price =e.querySelector('.ProductPrice')
                let wg =e.querySelector('.weightProduct')
                //console.log('wg: '+wg);
                //console.log('wg texc: '+wg.textContent);
                //console.log('parse wg: '+parseFloat(wg.textContent))
                weigths+=(parseFloat(wg.textContent.match(/\d+(\.\d+)?/g)).toFixed(2)*parseFloat(qty.value))
                console.log('typeof: '+(parseFloat(weigths)))
                console.log()
                qtys+=parseInt(qty.value);
                // //console.log(price.textContent)
                // //console.log(idrToInt(price.textContent))
                prices+=(parseInt(qty.value)*idrToInt(price.textContent));
                inpForm.forEach(i=>{
                    //console.log('i: '+i.getAttribute('name'));
                    // //console.log('bc: '+cb.value);
                    //console.log(i.getAttribute('name'));
                    if(i.getAttribute('name')==cb.value){
                        // //console.log('masuk');
                        i.checked=true;
                    }
                })
                let idProduct = ((cb.closest('.cb')).querySelector('.Pdi')).textContent;
                // //console.log(idProduct);
                updateStatus(idProduct,(cb.checked));
            }
            else{
                // //console.log((cb.closest('.cb')).querySelector('.Pdi'));
                let idProduct = ((cb.closest('.cb')).querySelector('.Pdi')).textContent;
                // //console.log(idProduct);
                
                updateStatus(idProduct,(cb.checked));
            }
        });
        if(weigths!=0){

            weightd.textContent = (Math.ceil(weigths * 100) / 100)
        }
        else{
            weightd.textContent = 0
            
        }
        //console.log("qtys: "+qtys);
        let theCBBs = document.querySelectorAll('.CartContainer input[type="checkbox"]');
        //console.log("cbbs lenth: "+theCBBs.length);
        if((checked+theCBs.length)!=0){
            //console.log("checked :"+checked)
            //console.log("cbs.length: "+theCBs.length);
            // //console.log("checked :"+checked)
            let bool = (checked==theCBs.length);
            theCBBs[0].checked = bool;
            theCBBs[theCBBs.length-theCBs.length-1].checked = bool;
            if(bool){
                theCBBs[0].setAttribute("onclick", "checkedAll('uncheck', this)");
                theCBBs[theCBBs.length-theCBs.length-1].setAttribute("onclick", "checkedAll('uncheck', this)");
            }
            else{
                theCBBs[0].setAttribute("onclick", "checkedAll('check', this)");
                theCBBs[theCBBs.length-theCBs.length-1].setAttribute("onclick", "checkedAll('check', this)");

            }
        }
        
        theqtys.textContent = qtys;
        // //console.log("weights: "+weights);
        TotalChecked.textContent = checked;
        // //console.log(toIdr(prices))
        theFinalSum.textContent = toIdr(prices);
    }

    function updateStatus(idProduct, wht){
        if(wht==true){
            wht = '1';
        }
        else{
            wht='2';
        }
        fetch(('/UpdateStatus/'+idProduct+'/'+wht),{
                method: 'get',
                headers: {
                    'Content-Type': 'application/json',
                },
            }).then(response=>response.json()).then(data => {
            //console.log('Success:', data.message);
        })
    }
    function onEditInput(elemen){
        elemen.addEventListener('change', function(){
            getChecked();
            let theproduct = elemen.closest('.theProduct');
            let price = idrToInt(theproduct.querySelector('.ProductPrice').textContent);
            let total = toIdr(price*elemen.value);
            let totalProduct = theproduct.querySelector('.ProductTotal');
            totalProduct.textContent = total;
            // //console.log(totalProduct.textContent)
        })
    }
    function allInputQty(){
        let inps = document.querySelectorAll('.mid input');
        inps.forEach(e=>{
            onEditInput(e);
        })
    }

    function Count(){
        let Products = document.querySelectorAll('.theProduct');
        Products.forEach(theproduct=>{
            let price = idrToInt(theproduct.querySelector('.ProductPrice').textContent);
            let inp = theproduct.querySelector('.mid input')
            let total = toIdr(price*inp.value);
            let totalProduct = theproduct.querySelector('.ProductTotal');
            totalProduct.textContent = total;
        })
        getChecked()
    }
    
    
</script>
@endsection