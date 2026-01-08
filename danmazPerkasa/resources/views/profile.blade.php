@extends('layouts.BasicPage1')
@section('css')
<!-- <link rel="stylesheet" type="" href="{{asset('css/profile.css')}}"> -->
<!-- <link rel="stylesheet" href="{{ secure_asset('css/profile.css') }}"> -->
<link rel="stylesheet" href="{{ app()->environment('local')? asset('css/profile.css') : secure_asset('css/profile.css') }}">
@endsection

@section('content')

<div>
    <div class="Text">
        <p>Profil Saya</p>
        <svg class="theSvgLine" height="1" viewBox="0 0 100% 1" fill="none" xmlns="http://www.w3.org/2000/svg">
            <line y1="0.5" x2="100%" y2="0.5" stroke="#B17457"/>
        </svg>
    </div>

    <div class="container">
        <div class="sideBar">
            <div class="ContainerSubMenu {{{$cp=='Info'? 'active':''}}}" onclick="ChangeText2(this)">
                <svg width="2" height="25" viewBox="0 0 3 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="2" height="27" fill="#B17457"/>
                </svg>
                <p><a href="/Profile/Info">Info</a></p>
            </div>

            <div class="ContainerSubMenu {{{$cp=='ChangePassword'? 'active':''}}}" onclick="ChangeText2(this)">
                <svg width="2" height="25" viewBox="0 0 3 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="2" height="25" fill="#B17457"/>
                </svg>
                <p><a href="/Profile/Change-Password">Ubah Kata Sandi</a></p>
            </div>

            <div class="ContainerSubMenu {{{$cp=='Address'? 'active':''}}}" onclick="ChangeText2(this)">
                <svg width="2" height="25" viewBox="0 0 3 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="2" height="25" fill="#B17457"/>
                </svg>
                <p><a href="/Profile/Address">Alamat</a></p>
            </div>

            <div class="ContainerSubMenu {{{$cp=='Logout'? 'active':''}}}" onclick="ChangeText2(this)">
                <svg width="2" height="25" viewBox="0 0 3 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="2" height="25" fill="#B17457"/>
                </svg>
                <p><a href="/Logout">Keluar</a></p>
            </div>
        </div>

        <div class="container2">
            <div class="Text2">
                <p>{{{$cp=='ChangePassword'? 'Ubah Kata Sandi':($cp=='Address'?'Alamat':$cp)}}}</p>
                <svg class="theSvgLine" width="100%" height="1" viewBox="0 0 100% 1" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <line y1="0.5" x2="100%" y2="0.5" stroke="#B17457"/>
                </svg>
            </div>

            @if($wht=="Info")
            <form action="/Profile/{{{$wht}}}-Update" class="formProfile" id="formProfileInfo" method="POST">
                @csrf
                <div class="containerbody horizontal">
                    <div class="BodyFill">
                        <div class="input-container">
                            <input type="text" name="firstName" placeholder="" maxlength="30" id="inputField" value="{{{$data->firstName}}}" required>
                            <label for="inputField">Nama Depan*</label>
                        </div>
                    </div>

                    <div class="BodyFill">
                        <div class="input-container">
                            <input type="text" name="lastName" placeholder="" maxlength="30" id="inputField" value="{{{$data->lastName}}}" required>
                            <label for="inputField">Nama Belakang*</label>
                        </div>
                    </div>

                    <div class="BodyFill">
                        <div class="input-container">
                            <input type="email" name="emailUser" placeholder=""
                                maxlength="50" id="inputField"
                                value="{{ $data->emailUser }}"
                                pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                                required
                                oninvalid="this.setCustomValidity('Format email tidak valid. Harap masukkan email yang benar, misalnya: example@domain.com.')"
                                oninput="this.setCustomValidity('')">
                            <label for="inputField">Email*</label>
                        </div>
                    </div>

                    <div class="BodyFill">
                        <div class="input-container">
                            <input type="text" name="Phone" placeholder="" maxlength="15" id="inputField" value="{{{$data->Phone}}}" required>
                            <label for="inputField">No. Telepon*</label>
                        </div>
                    </div>

                    <div class="BodyFill">
                        <div class="input-container" id="PasswordArea">
                            <input type="password" name="passwordUser" id="ThePassword" maxlength="25" placeholder="" value="{{{$data->lenPassword}}}">
                            <label for="ThePassword">Kata Sandi</label>

                            <div class="ButtonArea">
                                <p id="descPass" onclick="window.location.href = '/Profile/Change-Password'">Ubah</p>
                            </div>
                        </div>
                    </div>

                    <div class="BodyFill Gender">
                        <div class="titleSub">
                            <p>Jenis Kelamin</p>
                            <p class="pOptional">Opsional</p>
                        </div>
                        <div class="radioArea">
                            <label>
                                <input type="radio" name="Gender" value="Male" @if($data->Gender=="Male") checked @endif>
                                Laki-laki
                            </label>
                            <label>
                                <input type="radio" name="Gender" value="Female" @if($data->Gender=="Female") checked @endif>
                                Perempuan
                            </label>
                            <label>
                                <input type="radio" name="Gender" value="Prefer not to say" @if($data->Gender=="Prefer not to say") checked @endif>
                                Tidak ingin menyebutkan
                            </label>
                        </div>
                    </div>
                </div>

                <div class="containerButton">
                    <button type="submit" onclick="EditProfileInfo('edit',event,this)" id="toEdit">Simpan Perubahan</button>
                </div>
            </form>

            @elseif($wht=="Change-Password")
            <form action="/Profile/ChangePassword-Update" class="formProfile vertical" id="formProfileChangePassword" method="POST">
                @csrf
                <div class="containerbody vertical">

                    <div class="BodyFill">
                        <div class="input-container Info" id="PasswordArea">
                            <input type="password" name="currentPassword" maxlength="25" id="OldPassword" placeholder="" value="">
                            <label for="ThePassword">Masukkan Kata Sandi Saat Ini*</label>

                            <div class="ButtonArea">
                                <p id="descPass" onclick="ChangePasswordInfo(this,'OldPassword')">Tampilkan</p>
                            </div>
                        </div>
                    </div>

                    <div class="BodyFill">
                        <div class="input-container Info" id="PasswordArea">
                            <input type="password" name="NewPassword" maxlength="25" id="NewPassword" placeholder="">
                            <label for="ThePassword">Masukkan Kata Sandi Baru*</label>

                            <div class="ButtonArea">
                                <p id="descPass" onclick="ChangePasswordInfo(this,'NewPassword')">Tampilkan</p>
                            </div>
                        </div>
                    </div>

                    <div class="BodyFill">
                        <div class="input-container Info" id="PasswordArea">
                            <input type="password" name="RetypeNewPassword" maxlength="25" id="RetypeNewpassword" placeholder="">
                            <label for="ThePassword">Masukkan Ulang Kata Sandi Baru*</label>

                            <div class="ButtonArea">
                                <p id="descPass" onclick="ChangePasswordInfo(this,'RetypeNewpassword')">Tampilkan</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="containerButton Info">
                    <button type="Submit" onclick="checkPassword(event, this)">Perbarui</button>
                </div>
            </form>

            @elseif($wht=="Address")
            <form action="/Profile/{{{$wht}}}-Update" class="formProfile horizontal " id="formProfileAddress" method="POST">
                @csrf
                <div class ="containerbody TwoSpace first" style="display: none">
                    <div class="lefts">
                        <div class="BodyFill">
                            <div class="input-container select">
                                <p>Provinsi</p>
                                <select name="provinsi" class="Provinces" onchange="ChangeCity(this.value)">
                                    <option value="0" selected>Pilih Provinsi Terlebih Dahulu</option>
                                    @foreach($data->Province as $a)
                                    <option value="{{{$a['province_id']}}}">{{{$a['province_name']}}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="BodyFill">
                            <div class="input-container select">
                                <p>Kota / Kabupaten</p>
                                <select name="KotaKabupaten" class="KotaKabupaten" onchange="ChangeKecamatan(this.value)">
                                    <option value="0" selected>Pilih Provinsi Terlebih Dahulu</option>
                                </select>
                            </div>
                        </div>

                        <div class="BodyFill">
                            <div class="input-container select">
                                <p>Kecamatan</p>
                                <select name="Kecamatan" class="Kecamatan">
                                    <option value="0" selected>Pilih Kota Terlebih Dahulu</option>
                                </select>
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
                                <label for="inputField">Detail Alamat*</label>
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
                    <button onclick="editAddress(event,this)">Edit Alamat</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function formPassword(elemen){
        let form = elemen.closest('form')
        form.submit();
    }

    async function editAddress(event,button){
        event.preventDefault();

        let editSpace = document.querySelectorAll('#formProfileAddress>.containerbody');
        if(button.textContent=="Edit Alamat"){
            let data = await fetch('/isNew/Address');
            let isnew = await data.json();

            @if(isset($data->address))
                @if(($data->address[0]->Detil)!='Alamat Belum Diisi')
                    if(isnew==1){
                        let elementsWithName = Array.from(document.querySelectorAll('[name]')).filter(el => el.getAttribute('name') !== '_token');
                        for(let i=4;i<elementsWithName.length;i++){
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

            button.textContent = "Simpan Perubahan";
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
            city.value = id;
        }
    }

    function formSubmit(wht, event){
        event.preventDefault();
        if(wht=='Address'){
            let prov = document.querySelector('.Provinces')
            let kota = document.querySelector('.KotaKabupaten')

            if(prov.value==0){
                showPopup('Silakan pilih provinsi terlebih dahulu.',0)
            }
            else if(kota.value==0){
                showPopup('Silakan pilih kota/kabupaten terlebih dahulu.',0)
            }
            else{
                document.getElementById('formProfile'+wht).submit();
                initializeLoadingIndicator();
            }
        }
        else{
            document.getElementById('formProfile'+wht).submit();
        }
    }

    thePop()

    function thePop(){
        let msg = "{{ session('message') }}";
        if(msg){
            parsePopUp(msg);
        }
    }

    function parsePopUp(wht){
        let bool = wht[wht.length-1];
        let send = wht.slice(0, -1);
        if(bool=="0"){
            showPopup(send, 0);
        }
        else{
            showPopup(send, 1);
        }
    }

    function showPopup(wht,which) {
        setTimeout(() => {
            const popup = document.getElementById('popup');
            if(which==0){
                popup.style.backgroundColor="#b32323";
            }
            else{
                popup.style.backgroundColor="#4caf50";
            }
            popup.textContent = wht
            popup.classList.add('show');

            setTimeout(() => {
                popup.classList.remove('show');
            }, 1500);
        }, 50);
    }

    function EditProfileInfo(wht,event,button){
        if(button.textContent=="Simpan Perubahan"){
            let input = document.querySelectorAll('.formProfile input')
        }
    }

    function checkPassword(event, elemen){
        let old = document.querySelector('#OldPassword').value;
        let Neww = document.querySelector('#NewPassword').value;
        let retype = document.querySelector('#RetypeNewpassword').value;

        if(old==Neww){
            event.preventDefault();
            showPopup("Kata sandi baru tidak boleh sama dengan kata sandi lama",0);
        }
        else if(Neww!=retype){
            event.preventDefault();
            showPopup("Kata sandi baru dan konfirmasi kata sandi tidak sama",0);
        }
        else if(!Neww.length>=8 || !/[A-Z]/.test(Neww)||!/[a-z]/.test(Neww)){
            event.preventDefault();
            let string= 'Kata sandi minimal 8 karakter\nHarus ada minimal 1 huruf besar\nHarus ada minimal 1 huruf kecil';
            showPopup(string,0);
        }
    }

    function ChangePasswordInfo(elemen, idinput){
        let inp = document.querySelectorAll(("#PasswordArea #"+idinput));
        if(elemen.textContent=="Tampilkan"){
            inp[0].type="text";
            elemen.textContent = "Sembunyikan";
        }
        else{
            inp[0].type="password";
            elemen.textContent = "Tampilkan";
        }
    }

    async function ChangeCity($idProvince){
        let response = await fetch('/getCity/'+$idProvince);
        let data = await response.json();
        $back = 0;
        if(fillCity(data)==1){
            $back = 1;
        } else {
            $back = 0;
        }
        return $back;
    }

    async function ChangeKecamatan($idKota){
        let response = await fetch('/getKecamatan/'+$idKota);
        let data = await response.json();
        $back = 0;
        if(fillKecamatan(data)==1){
            $back = 1;
        } else {
            $back = 0;
        }
        return $back;
    }

    function fillCity(data){
        let select = document.querySelector('.KotaKabupaten');
        ClearCity(select);

        let option = document.createElement('option');
        option.value = '0';
        option.textContent = 'Pilih Kota atau Kabupaten'
        option.selected = true;
        select.appendChild(option);

        data.forEach(item => {
            let option = document.createElement('option');
            option.value = item.city_id;
            option.textContent = item.city_name;
            select.appendChild(option);
        });
        return 1;
    }

    function fillKecamatan(data){
        let select = document.querySelector('.Kecamatan');
        ClearKecamatan(select);

        let option = document.createElement('option');
        option.value = '0';
        option.textContent = 'Pilih Kecamatan'
        option.selected = true;
        select.appendChild(option);

        data.forEach(item => {
            let option = document.createElement('option');
            option.value = item.nama_kecamatan;
            option.textContent = item.nama_kecamatan;
            select.appendChild(option);
        });
        return 1;
    }

    function ClearCity(select){
        while (select.firstChild) {
            select.removeChild(select.firstChild);
        }
    }

    function ClearKecamatan(select){
        while (select.firstChild) {
            select.removeChild(select.firstChild);
        }
    }

    function initializeLoadingIndicator() {
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

        const spinner = document.createElement('div');
        spinner.style.border = '8px solid #f3f3f3';
        spinner.style.borderTop = '8px solid #3498db';
        spinner.style.borderRadius = '50%';
        spinner.style.width = '60px';
        spinner.style.height = '60px';
        spinner.style.animation = 'spin 1s linear infinite';

        const text = document.createElement('p');
        text.textContent = 'Kami sedang menyiapkan data Anda';
        text.style.marginTop = '20px';
        text.style.fontSize = '16px';

        loadingIndicator.appendChild(spinner);
        loadingIndicator.appendChild(text);

        document.body.appendChild(loadingIndicator);

        const styleSheet = document.styleSheets[0];
        styleSheet.insertRule(`
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `, styleSheet.cssRules.length);

        window.addEventListener('pagehide', function () {
            loadingIndicator.style.display = 'flex';
        });

        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                loadingIndicator.style.display = 'none';
            }
        });

        window.addEventListener('beforeunload', function () {
            loadingIndicator.style.display = 'flex';
        });
    }

    function ChangeText2(elemen){
        let div = document.querySelectorAll('.ContainerSubMenu');
        div.forEach(e=>{
            e.classList.remove('active');
            if(elemen==e){
                e.classList.add('active')
            }
        })
    }
</script>
@endsection
