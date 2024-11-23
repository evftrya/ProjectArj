
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
                <p>Info</p>
            </div>
            <div class="ContainerSubMenu" onclick="ChangeText2(this)">
                <svg width="2" height="25" viewBox="0 0 3 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="2" height="25" fill="#B17457"/>
                </svg>
                <p>Change Password</p>
            </div>
            <div class="ContainerSubMenu" onclick="ChangeText2(this)">
                <svg width="2" height="25" viewBox="0 0 3 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="2" height="25" fill="#B17457"/>
                </svg>
                <p>Address</p>
            </div>
            <div class="ContainerSubMenu" onclick="ChangeText2(this)">
                <svg width="2" height="25" viewBox="0 0 3 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="2" height="25" fill="#B17457"/>
                </svg>
                <p>Logout</p>
            </div>
        </div>
        <div class="container2">
            <div class="Text2">
                <p>Info</p>
                <svg width="100%" height="1" viewBox="0 0 100% 1" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <line y1="0.5" x2="100%" y2="0.5" stroke="#B17457"/>
                </svg>
            </div>
            <form action="" class="formProfile" id="formProfileInfo">
                <div class="containerbody horizontal">
                    <div class="BodyFill">
                        <div class="input-container">
                                <input type="text" name="emailUser" placeholder="" id="inputField">
                                <label for="inputField">First Name</label>
                        </div>
                    </div>
                    <div class="BodyFill">
                        <div class="input-container">
                            
                                <input type="text" name="emailUser" placeholder="" id="inputField">
                                <label for="inputField">Last Name</label>
                            
                        </div>
                    </div>
                    <div class="BodyFill">
                        <div class="input-container">
                                <input type="email" name="emailUser" placeholder="" id="inputField">
                                <label for="inputField">Email</label>
                        </div>
                    </div>
                    <div class="BodyFill">
                        <div class="input-container">
                            
                                <input type="email" name="emailUser" placeholder="" id="inputField">
                                <label for="inputField">Phone</label>
                            
                        </div>
                    </div>
                    <div class="BodyFill">
                        <div class="input-container" id="PasswordArea">

                                <input type="password" name="passwordUser" id="ThePassword" placeholder="" value="ghscmdjkfcnskd" disabled>
                                <label for="ThePassword">Password</label>
                                <input type="password" name="ChangePassword" id="newPassword" placeholder=""  style="display: none;">
                                <label for="newPassword" style="display: none;">New Password</label>

                            <div class="ButtonArea">
                                <p id="descPass" onclick="ChangePassword('change')">Change</p>
                                <button id="See" onclick="Password('LetsSee')" style="display: none;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="50" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                    </svg>
                                </button>
                                <button id="UnSee" onclick="Password('LetsUnSee')" style="display: none;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="50" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
                                        <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
                                        <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
                                        <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
                                    </svg>
                                </button>
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
                                <input type="radio" name="tes" id="" value="1">
                                Male
                            </label>
                            <label for="">
                                <input type="radio" name="tes" id="" value="1">
                                Female
                            </label>
                            <label for="">
                                <input type="radio" name="tes" id="" value="1">
                                I Prefer not to say
                            </label>

                        </div>
                    </div>
                </div>
                <div class="containerButton">
                    <button>Save Changes</button>
                </div>
            </form>

            <form action="" class="formProfile vertical" id="formProfileChangePassword" style="display:none;">
                <div class="containerbody vertical">
                    <div class="BodyFill">
                        <div class="input-container Info" id="PasswordArea">
                            <input type="password" name="passwordUser" id="OldPassword" placeholder="">
                            <label for="ThePassword">Enter Current Password</label>
                            

                            <div class="ButtonArea">
                                <p id="descPass" onclick="ChangePasswordInfo(this,'OldPassword')">Show</p>
                            </div>
                        </div>
                    </div>

                    <div class="BodyFill">
                        <div class="input-container Info" id="PasswordArea">
                            <input type="password" name="passwordUser" id="NewPassword" placeholder="">
                            <label for="ThePassword">Enter New Password</label>
                            

                            <div class="ButtonArea">
                                <p id="descPass" onclick="ChangePasswordInfo(this,'NewPassword')">Show</p>
                            </div>
                        </div>
                    </div>

                    <div class="BodyFill">
                        <div class="input-container Info" id="PasswordArea">
                            <input type="password" name="passwordUser" id="RetypeNewpassword" placeholder="">
                            <label for="ThePassword">Re-enter New password</label>
                            
                            <div class="ButtonArea">
                                <p id="descPass" onclick="ChangePasswordInfo(this,'RetypeNewpassword')">Show</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="containerButton Info">
                    <button>Update</button>
                </div>

            </form>

            <form action="" class="formProfile horizontal " id="formProfileAddress" style="display:none;">
                <div class ="containerbody TwoSpace">
                    <div class="lefts">
                        <div class="BodyFill">
                            <div class="input-container">
                                    <input type="email" name="emailUser" placeholder="Jawa Timur" id="inputField">
                                    <label for="inputField">Provinsi</label>
                            </div>
                        </div>
                        <div class="BodyFill">
                            <div class="input-container">
                                    <input type="email" name="emailUser" placeholder="Bondowoso" id="inputField">
                                    <label for="inputField">Kota / Kabupaten</label>
                            </div>
                        </div>
                        <div class="BodyFill">
                            <div class="input-container">
                                    <input type="email" name="emailUser" placeholder="Genteng" id="inputField">
                                    <label for="inputField">Kecamatan</label>
                            </div>
                        </div>
                        <div class="BodyFill">
                            <div class="input-container">
                                    <input type="email" name="emailUser" placeholder="Embong Kaliasin" id="inputField">
                                    <label for="inputField">Kelurahan</label>
                            </div>
                        </div>
                        
                    </div>
                    <div class="rights">
                        <div class="BodyFill">
                            <div class="input-container">
                                        <input type="email" name="emailUser" placeholder="012" id="inputField">
                                        <label for="inputField">RT</label>
                            </div>
                        </div>

                        <div class="BodyFill">
                            <div class="input-container">
                                    <input type="email" name="emailUser" placeholder="013" id="inputField">
                                    <label for="inputField">RW</label>
                            </div>
                        </div>
                        <div class="BodyFill">
                            <div class="input-container end">
                                    <input type="email" name="emailUser" placeholder="Jl. Kalijudan I No. 45" id="inputField">
                                    <label for="inputField">Alamat Detail</label>
                            </div>
                        </div>
                    </div>
                        
                    
                    
                </div>
                <div class="containerButton">
                    <button>Save Changes</button>
                </div>
            </form>
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

    </script>
@endsection