@extends('layouts.BasicPage1')

@section('css')
<link rel="stylesheet" type="" href="{{asset('css/Checkout.css')}}">
@endsection

@section('content')
<div class="CartContainer">
    
    <div class="Tables">
        <div class="THead" id="thead">
            <!-- <div class="forCBVar"><input type="checkbox" id="inCo" onclick="checkedAll('check', this)"></div> -->
            <div class="forProdVar">Product</div>
            <div class="forPriceVar">Unit Price</div>
            <div class="forQtyVar">Quantity</div>
            <div class="forSumVar">Total Price</div>
        </div>
        <div class="Tbody" id="tbody">
            <div class="theProducts">
                <div class="theProduct">
                    <!-- <div class="cb"><input type="checkbox" name="check2" onclick="getChecked()"></div> -->
                    <div class="prodDesc">
                        <div class="ProductPhoto" style="background-image: url('https://i.pinimg.com/736x/89/27/79/892779104c3f686796ce798403a97a44.jpg');">
                            
                        </div>
                        <div class="ProductDesc">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas, mollitia sint reprehenderit, provident at fugit esse debitis nobis eum quibusdam aliquam, cumque dolore.</p>
                        </div>
                    </div>
                    <div class="ProductPrice" id="ProductPrice">
                        Rp. 1.700.000
                    </div>
                    <div class="ProductQty">
                        <div class="inside">
                            <button class="ActQty minus">
                                <svg width="8" height="3" viewBox="0 0 8 3" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.43408 0.235352V2.5791H0.976562V0.235352H7.43408Z" fill="black"/>
                                </svg>
                            </button>
                            <div class="mid">
                                <input type="text" value="1" disabled>
                            </div>
                            <button class="ActQty plus">
                                <svg width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12.9883 5.25879V7.90771H0.805664V5.25879H12.9883ZM8.3252 0.27832V13.2178H5.48096V0.27832H8.3252Z" fill="black"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="ProductTotal">
                        Rp. 3.000.000
                    </div>
                </div>
            </div>
            <div class="detilTransaksi">
                <div class="line1">
                    <div class="notes">
                        <p>Notes:</p>
                        <input type="text" name="" placeholder="Optional">
                    </div>
                    <div class="Ship">
                        <p>Opsi Pengiriman:</p>
                        <div class="text">
                            <p>Banter Express</p>
                            <p class="tiny">Change</p>
                        </div>
                        
                        <p>Rp. 100.000</p>
                    </div>
                </div>
                <div class="PayMed">
                    <div>
                    <p>Payment Method</p>
                        <div class="ListPayMed">
                            <button><p>Bank Transfer</p></button>
                            <button><p>Pay Cash at Partner</p></button>
                            <button><p>Pay Cash at Partner</p></button>
                            <button><p>Credit Card</p></button>
                        </div>
                    </div>
                </div>
                <div class="bank">
                    <div>
                        <p>Choose Bank</p>
                        <select name="" id="">
                            <option value="1">Bank BCA</option>
                            <option value="1">Bank BRI</option>
                            <option value="1">Bank BNI</option>
                            <option value="1">4</option>
                        </select>
                    </div>
                    
                </div>
                <div class="theDetil">
                    <div class="cont">
                        <div class="subCont">
                            <p>Total Product Price</p>
                            <p>Rp. 4.000.000</p>
                        </div>
                        <div class="subCont">
                            <p>Total Shipping Price</p>
                            <p>Rp. 100.000</p>
                        </div>
                        <div class="subCont">
                            <p>Service Fee</p>
                            <p>Rp. 1.000</p>
                        </div>
                        <div class="subCont">
                            <p>Handling Fee</p>
                            <p>Rp. 1.000</p>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </div>
    <div class="toCheckout">
        <div class="sidE">
            <div class="text roboto" style="gap: 5px;">
                <p>Total Payment: </p>
                <p class="FinalSum" id="FinalSum">Rp. 0</p>
            </div>
            <form action="">
                <input type="text" id="toCheckout" style="Display: none">
                <button>Make Order</button>
            </form>
        </div>
    </div>
</div>


<script>
    Count();
    

    function changeQty(wht, elemen){
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
        Count();

    }

    function checkedAll(wht, elemen){
        let theCBs = document.querySelectorAll('.CartContainer input[type="checkbox"]');
        let len = theCBs.length;
        let sumCheck = document.getElementById('totalChecked');
        let totalProduct = document.getElementById('qtys');
        theCBs.forEach(e=> {
            if(wht=="check"){
                e.checked = true;
                elemen.setAttribute("onclick", "checkedAll('uncheck', this)");
                // sumCheck.textContent = len - 2;

            }
            else{
                e.checked = false;
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