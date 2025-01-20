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
            <div class="forPriceVar">Unit Weight</div>
            <div class="forQtyVar">Quantity</div>
            <div class="forSumVar">Total Price</div>
        </div>
        <div class="Tbody" id="tbody">
            <div class="theProducts">
                @foreach($data as $d)
                <div class="theProduct">
                    <div class="cb"><input type="checkbox" name="check2" onclick="getChecked()"></div>
                    <div class="prodDesc">
                        <div class="ProductPhoto" style="background-image: url('{{asset('storage/images/'.$d->PhotosName)}}');">
                            
                        </div>
                        <div class="ProductDesc">
                            <p>{{{$d->nama_product}}}</p>
                        </div>
                    </div>
                    <div class="ProductPrice" id="ProductPrice">
                        {{{$d->price}}}
                    </div>
                    <div class="ProductPrice weight">
                        <p class="berat">{{{$d->weight}}}</p>
                        <p>Kg</p>
                    </div>
                    <div class="ProductQty">
                        <div class="inside">
                            <div class="mid">
                                <input type="text" value="{{{$d->qty}}}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="ProductTotal">
                        Rp. 3.000.000
                    </div>
                </div>
                @endforeach
            </div>
            <div class="detilTransaksi">
                <div class="line1 nonbottom">
                    <div class="notes">
                        <p>Notes:</p>
                        <input type="text" name="" placeholder="Optional">
                    </div>
                    <div class="Ship">
                        <div style="display: flex;flex-direction:row;gap:5px;align-items:center;">
                            <p>Total Weight</p>
                            <p style="font-size: 10px;">(with packing +1kg/Product)</p>
                        </div>
                        <div style="display: flex; flex-direction:row;gap:5px;">
                            <p class="weigthTotal">130</p>
                            <p>Kg</p>
                        </div>
                    </div>
                    
                </div>
                
                <div class="line1 nonbottom">
                    <div class="notes">
                        <!-- <p>Notes:</p> -->
                        <!-- <input type="text" name="" placeholder="Optional"> -->
                    </div>
                    <div class="Ship">
                        <p>Opsi Pengiriman:</p>
                        <div class="text">
                            <select name="" id="" class="selectShip" onchange="ChangeNominal(this)">
                                <option value="0">Pilih Kurir</option>
                                @foreach($ship as $a)
                                    @foreach($a[0]['costs'] as $b)
                                        @if(!in_array($b['service'],['T15','T25','T60']))
                                            <option value="{{{$a[0]['code']}}}|{{{$b['service']}}}">{{{strtoupper($a[0]['code'])}}} ({{{$b['description']}}})</option>
                                        @endif
                                    @endforeach
                                @endforeach
                            </select>
                            <!-- <p class="tiny" onclick="selectActive('open')">Change</p> -->
                        </div>
                        
                        <p class="costsWeight">Rp. 0</p>
                        
                    </div>
                    
                </div>
                <div class="line1">
                    <div class="notes" style="display:flex; flex-direction:column; align-items:start;">
                        <p>Alamat tujuan (click to edit):</p>
                        <a href="/Profile/Address" style="text-decoration:none;color:green;">
                            <p style="font-size:15px;">60271, Jl. Keputran Panjunan III/No. 84, RT. 012, Kel. Gumah, Kec. Genteng, Kota Jambi, Prov. Jambi, Indonesia</p>
                        </a>
                    </div>
                    <div class="Ship">
                        <p>Estimasi Pengiriman:</p>
                        <div style="display:flex;flex-direction:row;gap:10px;">
                            <p class="daysEstimate">-</p>
                            <p>Hari</p>
                        </div>
                        
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
                            <p class="totalProductPrice">Rp. 4.000.000</p>
                        </div>
                        <div class="subCont">
                            <p>Total Shipping Price</p>
                            <p class="totalShippingPrice">Rp. 0</p>
                        </div>
                        <div class="subCont">
                            <p>Service Fee</p>
                            <p class="SerivceFee">Rp. 1.000</p>
                        </div>
                        <div class="subCont">
                            <p>Handling Fee</p>
                            <p class="HandlingFee">Rp. 1.000</p>
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
            <form action="/OrderDone" method="POST">
                @csrf
                <input type="text" id="toCheckout" style="Display: none">
                <button>Make Order</button>
            </form>
        </div>
    </div>
</div>


<script>


    CountAll();
    tydeUp();

    
    function tydeUp(){
        let elements = document.querySelectorAll('.ProductPrice');
        
        console.log(elements);
        let individu = Array.from(elements).filter(element => !element.classList.contains('weight'));
        console.log(individu);
        individu.forEach(e=>{
            e.textContent = toIdr(e.textContent);
        })
    }
    function CountAll(){
        Count();
        countTotalProductPrice();
        countTotalPayment();
        countWeight();
    }


    function countWeight(){
        // let wg = document.querySelectorAll('.ProductPrice.weight .berat');
        // let wg = document.querySelectorAll('.ProductPrice.weight .berat');
        let product = document.querySelectorAll('.theProduct')
        console.log(product)
        let sum = 0;
        product.forEach(a=>{
            let wg = document.querySelector('.ProductPrice.weight .berat')
            let inp = document.querySelector('.ProductQty .mid input')
            sum+=((parseFloat(wg.textContent)*inp.value)+(inp.value*1));
            console.log(sum);
        })
        console.log('weight : '+sum);
        let weightTotal =document.querySelector('.weigthTotal')
        weightTotal.textContent = sum;
    }
    
    function countTotalProductPrice(){
        let allproduct = document.querySelectorAll('.theProduct .ProductTotal');
        let total = 0;
        console.log(allproduct.length)
        allproduct.forEach(dtotal=>{
            console.log(dtotal.textContent);
            console.log(idrToInt(dtotal.textContent));
            total +=idrToInt(dtotal.textContent);
        })

        let TSP = document.querySelector('.totalProductPrice');
        TSP.textContent = toIdr(total);
        console.log(TSP);
    }

    function countTotalPayment(){
        let TPP =  document.querySelector('.totalProductPrice');
        let TSP = document.querySelector('.totalShippingPrice');
        let SF = document.querySelector('.SerivceFee');
        let HF = document.querySelector('.HandlingFee');

        let total = (    idrToInt(TPP.textContent)
                        +idrToInt(TSP.textContent)
                        +idrToInt(SF.textContent)
                        +idrToInt(HF.textContent)
                    );
        let FS = document.querySelector('.FinalSum');
        FS.textContent = toIdr(total);
        
    }
    function getKurir(kode){
        // console.log(@json($ship))
        // console.log('tipe: '+typeof(@json($ship)))
        let kurir = JSON.parse(@json($shipjs));
        let cost = document.querySelector('.costsWeight')
        let days = document.querySelector('.daysEstimate')
        let sumWeight = document.querySelector('.weigthTotal')
        let shipPrice = document.querySelector('.totalShippingPrice');

        // console.log(kurir);
        kurir.forEach(e=>{
            e.forEach(f=>{
                f.costs.forEach(g=>{
                    if((f.code+"|"+g.service)==kode){
                        console.log(f.code);
                        // console.log(f.costs);
                        console.log(g.service)
                        console.log(g.cost[0].etd)
                        console.log(g.cost[0].value)
                        cost.textContent=toIdr((g.cost[0].value)*parseFloat(sumWeight.textContent))
                        shipPrice.textContent=toIdr((g.cost[0].value)*parseFloat(sumWeight.textContent))
                        days.textContent = g.cost[0].etd;

                    }
                })
            })
        })
    }
    console.log(inGramRounUp(534.2));
    function inGramRounUp(weight){
        gram = weight*1000;
        return Math.ceil(gram / 1000) * 1000;
    }

    function ChangeNominal(elemen){
        let weight = document.querySelector('.weightTotal');
        let kode = document.querySelector('.selectShip');
        if(elemen.value!=0){

            getKurir(kode.value);
            countTotalPayment();

        }
        else{
            showPopup('Please select a shipping method first.',0)
        }

    }
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
    function idrToInt(string) {
        let str = String(string);

        let angka = str.replace(/[^\d]/g, '');

        let parsedAngka = parseInt(angka, 10);

        return parsedAngka;
}

    function getChecked(){
        // let theqtys = document.getElementById('qtys')
        // let TotalChecked = document.getElementById('totalChecked')
        let theFinalSum = document.getElementById('FinalSum')
        let theCBs = document.querySelectorAll('.theProduct');
        let checked = 0;
        let qtys = 0;
        let prices = 0;
        theCBs.forEach(e=>{
            let cb = e.querySelector('.cb input[type="checkbox"]')
            // console.log(cb);
            if(cb.checked==true){
                checked+=1;
                let qty = e.querySelector('.mid input')
                let priceAll =e.querySelector('.ProductPrice')
                let price = Array.from(priceAll).filter(priceAll => !priceAll.classList.contains('weight'));

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
        // theqtys.textContent = qtys;
        // TotalChecked.textContent = checked;
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
    

    function selectActive(wht){
        let select = document.querySelector('.selectShip');
        console.log(select);

    }
    
</script>
@endsection