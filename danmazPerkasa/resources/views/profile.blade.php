
@extends('layouts.BasicPage1')
@section('css')
<link rel="stylesheet" type="" href="{{asset('css/profile.css')}}">
@endsection

@section('content')

<div>
    <div class="Text">

        <p>My Profile</p>
        <svg width="100%" height="1" viewBox="0 0 100% 1" fill="none" xmlns="http://www.w3.org/2000/svg">
            <line y1="0.5" x2="100%" y2="0.5" stroke="#B17457"/>
        </svg>
    </div>
    <div class="container">
        <div class="sideBar">
            <div class="ContainerSubMenu" onclick="ChangeText2(this)">
                <svg width="2" height="25" viewBox="0 0 3 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="2" height="27" fill="#B17457"/>
                </svg>
                <p><a href="/Profile/Info">Info</a></p>
            </div>
            <div class="ContainerSubMenu" onclick="ChangeText2(this)">
                <svg width="2" height="25" viewBox="0 0 3 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="2" height="25" fill="#B17457"/>
                </svg>
                <p><a href="/Profile/Change-Password">Change Password</a></p>
            </div>
            <div class="ContainerSubMenu" onclick="ChangeText2(this)">
                <svg width="2" height="25" viewBox="0 0 3 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="2" height="25" fill="#B17457"/>
                </svg>
                <p><a href="/Profile/Address">Address</a></p>
            </div>
            <div class="ContainerSubMenu" onclick="ChangeText2(this)">
                <svg width="2" height="25" viewBox="0 0 3 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="2" height="25" fill="#B17457"/>
                </svg>
                <p><a href="/Logout">Logout</a></p>
            </div>
        </div>
        <div class="container2">
            <div class="Text2">
                <p>{{{$cp}}}</p>
                <svg width="100%" height="1" viewBox="0 0 100% 1" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <line y1="0.5" x2="100%" y2="0.5" stroke="#B17457"/>
                </svg>
            </div>
            @if($wht=="Info")
            <form action="/Profile/{{{$wht}}}-Update" class="formProfile" id="formProfileInfo" method="POST">
                @csrf
                <div class="containerbody horizontal">
                    
                    <div class="BodyFill">
                        <div class="input-container">
                                <input type="text" name="firstName" placeholder="" id="inputField" value="{{{$data->firstName}}}" disabled required>
                                <label for="inputField">First Name*</label>
                        </div>
                    </div>
                    <div class="BodyFill">
                        <div class="input-container">
                            
                                <input type="text" name="lastName" placeholder="" id="inputField" value="{{{$data->lastName}}}" disabled  required>
                                <label for="inputField">Last Name*</label>
                            
                        </div>
                    </div>
                    <div class="BodyFill">
                        <div class="input-container">
                                <input type="email" name="emailUser" placeholder="" id="inputField" value="{{{$data->emailUser}}}" disabled required>
                                <label for="inputField">Email*</label>
                        </div>
                    </div>
                    <div class="BodyFill">
                        <div class="input-container">
                            
                                <input type="text" name="Phone" placeholder="" id="inputField" value="{{{$data->Phone}}}" disabled required>
                                <label for="inputField">Phone*</label>
                            
                        </div>
                    </div>
                    <div class="BodyFill">
                        <div class="input-container" id="PasswordArea">

                                <input type="password" name="passwordUser" id="ThePassword" placeholder="" value="{{{$data->lenPassword}}}" disabled>
                                <label for="ThePassword">Password</label>

                            <div class="ButtonArea">
                                <p id="descPass" onclick="window.location.href = '/Profile/Change-Password'">Change</p>
                            </div>
                        </div>
                    </div>
                    <div class="BodyFill Gender">
                        <div class="titleSub">
                            <p>Gender</p>
                            <p class="pOptional">Optional</p>
                        </div>
                        <div class="radioArea">
                            <label for="">
                                <input type="radio" name="Gender" id="" value="Male" @if($data->Gender=="Male") checked @endif>
                                Male
                            </label>
                            <label for="">
                                <input type="radio" name="Gender" id="" value="Female" @if($data->Gender=="Female") checked @endif>
                                Female
                            </label>
                            <label for="">
                                <input type="radio" name="Gender" id="" value="Prefer not to say" @if($data->Gender=="Prefer not to say") checked @endif>
                                I Prefer not to say
                            </label>

                        </div>
                    </div>
                </div>
                <div class="containerButton">
                    <button type="submit" onclick="EditProfileInfo('edit',event,this)" id="toEdit">Update Profile</button>
                </div>
            </form>
            @elseif($wht=="Change-Password")
            <form action="/Profile/ChangePassword-Update" class="formProfile vertical" id="formProfileChangePassword" method="POST">
                
                @csrf
                <div class="containerbody vertical">
                    
                    <div class="BodyFill">
                        <div class="input-container Info" id="PasswordArea">
                            <input type="password" name="currentPassword" id="OldPassword" placeholder="" value="">
                            <label for="ThePassword">Enter Current Password*</label>
                            

                            <div class="ButtonArea">
                                <p id="descPass" onclick="ChangePasswordInfo(this,'OldPassword')">Show</p>
                            </div>
                        </div>
                    </div>

                    <div class="BodyFill">
                        <div class="input-container Info" id="PasswordArea">
                            <input type="password" name="NewPassword" id="NewPassword" placeholder="">
                            <label for="ThePassword">Enter New Password*</label>
                            

                            <div class="ButtonArea">
                                <p id="descPass" onclick="ChangePasswordInfo(this,'NewPassword')">Show</p>
                            </div>
                        </div>
                    </div>

                    <div class="BodyFill">
                        <div class="input-container Info" id="PasswordArea">
                            <input type="password" name="RetypeNewPassword" id="RetypeNewpassword" placeholder="">
                            <label for="ThePassword">Re-enter New password*</label>
                            
                            <div class="ButtonArea">
                                <p id="descPass" onclick="ChangePasswordInfo(this,'RetypeNewpassword')">Show</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="containerButton Info">
                    <button type="submit" onclick="formSubmit('ChangePassword',event)">Update</button>
                </div>

            </form>
            @elseif($wht=="Address")
            <form action="/Profile/{{{$wht}}}-Update" class="formProfile horizontal " id="formProfileAddress" method="POST">
                @csrf
                <div class ="containerbody TwoSpace first" style="display: none">
                    <div class="lefts">
                        <div class="BodyFill">
                            <div class="input-container select">
                                <p>Province </p>
                                <select name="provinsi" class="Provinces" id="" onchange="ChangeCity(this.value)">
                                    <option value="0" selected>Pilih Provinsi Dahulu</option>
                                    @foreach($data->Province as $a)
                                    <option value="{{{$a['province_id']}}}">{{{$a['province']}}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="BodyFill">
                            <div class="input-container select">
                                <p>Kota / Kabupaten</p>
                                <select name="KotaKabupaten" class="KotaKabupaten" id="">
                                    <option value="0" selected>Pilih Provinsi Dahulu</option>
                                </select>
                            </div>
                        </div>
                        <div class="BodyFill">
                            <div class="input-container">
                                    <input type="text" name="Kecamatan" placeholder="Genteng" id="inputField">
                                    <label for="inputField">Kecamatan</label>
                            </div>
                        </div>
                        <div class="BodyFill">
                            <div class="input-container">
                                    <input type="text" name="Kelurahan" placeholder="Embong Kaliasin" id="inputField">
                                    <label for="inputField">Kelurahan</label>
                            </div>
                        </div>
                        
                    </div>
                    <div class="rights">
                        <div class="BodyFill">
                            <div class="input-container">
                                        <input type="text" name="RT" placeholder="012" id="inputField">
                                        <label for="inputField">RT</label>
                            </div>
                        </div>

                        <div class="BodyFill">
                            <div class="input-container">
                                    <input type="text" name="RW" placeholder="013" id="inputField">
                                    <label for="inputField">RW</label>
                            </div>
                        </div>
                        <div class="BodyFill">
                            <div class="input-container end">
                                    <input type="text" name="AlamatDetail" placeholder="Jl. Kalijudan I No. 45" id="inputField">
                                    <label for="inputField">Alamat Detail*</label>
                            </div>
                        </div>
                        <div class="BodyFill">
                            <div class="input-container end">
                                    <input type="text" name="KodePos" placeholder="60241" id="inputField">
                                    <label for="inputField">Kode Pos*</label>
                            </div>
                        </div>
                    </div>
                        
                    
                    
                </div>
                <div class ="containerbody second">
                    <div>
                        <p>{{($data->address[0]->Detil)}}</p>
                    </div>
                </div>
                <div class="containerButton">
                    <button onclick="editAddress(event,this)">Edit Address</button>
                </div>

            </form>
            @endif
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
        // function ChangeText2(elemen){
        //     let theName = elemen.querySelectorAll("P");
        //     let theText = document.querySelectorAll(".container2>.Text2>p")
        //     theText[0].textContent = theName[0].textContent;
        //     document.querySelectorAll(".formProfile").forEach(function(element){
        //         element.style.display="none";
        //     })

        //     let theForm = document.querySelector(("#formProfile"+theName[0].textContent.replace(/\s+/g, '')));
        //     theForm.style.display = "flex";
        // }

        // EditProfileInfo('non-edit',event);

        async function editAddress(event,button){
            event.preventDefault();

            let editSpace = document.querySelectorAll('#formProfileAddress>.containerbody');
            if(button.textContent=="Edit Address"){
                
                let data = await fetch('/isNew');
                let isnew = await data.json();
                // console.log(isnew);
                @if(isset($data->address))
                    @if(($data->address[0]->Detil)!='The address has not been set.')
                        if(isnew==1){
                            // Mendapatkan semua elemen input, textarea, dan select
                            let elementsWithName = Array.from(document.querySelectorAll('[name]'));
                            for(let i=4;i<elementsWithName.length;i++){
                                // console.log(elementsWithName[i]);
                                (i==4)? elementsWithName[i].value = '{{$data->address[0]->province_id}}' : 0; 
                                (i==4)? changeValueCity(elementsWithName[i],elementsWithName[i+1],'{{$data->address[0]->province_id}}','{{$data->address[0]->city_id}}'):null;
                                (i==6)? elementsWithName[i].value = '{{$data->address[0]->Kecamatan}}' : 0; 
                                (i==7)? elementsWithName[i].value = '{{$data->address[0]->Kelurahan}}' : 0; 
                                (i==8)? elementsWithName[i].value = '{{$data->address[0]->RT}}' : 0; 
                                (i==9)? elementsWithName[i].value = '{{$data->address[0]->RW}}' : 0; 
                                (i==10)? elementsWithName[i].value = '{{$data->address[0]->AlamatDetil}}' : 0; 
                                (i==11)? elementsWithName[i].value = '{{$data->address[0]->KodePos}}' : 0; 
                            }

                        }
                    @endif
                @endif
                button.textContent = "Save Changes";
                editSpace[0].style.display = "flex";
                editSpace[1].style.display = "none";
            }
            else{
                formSubmit('Address',event);
            }
        }
        async function changeValueCity(province,city, idProvince, id){

            if(await ChangeCity(idProvince)==1){
                province.value = idProvince;
                // setTimeout(()=>{
                console.log(id)
                    console.log(city.value)
                    city.value=id;
                    console.log(id)
                    console.log(city.value)
                // },3000)
            }
        }
        function formSubmit(wht, event){
            // console.log(wht);
            event.preventDefault();
            if(wht=='Address'){
                let prov = document.querySelector('.Provinces')
                let kota = document.querySelector('.KotaKabupaten')

                if(prov.value==0){
                    showPopup('Please select a province first.',0)
                }
                else if(kota.value==0){
                    showPopup('Please select a city first.',0)
                }
                else{
                    document.getElementById('formProfile'+wht).submit();
                }
                // if(Check.)
                console.log(Check.value);
            }
            else{

                document.getElementById('formProfile'+wht).submit();
            }
        }
        function EditProfileInfo(wht,event,button){
            event.preventDefault();
            if(button.textContent=="Save Changes"){
                document.getElementById('formProfileInfo').submit();
            }
            let input = document.querySelectorAll('#formProfileInfo input');
            input.forEach(e => {
                e.disabled = ((wht!=='edit'));
                if(wht==='edit'){
                    button.textContent="Save Changes";
                }
                else{
                    button.textContent="Update Profile"
                }
            });
        }
        let seeBut = document.getElementById('See');
            seeBut.addEventListener("click", function(event){
                event.preventDefault();
            })

        let unSeeBut = document.getElementById('UnSee');
        unSeeBut.addEventListener("click", function(event){
            event.preventDefault();
        })
        function Password(a){
            const pw = document.getElementById('newPassword');
            if(a=="LetsSee"){
                seeBut.style.display="none";
                unSeeBut.style.display="flex";
                pw.type="password";
            }
            else{
                seeBut.style.display="flex";
                unSeeBut.style.display="none"; 
                pw.type="text";
            }
        }

        function ChangePassword(what){
            let but1 = document.getElementById('See');
            let but2 = document.getElementById('UnSee');
            let desc = document.getElementById('descPass');
            const labels = document.querySelectorAll('#PasswordArea label');
            const inputs = document.querySelectorAll('#PasswordArea input');

            if(what=='change'){
                labels[1].style.display = "flex";
                inputs[1].style.display = "flex";
                inputs[1].type = "password";
                labels[0].style.display = "none";
                inputs[0].style.display = "none";
                console.log('masuk')
                but1.style.display = "none";
                but2.style.display = "flex";
                desc.textContent = "Cancel";
                desc.setAttribute("onclick", "ChangePassword('cancel')")

            }
            else{
                labels[1].style.display = "none";
                inputs[1].style.display = "none";
                labels[0].style.display = "flex";
                inputs[0].style.display = "flex";
                but1.style.display = "none";
                but2.style.display = "none";
                desc.textContent = "Change";
                desc.setAttribute("onclick", "ChangePassword('change')")
            }

        }

        function ChangePasswordInfo(elemen, idinput){
            let inp = document.querySelectorAll(("#PasswordArea #"+idinput));
            console.log(inp[0].value);
            if(elemen.textContent=="Show"){
                inp[0].type="text";
                elemen.textContent = "Hide";
            }
            else{
                inp[0].type="password";
                elemen.textContent = "Show";
            }
        }

        
        async function ChangeCity($idProvince){
            let response = await fetch('/getCity/'+$idProvince);
            let data = await response.json();
            $back = 0;
            if(fillCity(data)==1){
                $back = 1;
            }
            else{
                $back= 0;
            }
            console.log($back)
            return $back;
            
        // console.log(data);

            // console.log('masuk')
        }

        function fillCity(data){
            let select = document.querySelector('.KotaKabupaten');
            ClearCity(select);
                let option = document.createElement('option'); // Buat elemen <option>
                option.value = '0';
                option.textContent = 'Pilih Kota atau Kabupaten'
                option.selected = true;
            select.appendChild(option);

            data.forEach(item => {
                let option = document.createElement('option'); // Buat elemen <option>
                option.value = item.city_id; // Atur atribut value
                option.textContent = item.city_name; // Atur teks yang ditampilkan
                select.appendChild(option); // Tambahkan opsi ke <select>
            });
            return 1;


        }

        function ClearCity(select){
            // let select = document.querySelector('.KotaKabupaten');
            while (select.firstChild) {
                select.removeChild(select.firstChild);
            }
        }

    </script>
@endsection