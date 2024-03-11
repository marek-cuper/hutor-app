<!DOCTYPE html>
<html lang="en">
<head>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset("/css/main.css")}}" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>Title</title>

</head>

<body>

@include('include.navbar')

<div class="moderator_panel_telo">
    <h2>Moderátorsky panel</h2>
    <div class="moderator_panel_telo_cast">
        <h4>Moderatori</h4>
        <div id="moderator_panel_telo_tabulka" class="moderator_panel_telo_tabulka">

        </div>
    </div>
    <div class="moderator_panel_telo_cast">

    </div>
</div>

@include('include.footbar')

<script>
    var modsTable = document.getElementById('moderator_panel_telo_tabulka');

    var user_privilege = @json(session('privileges'));

    var mods = @json(session('mods'));
    var mods_user = @json(session('mods_user'));


    document.addEventListener('DOMContentLoaded', function() {
        for (let i = 0; i < mods_user.length; i++) {
            createModerator(i);
        }
    });

    function createModerator($index){
        const moderatorDiv = document.createElement('div');
        moderatorDiv.className = 'moderator_panel_telo_tabulka_riadok';
        modsTable.appendChild(moderatorDiv);

        const obrazokDiv = document.createElement('a');
        obrazokDiv.className = 'domov_zobrazenie_komentare_komentar_obrazok';
        obrazokDiv.href = '/profil/' + mods_user[$index].id;
        const obrazokImage = document.createElement('img');
        obrazokImage.src = '/storage/' + mods_user[$index].image_name;
        obrazokDiv.appendChild(obrazokImage);
        moderatorDiv.appendChild(obrazokDiv);

        const meno = document.createElement('p');
        meno.textContent = mods_user[$index].name;
        moderatorDiv.appendChild(meno);

        const rola = document.createElement('p');
        if(mods[$index].admin){
            rola.textContent = 'Admin';
        }else{
            rola.textContent = 'Moderator';
        }
        moderatorDiv.appendChild(rola);

        if(user_privilege === 2 && !mods[$index].admin){
            const zrusIkonaDiv = document.createElement('div');
            zrusIkonaDiv.className = 'domov_prispevok_prieskum_ikona';
            const zrusIkona = document.createElement('i');
            zrusIkona.className = 'fa fa-times fa-2x';
            zrusIkonaDiv.appendChild(zrusIkona);
            moderatorDiv.appendChild(zrusIkonaDiv);
            zrusIkonaDiv.addEventListener("click", function() {
                removeMod(mods[$index].user_id);
            });
        }

        function removeMod(user_id){

            $.ajax({
                url: '/moderator/odober',
                method: 'POST',
                data: { user_id: user_id, _token: '{{ csrf_token() }}' },
                success: function (response) {
                    window.location.href = '/moderator/panel';
                },
                error: function (error) {
                    console.error('Error:', error);
                }
            });
        }


    }
</script>

</body>
</html>
