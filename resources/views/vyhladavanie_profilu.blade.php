@include('include.head')

<body>

@include('include.navbar')

<div class="moderator_panel_telo">
    <h2>Vyhladavanie</h2>
    <div class="moderator_panel_telo_cast">
        <h4 id="find_text_h4"></h4>
        <div id="moderator_panel_telo_tabulka" class="moderator_panel_telo_tabulka">

        </div>
    </div>
    <div class="moderator_panel_telo_cast">

    </div>
</div>

@include('include.footbar')

<script>

    var find = @json(session('find'));
    var find_text = @json(session('find_text'));
    var find_user = @json(session('find_users'));

    var usersTable = document.getElementById('moderator_panel_telo_tabulka');
    var findTextH4 = document.getElementById('find_text_h4');

    document.addEventListener('DOMContentLoaded', function() {
        if(find){
            findTextH4.textContent = "Vyhladávaný text: '" + find_text + "'";
            for (let i = 0; i < find_user.length; i++) {
                createUser(i);
            }
        }else {
            findTextH4.textContent = "Vyhladávaný text sa nepodarilo vyhladať... ";
        }

    });

    function createUser($index){
        const moderatorDiv = document.createElement('div');
        moderatorDiv.className = 'moderator_panel_telo_tabulka_riadok';
        usersTable.appendChild(moderatorDiv);

        const obrazokDiv = document.createElement('a');
        obrazokDiv.className = 'domov_zobrazenie_komentare_komentar_obrazok';
        obrazokDiv.href = '/profil/' + find_user[$index].id;
        const obrazokImage = document.createElement('img');
        obrazokImage.src = '/storage/' + find_user[$index].image_name;
        obrazokDiv.appendChild(obrazokImage);
        moderatorDiv.appendChild(obrazokDiv);

        const meno = document.createElement('p');
        meno.textContent = find_user[$index].name;
        moderatorDiv.appendChild(meno);
        }

</script>

</body>
</html>
