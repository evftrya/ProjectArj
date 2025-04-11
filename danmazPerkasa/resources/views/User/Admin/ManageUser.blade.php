@extends('layouts.BasicPage1')

@section('css')
<!-- <link rel="stylesheet" type="" href="{{asset('css/ManageProduct.css')}}"> -->
<!-- <link rel="stylesheet" href="{{ secure_asset('css/ManageProduct.css') }}"> -->
<link rel="stylesheet" href="{{ app()->environment('local')? asset('css/ManageProduct.css') : secure_asset('css/ManageProduct.css') }}">

@endsection

@section('content')
<div class="LandingPage">
    <div class="titled">
        Manage User
    </div>
    <div class="theMainList" style="margin-top:30px;">
        <div class="TheList">
            @foreach($data as $d)
                <div class="theItems" onclick="viewProduct('{{{$d->id}}}')">
                    <p class="name">{{{$d->name}}}</p>
                    <p>{{{$d->email}}}</p>
                    <p>{{{$d->Phone}}}</p>
                    <p>{{{$d->role}}}</p>
                    <div class="theButtons">
                        <button>Detail</button>
                    </div>
                </div>
            @endforeach

        </div>
        
        <div class="bottomsArea">
            <p>No More User</p>
        </div>
    </div>
</div>
<script>
    function viewProduct(id){
        DetilProductAdd(id)
        fetch('/viewUser/'+id)
            .then(response => response.text())
            .then(html => { 
                let show = document.querySelector('.BodyDetail');
                show.innerHTML = html;
        })
        .catch(err => {
            console.error('Gagal memuat konten:', err);
        });
    }
    function DetilProductAdd(id){
        let container = document.querySelector(".maincontent");
        container.style.display = "flex !improtant";
        let div = document.createElement('div');
        div.className = 'ShowSomething';
        div.setAttribute('onclick', 'closeViewProduct()');
        div.innerHTML=`
            <div class="ShowContainer" style="width:400px; background-color:#fafafa;">
                <div class="HeaderDetails" style="background-color:#fafafa;">
                    <div class="TextDetails">
                        <p>Product Detail</p>
                    </div>
                </div>
                <div class="BodyDetail User" onclick="holdPrevent(event)" style="background-color:#fafafa;">
                    
                </div>
                
            </div>

        `
        // container.appendChild(div);
        container.insertBefore(div, container.firstChild);

    }
    async function Deactive(id){
        let adr = await fetch('/DeactiveAccount/'+id);
        let button = document.querySelector('.Deactive')
        let isSuccess = await adr.json();
        if(isSuccess==0){
            showPopup('Account has been successfully deactivated.');
            button.textContent = 'Activated';
        }
        else{
            button.textContent = 'Deactive';
            showPopup('Account has been successfully activated.');
            
        }

    }
    function DeleteAccount(id){
        window.location.href = '/DeleteAccount/'+id;
    }
    function closeViewProduct(){
        let container = document.querySelector(".maincontent");
        let show = document.querySelector('.ShowSomething'); 
        container.removeChild(show);
    }
    function holdPrevent(event){
        event.stopPropagation();

    }
</script>
@endsection