@include('include.head')

<body>
@auth
    <script>window.location = "{{ route('domov') }}";</script>
@endauth
<div class="prihlasenie_stranka">
    @if(session('error'))
        <div class="profil_uprava_odozva profil_uprava_odozva_chyba">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="profil_uprava_odozva profil_uprava_odozva_uspech">
            {{ session('success') }}
        </div>
    @endif
        <div class="prihlasenie_stranka_obsah">
            <div class="prihlasenie_stranka_logo">
                <img src="images/hutor_cierne_cierne.png" alt="">
            </div>

            <div class="prihlasenie_stranka_formular">
                <div class="prihlasenie_formular">
                    <form id="prihlasenie_straka_formular" action="{{ route('prihlasenie.post') }}" method="POST">
                        @csrf
                        <div class="prihlasenie_formular_kolonka">
                            <label><b>Email</b></label>
                            <input type="text" placeholder="Zadaj Email" id="email" name="email" required>
                        </div>
                        <div class="prihlasenie_formular_kolonka">
                            <label><b>Heslo</b></label>
                            <input type="password" placeholder="Zadaj Heslo" id="password" name="password" required>
                        </div>

                        <button class="prihlasenie_stranka_formular_tlacitko prihlasenie_stranka_formular_tlacitko_prih" type="submit">Prihlasenie</button>
                    </form>
                    <form action="{{ route('registracia') }}" method="GET">
                        <button class="prihlasenie_stranka_formular_tlacitko prihlasenie_stranka_formular_tlacitko_reg" type="submit">Vytvor účet</button>
                    </form>
                </div>
            </div>
        </div>


</div>

<script>
    const nameChars = /^[a-zA-Z0-9]+$/;
    const emailRegex = /^\S+@\S+\.\S+$/;

    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    document.getElementById('prihlasenie_straka_formular').addEventListener('submit', function(event) {
        let inputEmail = emailInput.value;
        let inputPassword = passwordInput.value;

        if (inputEmail == '' && inputPassword == '') {
            alert('Email a heslo neboli vyplnene.');
            event.preventDefault();
        }else{
            if(!emailRegex.test(inputEmail)){
                alert('Zadany email nema format emailu.');
                event.preventDefault();
            }else if (inputPassword.length < 8 || !nameChars.test(inputPassword)){
                alert('Heslo musi obsahovat len pismena a cisla. \nHeslo musi pozostavat z aspon 8 znakov.');
                event.preventDefault();
            }
        }
    });

    passwordInput.addEventListener('keydown', function(event) {
        // Check if Enter key is pressed
        if (event.key === 'Enter') {
            const myForm = document.getElementById('prihlasenie_straka_formular');
            myForm.submit();
        }
    });

</script>

</body>
</html>
