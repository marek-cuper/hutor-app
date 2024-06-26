@include('include.head')

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
                <div class="profil_uprava_profil_meno">
                    <label>Meno:</label>
                    <h2 id="profil_meno"></h2>
                </div>

            </div>
            <div id="profil_kontajner_statistiky" class="profil_kontajner_statistiky">
            </div>

        </div>

    </div>
</div>

@include('include.footbar')

<script>
    var user = @json(session('user'));
    var another_user = @json($data['another_user']);

    var numberOfPosts = @json($data['numberOfPosts']);
    var numberOfComments = @json($data['numberOfComments']);
    var numberOfPostsUpVotes = @json($data['numberOfPostsUpVotes']);
    var numberOfPostsDownVotes = @json($data['numberOfPostsDownVotes']);
    var numberOfCommentsUpVotes = @json($data['numberOfCommentsUpVotes']);
    var numberOfCommentsDownVotes = @json($data['numberOfCommentsDownVotes']);
    var numberOfMessages = @json($data['numberOfMessages']);

    let profilName = document.getElementById('profil_meno');
    let profilImage = document.getElementById('profil_obrazok');
    let statsContainerDiv = document.getElementById('profil_kontajner_statistiky');

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

        if(user_privilege > 0 || user.id == another_user.id){
            const banDiv = document.createElement('div');
            banDiv.className = 'domov_zobrazenie_komentare_pridaj_kontajner';
            panel.appendChild(banDiv);

            const banIkona = document.createElement('i');
            banIkona.className = 'fa fa-trash fa-2x';
            banDiv.appendChild(banIkona);

            banDiv.addEventListener("click", function() {
                deleteUser();
            });
        }

        //Adding stats
        createStat('Prispevky', numberOfPosts);
        createStat('Komentáre', numberOfComments);
        createStat('Poslané správy', numberOfMessages);
        createStat('Hlas za prispevok', numberOfPostsUpVotes);
        createStat('Hlas proti prispevok', numberOfPostsDownVotes);
        createStat('Hlas za komentár', numberOfCommentsUpVotes);
        createStat('Hlas proti komentár', numberOfCommentsDownVotes);
    });

    function openChat(){
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

    function deleteUser(){
        var result = window.confirm("Ste si isty zmazanim pouzivatela?");

        if (result) {
            var user_id = another_user.id;

            $.ajax({
                url: '/pouzivatel/vymaz',
                method: 'POST',
                data: { user_id: user_id, _token: '{{ csrf_token() }}' },
                success: function () {
                    window.location.href = '/domov';
                },
                error: function (error) {
                    console.error('Error:', error);
                }
            });
        }

    }

    function createStat(text, number) {
        var statDiv = document.createElement('div');
        statDiv.classList.add('profil_kontajner_statistiky_okno');
        statsContainerDiv.appendChild(statDiv);

        var labelText = document.createElement('label');
        labelText.textContent = text;
        statDiv.appendChild(labelText);

        var h3Number = document.createElement('h3');
        h3Number.textContent = number;
        statDiv.appendChild(h3Number);
    }

</script>

</body>
</html>
