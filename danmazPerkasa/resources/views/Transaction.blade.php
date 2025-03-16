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
                        <p> @if(isset($data->Notes)) $data->Notes @endif </p>
                        <!-- <input type="text" class="notesTemp" onchange="changeNotes(this)" name="" placeholder="Optional"> -->
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
                        <p>Opsi Pengiriman: {{{$data[0]->Shipping}}}</p>
                        
                        <p class="costsWeightSum">{{{$data[0]->TotalShipping}}}</p>
                    </div>
                    
                </div>
                <div class="line1">
                    <div class="notes" style="display:flex; flex-direction:column; align-items:start;">
                        <p>Alamat tujuan:</p>
                        <a href="/Profile/Address" style="text-decoration:none;color:green;">
                            <p style="font-size:12px;">{{{$userData[0]->Detil}}}</p>
                        </a>
                    </div>
                    <div class="Ship">
                        <p>Estimasi Pengiriman:</p>
                        <div style="display:flex;flex-direction:row;gap:10px; ">
                            <p class="daysEstimate"></p>
                            <p>{{{$data[0]->shippingEstimate}}} Hari</p>
                        </div>
                        
                    </div>
                </div>
                <div class="PayMed">
                    <div>
                        <!-- <input type="text"> -->
                    <p>Payment Method</p>
                        <div class="ListPayMed">
                            <p>{{{$data[0]->PaymentMethod}}}</p>
                        </div>
                    </div>
                </div>
                <div class="theDetil transaction">
                    <div class="cont">
                        <div class="subCont">
                            <p>Id Transaction:   {{{$data[0]->id}}}</p>
                        </div>
                        <div class="subCont">
                            <p>Total Product Price</p>
                            <p class="totalProductPrice">Rp. 4.000.000</p>
                        </div>
                        <div class="subCont">
                            <p>Total Shipping Price</p>
                            <p class="totalShippingPrice">{{{$data[0]->TotalShipping}}}</p>
                        </div>
                        <div class="subCont">
                            <p>Service Fee</p>
                            <p class="SerivceFee">Rp. 1.000</p>
                        </div>
                        <div class="subCont">
                            <p>Handling Fee</p>
                            <p class="HandlingFee">Rp. 1.000</p>
                        </div>
                        <div class="subCont">
                            <p>Total Payment</p>
                            <p class="FinalSum">{{{$data[0]->TotalShopping}}}</p>
                        </div>
                        <div class="subCont">
                            <p>Payment Status</p>
                            <p class="PaymentStatus">{{{$data[0]->Status_Pembayaran}}}</p>
                        </div>
                    </div>
                </div>
                
            </div>

            
        </div>
    </div> 
    <div class="toCheckout transaction">
        <div class="warningTeks">
            <div>
                <p class="deadline" style="display:none;">{{{$data[0]->Deadline}}}</p>
                <p>Segera Lakukan pembayaran sebelum</p>
                <p class="time">{{{$data[0]->Deadline}}}</p>
            </div>
            <div class="thewarning">
                <p>- Apabila melebihi batas waktu Transaksi ini akan dianggap gagal</p>
                <p>- Pembatalan hanya bisa dilakukan apabila belum melakukan pembayaran</p>
            </div>
        </div>
        <div class="sidE">
            <form action="" class="theforms" method="POST">
                @csrf
                <button onclick="ToForm(this,event)">Cancel Order</button>
            </form>
        </div>
    </div>
</div>

<script>

    function formattedDateTime(time){
        console.log(time)
        return time.replace(" ", "T").replace("09:08:41", "09:05:39");
    }
    newDeadline();
    function newDeadline(){
        const dateTime = '{{{$data[0]->created_at}}}';

        // Konversi string ke objek Date
        const date = new Date(dateTime.replace(" ", "T")); // Mengganti spasi menjadi 'T' agar dikenali oleh Date

        // Tambahkan 30 menit
        date.setMinutes(date.getMinutes() + 30);

        // Format kembali ke string jika diperlukan (YYYY-MM-DD HH:mm:ss)
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Bulan dimulai dari 0
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');

        const newDateTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        let time = document.querySelector('.warningTeks .time');
        if(time!=null){

            time.textContent = newDateTime;
            return (newDateTime);
        }
        return null;
        // console.log(time.textContent);
        // console.log(newDateTime); // Output: 2025-01-23 09:38:41
        // console.log('new date: '+newDateTime);
        // return formattedDateTime(newDateTime);

    }
    
    // setInterval(RefreshPage, 1000);
    RefreshPage()

    async function RefreshPage(){
        let idTransaction = "{{$data[0]->id}}";
        let timepurchase = newDeadline()
        let now = new Date();
        let deadline = new Date(timepurchase)
        let bottom = document.querySelector('.toCheckout.transaction')
        console.log(bottom)
        // bottom.style.display ='none'
        // let container = document.querySelector('.CartContainer');
        console.log('masuk luar')
        if((now.getTime()>=deadline.getTime())){
            // let PaymentStatus = document.querySelector('.PaymentStatus')
            // if(PaymentStatus!='Done'){
                // container.remove(bottom)
                // }
                if(bottom.style.display!='none'){
                    clearBottom()
                    // container.remove(bottom)
                }
        }
        else{
            // console.log('masuk')
            let response = await fetch('/PaymentStatus/'+'{{$data[0]->id}}');
            let data = await response.json();
            if(data=='Done'){
                clearBottom()
            }
            // console.log(data); 
        }
    }

    function clearBottom(){
        let bottom = document.querySelector('.toCheckout.transaction')
        let divs = bottom.querySelectorAll('div')
            divs.forEach(e=>{
                bottom.remove(e)
            })
        
    }
    

    


    CountAll();
    tydeUp();


    function ToForm(elemen,event){
        event.preventDefault();
        let inps = document.querySelectorAll('form input[required]');
        let cekNull = 0
        inps.forEach(e=>{
            if(e.value==""){
                cekNull+=1;
            }
        })
        if(cekNull>0){
            showPopup('Please complete the data', 0)
        }
        else{
            // console.log('aman')
            let form = elemen.closest('form');
            form.submit();
        }
        
    }
    
    function tydeUp(){
        let elements = document.querySelectorAll('.ProductPrice');
        
        //console.log(elements);
        let individu = Array.from(elements).filter(element => !element.classList.contains('weight'));
        //console.log(individu);
        individu.forEach(e=>{
            e.textContent = toIdr(e.textContent);
        })

        pmStatus();
        repairWeight()
    }

    function repairWeight(){
        let weight = document.querySelectorAll('.ProductPrice.weight')
        weight.forEach(t=>{
            t.textContent = (Math.ceil(parseFloat(t.textContent)/1000 * 100) / 100)+" Kg"
        })

        let sumWeight = document.querySelector('.weigthTotal')
        sumWeight.textContent = (Math.ceil(parseFloat(sumWeight.textContent)/1000 * 100) / 100)+" Kg"

    }

    function pmStatus(){
        let status = document.querySelector('.PaymentStatus')
        if(status.textContent=='Waiting'){
            status.textContent = 'Waiting For Payment'
        }
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
        //console.log(product)
        let sum = 0;
        product.forEach(a=>{
            let wg = a.querySelector('.ProductPrice.weight .berat')
            let inp = a.querySelector('.ProductQty .mid input')
            // console.log()
            sum+=((parseFloat(wg.textContent)*inp.value)+(inp.value*1));
            // console.log(sum);
        })
        //console.log('weight : '+sum);
        let weightTotal =document.querySelector('.weigthTotal')
        weightTotal.textContent = (Math.ceil(sum * 100) / 100)
    }
    
    function countTotalProductPrice(){
        let allproduct = document.querySelectorAll('.theProduct .ProductTotal');
        let total = 0;
        console.log(allproduct.length)
        allproduct.forEach(dtotal=>{
            //console.log(dtotal.textContent);
            //console.log(idrToInt(dtotal.textContent));
            total +=idrToInt(dtotal.textContent);
        })
        console.log(total)
        let TPP = document.querySelector('.totalProductPrice');
        TPP.textContent = toIdr(total);
        //console.log(TSP);

        /////////////////// shipping price ///////////////////
        let  shippingSum = document.querySelector('.totalShippingPrice');
        console.log('shipping : '+shippingSum.textContent);
        let shipping = toIdr(parseInt(shippingSum.textContent))
        shippingSum.textContent = shipping
        document.querySelector('.costsWeightSum').textContent = shipping
    }

    function changePM(text,elemen){
        let all = document.querySelectorAll('.ListPayMed button')
        console.log(all)
        all.forEach(o=>{
            if(o==elemen){
                o.classList.add('select');

            }
            else{
                o.classList.remove('select');
            }
        })
        let inp = document.querySelector('.paymentMethodInp')
        inp.value=text;
        let bank = document.querySelector('.bank')
        let inpbank = document.querySelector('.bankMethod')
        if(text=='Bank Transfer'){
            bank.style.display='flex';
            inpbank.required = true;
        }
        else{
            bank.style.display='none';
            inpbank.required = false;

        }

        
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
    //console.log(inGramRounUp(534.2));
    function inGramRounUp(weight){
        gram = weight*1000;
        return Math.ceil(gram / 1000) * 1000;
    }

    function ChangeNominal(elemen){
        let weight = document.querySelector('.weightTotal');
        let kode = document.querySelector('.selectShip');
        if(elemen.value!=0){
            countTotalPayment();

            let inp = document.querySelector('.shipUse')
            inp.value=elemen.value

        }
        else{
            showPopup('Please select a shipping method first.',0)
        }

    }
    function ChangeBank(elemen){
        let inp = document.querySelector('.bankMethod')
        console.log(elemen.value)
        inp.value = elemen.value
    }
    function changeQty(wht, elemen){
        let number = (elemen.closest('.theProduct')).querySelector('.ProductQty .mid input');
        // //console.log(number.value)
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

    function changeNotes(elemen){
        let inp=document.querySelector('#notesClient')
        inp.value = elemen.value
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
        let theFinalSum = document.querySelector('.FinalSum')
        let theCBs = document.querySelectorAll('.theProduct');
        let checked = 0;
        let qtys = 0;
        let prices = 0;
        theCBs.forEach(e=>{
            let cb = e.querySelector('.cb input[type="checkbox"]')
            // //console.log(cb);
            if(cb.checked==true){
                checked+=1;
                let qty = e.querySelector('.mid input')
                let priceAll =e.querySelector('.ProductPrice')
                let price = Array.from(priceAll).filter(priceAll => !priceAll.classList.contains('weight'));

                qtys+=parseInt(qty.value);
                // //console.log(price.textContent)
                // //console.log(idrToInt(price.textContent))
                prices+=(parseInt(qty.value)*idrToInt(price.textContent));
            }
        });
        if((qtys/2)==theCBs.length){
            let theCBBs = document.querySelectorAll('.CartContainer input[type="checkbox"]');
            // //console.log(theCBBs[(theCBBs.length)-1].checked);
            theCBBs[0].checked = true;
            theCBBs[theCBBs.length-1].checked = true;
        }
        // theqtys.textContent = qtys;
        // TotalChecked.textContent = checked;
        // //console.log(toIdr(prices))
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
    

    function selectActive(wht){
        let select = document.querySelector('.selectShip');
        //console.log(select);

    }
</script>

@endsection