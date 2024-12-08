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
                <div class="ProductQty">
                    <div class="inside">
                        <button class="ActQty minus" onclick="changeQty('min',this,'{{{$d->id_product}}}')">
                            <svg width="8" height="3" viewBox="0 0 8 3" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.43408 0.235352V2.5791H0.976562V0.235352H7.43408Z" fill="black"/>
                            </svg>
                        </button>
                        <div class="mid">
                            <input type="text" value="{{{$d->qty}}}">
                        </div>
                        <button class="ActQty plus" onclick="changeQty('plus',this,'{{{$d->id_product}}}')">
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
                    <form action="">
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
                        <p> product</p>
                        <p>) :</p>
                    </div>
                </div>
                <p class="FinalSum" id="FinalSum">Rp. 0</p>
            </div>
            <form action="/Checkout" method="POST" class="formCheckout">
                
                @csrf
                <div class="inps" style="display: none;">
                    @foreach($data as $d)
                        <input type="checkbox" name="{{{$d->id_Detail_transaction}}}" value="{{{$d->id_Detail_transaction}}}">
                    @endforeach
                </div>
                <input type="text" id="toCheckout" style="Display: none">
                <button onclick="window.location.href='/Checkout';">Checkout</button>
            </form>
        </div>
    </div>
</div>


<script>
    Count();
    allInputQty();

    function changeQty(wht, elemen,idProduct){
        let number = (elemen.closest('.theProduct')).querySelector('.ProductQty .mid input');
        // console.log(number.value)
        let temp = parseInt(number.value);
        
        if(wht!='min'){
            temp+=1;
            number.value=temp;
        }
        else{
            if(temp!=0){
                temp-=1;
                number.value=temp;
            }
        }

        fetch(('/UpdateCart/'+idProduct),{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                qty: temp,
            })
        }).then(response=>response.json()).then(data => {
        console.log('Success:', data.message);
        })

        Count();

    }

    function checkedAll(wht, elemen){
        let theCBs = document.querySelectorAll('.CartContainer .cb input[type="checkbox"]');
        let len = theCBs.length;
        let sumCheck = document.getElementById('totalChecked');
        let totalProduct = document.getElementById('qtys');
        let inpForm = document.querySelector('.formCheckout').querySelectorAll('.inps input');
        theCBs.forEach(e=> {
            if(wht=="check"){
                e.checked = true;
                elemen.setAttribute("onclick", "checkedAll('uncheck', this)");
                // sumCheck.textContent = len - 2;
                inpForm.forEach(i=>{
                    // console.log('i: '+i.getAttribute('name'));
                    // console.log('bc: '+cb.value);
                    if(i.getAttribute('name')==e.value){
                        // console.log('masuk');
                        i.checked=true;
                        let idproduct = e.closest('.cb').querySelector('.pdi').textContent; 
                        updateStatus(idproduct,e.checked);
                    }
                })

            }
            else{
                inpForm.forEach(i=>{
                    i.checked=false;
                })
                e.checked = false;
                let idproduct = e.closest('.cb').querySelector('.pdi').textContent; 
                updateStatus(idproduct,e.checked);
                elemen.setAttribute("onclick", "checkedAll('check', this)");
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
        let theqtys = document.getElementById('qtys')
        let TotalChecked = document.getElementById('totalChecked')
        let theFinalSum = document.getElementById('FinalSum')
        let theCBs = document.querySelectorAll('.theProduct');
        let checked = 0;
        let qtys = 0;
        let prices = 0;
        let inpForm = document.querySelector('.formCheckout').querySelectorAll('.inps input');

        theCBs.forEach(e=>{
            let cb = e.querySelector('.cb input[type="checkbox"]')
            if(cb.checked==true){

                checked+=1;
                let qty = e.querySelector('.mid input')
                let price =e.querySelector('.ProductPrice')
                qtys+=parseInt(qty.value);
                // console.log(price.textContent)
                // console.log(idrToInt(price.textContent))
                prices+=(parseInt(qty.value)*idrToInt(price.textContent));
                inpForm.forEach(i=>{
                    // console.log('i: '+i.getAttribute('name'));
                    // console.log('bc: '+cb.value);
                    if(i.getAttribute('name')==cb.value){
                        // console.log('masuk');
                        i.checked=true;
                    }
                })
                let idProduct = ((cb.closest('.cb')).querySelector('.Pdi')).textContent;
                // console.log(idProduct);
                updateStatus(idProduct,(cb.checked));
            }
            else{
                let idProduct = ((cb.closest('.cb')).querySelector('.Pdi')).textContent;
                console.log(idProduct);
                
                updateStatus(idProduct,(cb.checked));
            }
        });
        if((qtys/2)==theCBs.length){
            let theCBBs = document.querySelectorAll('.CartContainer input[type="checkbox"]');
            // console.log(theCBBs[(theCBBs.length)-1].checked);
            theCBBs[0].checked = true;
            theCBBs[theCBBs.length-1].checked = true;
        }
        theqtys.textContent = qtys;
        TotalChecked.textContent = checked;
        // console.log(toIdr(prices))
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
            console.log('Success:', data.message);
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
            // console.log(totalProduct.textContent)
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