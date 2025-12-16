
        <div class="containerPopUpView">
            <div class="variable">
                <div class="key">
                    <p>Name</p>
                </div>
                <div class="value">
                    <p>{{{$data->name}}}</p>
                </div>
            </div>

            <div class="variable">
                <div class="key">
                    <p>Email</p>
                </div>
                <div class="value">
                    <p>{{{$data->email}}}</p>
                </div>
            </div>

            <div class="variable">
                <div class="key">
                    <p>Phone</p>
                </div>
                <div class="value">
                    <p>{{{$data->Phone}}}</p>
                </div>
            </div>

            <div class="variable">
                <div class="key">
                    <p>Gender</p>
                </div>
                <div class="value">
                    <p>{{{$data->Gender}}}</p>
                </div>
            </div>
            <div class="variable">
                <div class="key">
                    <p>Addresses</p>
                </div>
                <div class="value">
                    <p>{{{$data->address}}}</p>
                </div>
            </div>
        </div>
        <div class="ButtonAction" onclick="holdPrevent(event)">
            @if($data->isActive=='active')
                <button class="Deactive btn-pointer" onclick="Deactive('{{{$data->id}}}')">Deactive</button>
            @else
                <button class="Deactive btn-pointer" onclick="Deactive('{{{$data->id}}}')">Activate</button>
            @endif
            <button class="DeleteAccount btn-pointer" onclick="DeleteAccount('{{{$data->id}}}')">Delete Account</button>
        </div>