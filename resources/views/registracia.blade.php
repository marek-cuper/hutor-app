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

                    <form id="prihlasenie_straka_formular" action="{{ route('registracia.post') }}" method="POST">
                        @csrf
                        <div class="prihlasenie_formular_kolonka">
                            <label title="Meno musi obsahovat aspon 5 znakov(len cisla a pismena)"><b>Meno</b></label>
                            <input type="text" placeholder="Zadaj Meno" id="name" name="name" required>
                        </div>
                        <div class="prihlasenie_formular_kolonka">
                            <label><b>Email</b></label>
                            <input type="text" placeholder="Zadaj Email" id="email" name="email" required>
                        </div>
                        <div class="prihlasenie_formular_kolonka">
                            <label title="Heslo musi obsahovat aspon 8 znakov(len cisla a pismena)"><b>Heslo</b></label>
                            <input type="password" placeholder="Zadaj Heslo" id="password1" name="password1" required>
                        </div>
                        <div class="prihlasenie_formular_kolonka">
                            <label title="Opakovane heslo sa musi rovnat s heslom"><b>Heslo</b></label>
                            <input type="password" placeholder="Zadaj Heslo znova" id="password2" name="password2" required>
                        </div>


                        <button class="prihlasenie_stranka_formular_tlacitko prihlasenie_stranka_formular_tlacitko_prih" type="submit">Registracia</button>
                    </form>
                    <form action="{{ route('prihlasenie') }}" method="GET">
                        <button class="prihlasenie_stranka_formular_tlacitko prihlasenie_stranka_formular_tlacitko_reg" type="submit">Uz som zaregistrovany</button>
                    </form>
                </div>
            </div>
        </div>

</div>

<script>
    const nameChars = /^[a-zA-Z0-9]+$/;
    const emailRegex = /^\S+@\S+\.\S+$/;

    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const newPassword1Input = document.getElementById('password1');
    const newPassword2Input = document.getElementById('password2');

    document.getElementById('prihlasenie_straka_formular').addEventListener('submit', function(event) {
        let inputName = nameInput.value;
        let inputEmail = emailInput.value;
        let inputNewPassword1 = newPassword1Input.value;
        let inputNewPassword2 = newPassword2Input.value;

        if (inputName == '' || inputEmail == '' || inputNewPassword1 == '') {
            alert('Meno, email a heslo neboli vyplnene.');
            event.preventDefault();
        }else{
            if ((inputName.length < 5 || !nameChars.test(inputName))) {
                alert('Meno musi obsahovat len pismena a cisla. \nMeno musi pozostavat z aspon 5 znakov.');
                event.preventDefault();

            } else if(!emailRegex.test(inputEmail)){
                alert('Zadany email nema format emailu.');
                event.preventDefault();
            }else if ( inputNewPassword1.length < 8 || !nameChars.test(inputNewPassword1)){
                alert('Heslo musi obsahovat len pismena a cisla. \nHeslo musi pozostavat z aspon 8 znakov.');
                event.preventDefault();
            }else if(inputNewPassword1 !== inputNewPassword2){
                alert('Heslo sa nezhoduje s zopakovanym heslom.');
                event.preventDefault();
            }
        }
    });

    newPassword2Input.addEventListener('keydown', function(event) {
        // Check if Enter key is pressed
        if (event.key === 'Enter') {
            const myForm = document.getElementById('prihlasenie_straka_formular');
            myForm.submit();
        }
    });

</script>

</body>
</html>
