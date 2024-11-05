<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login Page</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{asset('css/LoginAndRegister.css')}}" rel="stylesheet">
        <!-- <link href="login.css" rel="stylesheet"> -->

    </head>
    <body>
        <div class="BackButton">
            <a href="{{ url()->previous() }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/>
                </svg>
                <p>Back</p>
            </a>
        </div>
        <div class="Container">
            <div class="namaCv">
                <p>CV. Danmaz Perkasa</p>
            </div>
            <p>Welcome back</p>
            <div class="InputArea ">
                <form action="/loginAccount" method="POST">
                    @csrf 

                    <div class="input-container">
                        <!-- <p>Email</p> -->
                            <input type="email" name="emailUser" placeholder="" id="inputField">
                            <label for="inputField">Email</label>
                        <!-- <input type="email" name="" id="" placeholder="username@gmail.com"> -->
                    </div>
                    <div class="input-container" id="PasswordArea">
                            <!-- <p>Password</p> -->
                            <input type="password" name="passwordUser" id="ThePassword" placeholder="">
                            <label for="inputField">Password</label>
                        <div class="ButtonArea">
                            <button id="See" onclick="Password('LetsSee')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="50" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                </svg>
                            </button>
                            <button id="UnSee" onclick="Password('LetsUnSee')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="50" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
                                    <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
                                    <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
                                    <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <a href=""><p>Forgot Password?</p></a>
                    <button type="submit">
                        <p>Login</p>
                    </button>
                    <div class="ToRegister">
                        <p>Don't have an account?</p>
                        <a href="/Register"><p>Register</p></a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- <img src="asetfoto/login.jpg" alt=""> -->
    </body>
    <script>
        let seeBut = document.getElementById('See');
            seeBut.addEventListener("click", function(event){
                event.preventDefault();
            })

        let unSeeBut = document.getElementById('UnSee');
        unSeeBut.addEventListener("click", function(event){
            event.preventDefault();
        })
        function Password(a){
            let pw = document.getElementById('ThePassword'); 
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

    </script>
</html>