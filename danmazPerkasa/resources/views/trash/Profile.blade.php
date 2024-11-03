@extends('layouts.BasicPage')
@section('css')
<link rel="stylesheet" type="" href="{{asset('css/Profile.css')}}">
@section('content')
<div class="SubTitle">
    <p>My Profile</p>
</div>
<div class="content">
    <div class="LeftMenu">
        <button class="TheMenu" onclick="ActiveMenu('Info')">
            <p id="InfoText">Info</p>
        </button>
        <button class="TheMenu" onclick="ActiveMenu('Info')">
            <p id="InfoText">Change Password</p>
        </button>
        <button class="TheMenu" onclick="ActiveMenu('Info')">
            <p id="InfoText">Address</p>
        </button>
        <button class="TheMenu" onclick="ActiveMenu('Info')">
            <p id="InfoText">Logout</p>
        </button>

        
        
    </div>
    <div class="IntoContent">
        <div class="TitleMenu">
            <p>Info</p>
        </div>
        <form action="">
            <div class="MainContent">
                <div class="ContentInfo">
                    <div class="TheInput">
                        <p>First Name</p>
                        <input type="text" placeholder="Ariana">
                    </div>
                    <div class="TheInput">
                        <p>Last Name</p>
                        <input type="text" placeholder="Grenade">
                    </div>
                    <div class="TheInput">
                        <p>Email</p>
                        <input type="email" placeholder="Ariana Grenade">
                    </div>
                    <div class="TheInput">
                        <p>Phone</p>
                        <input type="text" placeholder="089517651554">
                    </div>
                    <div class="TheInput" id="PasswordsArea">
                        <div>
                            <p>Password</p>
                            <input type="Password">
                        </div>
                        <div>
                            <button>
                                <p>Change</p>
                            </button>
                        </div>
                    </div>
                    <div class="TheInput" id="GendersArea">
                        <div class="Labels">
                            <p>Gender</p>
                            <p id="Optionals">Optional</p>   
                        </div>
                        <div class="Options">
                            <div>
                                <input type="radio" id="male" name="gender" value="male">
                                <label for="male">Male</label><br>
                            </div>
                            <div>
                                <input type="radio" id="female" name="gender" value="female">
                                <label for="female">Female</label><br>
                            </div>

                            <div>
                                <input type="radio" id="other" name="gender" value="other">
                                <label for="other">I prefer not to say</label><br>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="ButtonsArea">
                <!-- <button>Update</button> -->
                <button>Save Changes</button>
            </div>
        </form>
    </div>
    
</div>
<!-- <img src="{{asset('asetfoto/info.png')}}" alt=""> -->
<style>
    .topBar{
        border-bottom: 1px solid #9D5C0D !important;
        /* background-color: #9D5C0D !important; */
    }
    img{
        width: 500px;
    }
</style>
@endsection