<div id="navbar">
    <div id="navbar-logo" href="#">
        <img src="{{ asset('images/hutor_cierne_cierne.png') }}" width="100" height="50">
    </div>


    <div id="navbar-search-duo" >
        <input id="navbar-search-bar" type="text" class="round" />
        <div class="domov_prispevok_icon_pozadie" id="navbar-sipka-icon">
            <i class="fa fa-arrow-right fa-3x"></i>
        </div>
    </div>

    <div id="navbar-menu" >
        <div class="navbar-icon">
            <i class="fa fa-cog fa-2x"></i>
        </div>
        <div class="navbar-icon">
            <i class="fa fa-user fa-2x"></i>
        </div>
        <a id="button-logout" href="{{URL::route('odhlasenie') }}" class="navbar-icon">
            <i class="fa fa-sign-out fa-2x"></i>
        </a>
    </div>
</div>

<script>
    var buttonLogout = document.getElementById('button-logout');

    buttonLogout.addEventListener('click', function() {
        localStorage.removeItem("oldIndex");
    });
</script>
