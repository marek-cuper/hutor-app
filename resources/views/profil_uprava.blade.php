@include('include.head')

<body>

@include('include.navbar')

<div class="domov_prispevok">
    <div class="pridaj_prispevok_telo" onclick="">
        <a class="profil_uprava_meno" id="profil_uprava_meno">
            <h3 id="profil_meno" >Admin</h3>
        </a>
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
        <div class="profil_uprava_kontajner">
            <div class="profil_uprava_kontajner_cast">
                <div class="profil_uprava_profil_obrazok">
                    <img id="profil_obrazok" src="">
                </div>
                <form id="profil_uprava_obrazok_formular" class="profil_uprava_obrazok_formular" action="{{ route('uloz_obrazok.post') }}" method="post">
                    @csrf
                    <h4>Vyber profilovu fotku</h4>
                    <input type="file" name="profil_obrazok_vstup" id="profil_obrazok_vstup" class="form-control-file">
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    <div class="preferencie_tlacitka_okno">
                        <button type="submit" form="profil_uprava_obrazok_formular" class="preferencie_tlacitko_uloz">Uloz</button>
                        <button onclick="resetFunction()" class="preferencie_tlacitko_reset">Reset</button>
                    </div>
                </form>
            </div>
            <form id="profil_uprava_profil_text_formular" class="profil_uprava_profil_text_formular" action="{{ route('nastevenie_udajov.post') }}" method="post">
                @csrf
                <h4>Zmena mena, emailu a hesla</h4>
                <div class="profil_uprava_profil_text_formular_napoveda">
                    <p><b>Nápoveda</b></p>
                    <p>Pre ktorúkoľvek zmenu je potrebné zadať staré/aktuálne heslo.</p>
                    <p>Je potrebné vyplniť len tie polička ktoré chcete zmeniť.</p>
                </div>

                <div class="profil_uprava_profil_text_vstup">
                    <p>Zadaj nove meno</p>
                    <input type="text" id="name_input" name="name">
                    <button type="button" onclick="checkName()">Overit</button>
                    <i id="nameI" class="fa fa-question fa-2x"></i>
                </div>
                <div class="profil_uprava_profil_text_vstup">
                    <p>Zadaj novy email</p>
                    <input type="text" id="email_input" name="email">
                    <button type="button" onclick="checkEmail()">Overit</button>
                    <i id="emailI" class="fa fa-question fa-2x"></i>
                </div>
                <div class="profil_uprava_profil_text_vstup">
                    <p>Zadaj nove heslo</p>
                    <input type="password" placeholder="Nove heslo" id="new_password1_input" name="new_password1">
                    <button type="button" onclick="checkPassword1()">Overit</button>
                    <i id="password1I" class="fa fa-question fa-2x"></i>
                </div>
                <div class="profil_uprava_profil_text_vstup">
                    <p>Zadaj znova nove heslo</p>
                    <input type="password" placeholder="Nove heslo" id="new_password2_input" name="new_password2">
                    <button type="button" onclick="checkPassword2()">Overit</button>
                    <i id="password2I" class="fa fa-question fa-2x"></i>
                </div>
                <div style="margin-top: 5%; margin-bottom: 0;" class="profil_uprava_profil_text_vstup">
                    <p>Zadaj stare heslo</p>
                    <input type="password" placeholder="Stare heslo" id="old_password_input" name="old_password" required>
                </div>
                <div style="margin-bottom: 3%" class="preferencie_tlacitka_okno">
                    <button type="submit" form="profil_uprava_profil_text_formular" class="preferencie_tlacitko_uloz">Uloz</button>
                </div>
            </form>
            <div class="empty_space"></div>

        </div>

    </div>
</div>

@include('include.footbar')

<script>
    var hiddenImageNameInput;

    var user = @json(session('user'));

    const profileName = document.getElementById('profil_meno');
    const profileImageImg = document.getElementById('profil_obrazok');
    const profileImageInput = document.getElementById('profil_obrazok_vstup');
    const formProfileImageInput = document.getElementById('profil_uprava_obrazok_formular');

    const nameI = document.getElementById('nameI');
    const emailI = document.getElementById('emailI');
    const password1I = document.getElementById('password1I');
    const password2I = document.getElementById('password2I');

    const nameChars = /^[a-zA-Z0-9]+$/;
    const emailRegex = /^\S+@\S+\.\S+$/;

    const nameInput = document.getElementById('name_input');
    const emailInput = document.getElementById('email_input');
    const newPassword1Input = document.getElementById('new_password1_input');
    const newPassword2Input = document.getElementById('new_password2_input');
    const oldPasswordInput = document.getElementById('old_password_input');

    document.addEventListener('DOMContentLoaded', function() {
        profileName.textContent = user.name;

        profileImageImg.src = '/storage/' + user.image_name;

        hiddenImageNameInput = document.createElement('input');
        hiddenImageNameInput.value = user.image_name;
        hiddenImageNameInput.name = 'profile_image_name';
        hiddenImageNameInput.type = 'text';
        hiddenImageNameInput.style.display = 'none';
        formProfileImageInput.appendChild(hiddenImageNameInput);

        nameInput.placeholder = user.name;
        emailInput.placeholder = user.email;

        var nameLink = document.getElementById('profil_uprava_meno');
        nameLink.href = '/profil/' + user.id;

    });

    $('#profil_obrazok_vstup').on('input', function(event) {
        event.preventDefault(); // Prevent the default button click behavior

        if (profileImageInput.value.trim() !== '') {
            var imageData = $('#profil_obrazok_vstup')[0].files[0]; // Get the selected image file

            var formData = new FormData(); // Create FormData object
            formData.append('image', imageData); // Append the image file to FormData

            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            // Set the CSRF token in the request headers
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            // Make AJAX request
            $.ajax({
                url: '/profil_uprava/pridaj_obrazok', // Replace with your server endpoint
                method: 'POST',
                data: formData,
                contentType: false, // Important: set contentType to false
                processData: false, // Important: set processData to false
                success: function(response) {
                    // Handle success response
                    profileImageImg.src = '/storage/' + response.imageName;
                    hiddenImageNameInput.value = response.imageName;
                },
                error: function(error) {
                    // Handle error
                    alert('Error');
                }
            });
        }
    });

    function resetFunction(){
        profileImageImg.src = '/storage/' + user.image_name;
        hiddenImageNameInput.value = user.image_name;
    }

    function checkName(){
        let input = nameInput.value;

        if(nameChars.test(input) && input.length >= 5){
            $.ajax({
                url: '/profil_uprava/overenie_meno', // Replace with your server endpoint
                method: 'POST',
                data: { name: input, _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if(response.unique){
                        nameI.className = 'fa fa-check fa-2x';
                    }else{
                        nameI.className = 'fa fa-times fa-2x';
                        alert(response.text);
                    }

                },
                error: function (error) {
                    console.error('Error:', error);
                }
            });

        }else {
            nameI.className = 'fa fa-times fa-2x';
            alert('Meno musi obsahovat len pismena a cisla. \nMeno musi pozostavat z aspon 5 znakov.');
        }

    }

    function checkEmail(){
        let input = emailInput.value;

        if(emailRegex.test(input)){
            $.ajax({
                url: '/profil_uprava/overenie_email', // Replace with your server endpoint
                method: 'POST',
                data: { email: input, _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if(response.unique){
                        emailI.className = 'fa fa-check fa-2x';
                    }else{
                        emailI.className = 'fa fa-times fa-2x';
                        alert(response.text);
                    }

                },
                error: function (error) {
                    console.error('Error:', error);
                }
            });

        }else {
            emailI.className = 'fa fa-times fa-2x';
            alert('Zadany email nema format emailu.');
        }
    }

    function checkPassword1(){
        let input = newPassword1Input.value;

        if(nameChars.test(input) && input.length >= 8){
            password1I.className = 'fa fa-check fa-2x';
        }else {
            password1I.className = 'fa fa-times fa-2x';
            alert('heslo musi obsahovat len pismena a cisla. \nHeslo musi pozostavat z aspon 8 znakov.');
        }
    }

    function checkPassword2(){
        let input1 = newPassword1Input.value;
        let input2 = newPassword2Input.value;

        if(input1 == input2){
            password2I.className = 'fa fa-check fa-2x';
        }else {
            password2I.className = 'fa fa-times fa-2x';
            alert('Opakovane heslo sa nezhoduje s prvym novym');
        }
    }

    document.getElementById('profil_uprava_profil_text_formular').addEventListener('submit', function(event) {
        let inputName = nameInput.value;
        let inputEmail = emailInput.value;
        let inputNewPassword1 = newPassword1Input.value;
        let inputNewPassword2 = newPassword2Input.value;

        if (inputName == '' && inputEmail == '' && inputNewPassword1 == '') {
            alert('Meno, email a nove heslo neboli vyplnene.\nJe potrebne vyplnit aspon jedno.');
            event.preventDefault();
        }else{
            if ((0 < inputName.length && inputName.length < 5)) {
                if(!nameChars.test(inputName)){
                    alert('Meno musi obsahovat len pismena a cisla. \nMeno musi pozostavat z aspon 5 znakov.');
                    event.preventDefault();
                }
            }else if(inputEmail != ''){
                if(!emailRegex.test(inputEmail)){
                    alert('Zadany email nema format emailu.');
                    event.preventDefault();
                }
            }else if ( 0 < inputNewPassword1.length && inputNewPassword1.length < 8){
                if(!nameChars.test(inputNewPassword1)){
                    alert('Heslo musi obsahovat len pismena a cisla. \nHeslo musi pozostavat z aspon 8 znakov.');
                    event.preventDefault();
                }
            }else if(inputNewPassword1 !== inputNewPassword2){
                alert('Nove heslo sa nezhoduje s zopakovanym novym heslom.');
                event.preventDefault();
            }
        }
    });

    oldPasswordInput.addEventListener('keydown', function(event) {
        // Check if Enter key is pressed
        if (event.key === 'Enter') {
            const myForm = document.getElementById('profil_uprava_profil_text_formular');
            myForm.submit();
        }
    });


</script>

</body>
</html>
