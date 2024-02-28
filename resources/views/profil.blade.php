<!DOCTYPE html>
<html lang="en">
<head>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset("/css/main.css")}}" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>Title</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>

<body>

@include('include.navbar')

<div class="domov_prispevok">
    <div class="pridaj_prispevok_telo" onclick="">
        <div class="profil_uprava_kontajner">
            <div class="profil_uprava_kontajner_cast">
                <div class="profil_uprava_profil_obrazok">
                    <img id="profil_obrazok" src="/storage/{{ $user->image_name }}">
                </div>
                <div class="profil_uprava_profil_obrazok_vstup">
                    <label>Meno:</label>
                    <label><b>{{ $user->name }}</b></label>
                </div>

            </div>

        </div>

    </div>
</div>

@include('include.footbar')

<script>

</script>

</body>
</html>
