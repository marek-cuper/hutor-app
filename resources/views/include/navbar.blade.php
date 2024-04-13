@guest
    <script>window.location = "{{ route('prihlasenie') }}";</script>
@endguest

<div id="navbar">
    <a id="navbar-logo" href="{{ route('domov') }}">
        <img src="{{ asset('images/hutor_cierne_cierne.png') }}" width="100" height="50">
    </a>


    <div id="navbar-search-duo" >
        <input id="navbar-search-bar" placeholder="Vyhladaj pouzivatela" type="text" class="round" />
        <div class="navbar_ikona_pozadie" id="navbar-sipka-icon">
            <i class="fa fa-search fa-1x"></i>
        </div>
    </div>

    <div class="navbar-menu" >
        <a href="{{URL::route('pravidla') }}" class="navbar-icon">
            <i class="fa fa-info-circle fa-2x"></i>
        </a>
        <a id="moderator_ikona" href="{{ route('moderator_panel') }}" class="navbar-icon" style="display: none;">
            <i class="fa fa-cogs fa-2x"></i>
        </a>
        <a href="{{URL::route('profil_uprava') }}" class="navbar-icon">
            <i class="fa fa-user fa-2x"></i>
        </a>
        <a id="button-logout" href="{{URL::route('odhlasenie') }}" class="navbar-icon">
            <i class="fa fa-sign-out fa-2x"></i>
        </a>
    </div>
</div>

<script>
    var modIkona = document.getElementById('moderator_ikona');

    var buttonLogout = document.getElementById('button-logout');

    var searchInput = document.getElementById('navbar-search-bar');
    var searchButton = document.getElementById('navbar-sipka-icon');

    buttonLogout.addEventListener('click', function() {
        localStorage.removeItem("oldIndex");
    });

    document.addEventListener('DOMContentLoaded', function() {
        var privileges = @json(session('privileges'));
        if(privileges > 0){
            modIkona.style.display = "block";
        }else {
            modIkona.href = '';
        }
    });

    const nameChars = /^[a-zA-Z0-9]+$/;

    function searchProfile(){
        let input = searchInput.value;
        if(input.length > 0){
            if(nameChars.test(input)){
                $.ajax({
                    url: '/profil_vyhladavanie', // Replace with your server endpoint
                    method: 'POST',
                    data: { input: input, _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        window.location.href = '/profil_vyhladavanie';

                    },
                    error: function (error) {
                        console.error('Error:', error);
                    }
                });
            }
        }else {
            alert('Je potrebné zadať aspoň jeden znak');
        }
    }

    searchInput.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            searchProfile();
        }
    });

    searchButton.addEventListener('click', function() {
        searchProfile();
    });


</script>
