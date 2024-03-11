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
            <div class="profil_kontajner_panel">
            </div>
            <div class="profil_uprava_kontajner_cast">
                <div class="profil_uprava_profil_obrazok">
                    <img id="profil_obrazok">
                </div>
                <div class="profil_uprava_profil_obrazok_vstup">
                    <label>Meno:</label>
                    <label id="profil_meno"></label>
                </div>

            </div>

        </div>

    </div>
</div>

@include('include.footbar')

<script>
    var user = @json(session('user'));
    var another_user = @json($data['another_user']);;

    let profilName = document.getElementById('profil_meno');
    let profilImage = document.getElementById('profil_obrazok');

    var user_privilege = @json(session('privileges'));
    var mods = @json(session('mods'));


    document.addEventListener('DOMContentLoaded', function() {
        profilName.textContent = another_user.name;
        profilName.style.fontWeight = "bold";
        profilImage.src = '/storage/' + another_user.image_name;
        var panel = document.querySelector('.profil_kontajner_panel');
        let moderator = false;
        for (let i = 0; i < mods.length; i++) {
            if(mods[i].user_id == another_user.id){
                moderator = true;
            }
        }

        if(user_privilege == 2 && !moderator){
            const modDiv = document.createElement('div');
            modDiv.className = 'domov_zobrazenie_komentare_pridaj_kontajner';
            panel.appendChild(modDiv);

            const modIkona = document.createElement('i');
            modIkona.className = 'fa fa-cogs fa-2x';
            modDiv.appendChild(modIkona);

            modDiv.addEventListener("click", function() {
                addMod();
            });
        }

        if(user.id != another_user.id){
            const spravyDiv = document.createElement('div');
            spravyDiv.className = 'domov_zobrazenie_komentare_pridaj_kontajner';
            panel.appendChild(spravyDiv);

            const spravyIkona = document.createElement('i');
            spravyIkona.className = 'fa fa-comments-o fa-2x';
            spravyDiv.appendChild(spravyIkona);

            spravyDiv.addEventListener("click", function() {
                openChat();
            });
        }
        if(user.id == 1){
            const banDiv = document.createElement('div');
            banDiv.className = 'domov_zobrazenie_komentare_pridaj_kontajner';
            panel.appendChild(banDiv);

            const banIkona = document.createElement('i');
            banIkona.className = 'fa fa-ban fa-2x';
            banDiv.appendChild(banIkona);
        }
    });

    function openChat(){
        //After click to post disable scrolling
        var user_id = another_user.id;

        $.ajax({
            url: '/spravy/konverzacia_pouzivatel',
            method: 'POST',
            data: { user_id: user_id, _token: '{{ csrf_token() }}' },
            success: function (response) {
                window.location.href = '/spravy';

            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }

    function addMod(){
        //After click to post disable scrolling
        var user_id = another_user.id;

        $.ajax({
            url: '/moderator/pridaj',
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

</script>

</body>
</html>
