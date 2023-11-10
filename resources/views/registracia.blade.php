<!DOCTYPE html>
<html lang="en">
<head>

    <link href="{{ asset("/css/main.css")}}" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>Title</title>

</head>

<body>

@auth
    <script>window.location = "{{ route('domov') }}";</script>
@endauth

<div class="prihlasenie_stranka">

    <div class="prihlasenie_stranka_idea">
        <img src="images/hutor_cierne_cierne.png" alt="">
    </div>

    <div class="prihlasenie_stranka_formular">
        <div class="prihlasenie_formular">

            <form action="{{ route('registracia.post') }}" method="POST">
                @csrf
                <div class="prihlasenie_formular_kolonka">
                    <label><b>Name</b></label>
                    <input type="text" placeholder="Zadaj Meno" id="name" name="name" required>
                </div>
                <div class="prihlasenie_formular_kolonka">
                    <label><b>Email</b></label>
                    <input type="text" placeholder="Zadaj Email" id="email" name="email" required>
                </div>
                <div class="prihlasenie_formular_kolonka">
                    <label><b>Password</b></label>
                    <input type="password" placeholder="Zadaj Heslo" id="password" name="password" required>
                </div>


                <button class="prihlasenie_stranka_formular_tlacitko_prih" type="submit">Registracia</button>
            </form>
            <form action="{{ route('prihlasenie') }}" method="GET">
                <button class="prihlasenie_stranka_formular_tlacitko_reg" type="submit">Uz som zaregistrovany</button>
            </form>
        </div>
    </div>

</div>

</body>
</html>
