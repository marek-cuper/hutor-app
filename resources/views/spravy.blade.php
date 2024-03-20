@include('include.head')

<body>

@include('include.navbar')
<div id="domov_tlacitko_nazad" onclick="returnToConversations()">
    <i class="fa fa-angle-double-left  fa-3x"></i>
</div>

<div id="spravy_kontajner_konverzacie" class="spravy_kontajner_konverzacie">
    <div class="spravy_kontajner_konverzacie_telo">
        <div id="spravy_kontajner_konverzacie_zoznam" class="spravy_kontajner_konverzacie_zoznam">

        </div>
    </div>
</div>
<div id="spravy_kontajner_zobrazena_konverzacia" class="spravy_kontajner_zobrazena_konverzacia">
    <div class="spravy_zobrazena_konverzacia_telo">
        <div class="spravy_zobrazena_konverzacia_telo_profil">
            <a id="another_profil_a" href="">
                <img id="another_profil_img">
            </a>
            <h2 id="another_profil_h"></h2>
        </div>
        <div id="spravy_zobrazena_konverzacia_telo_spravy" class="spravy_zobrazena_konverzacia_telo_spravy">

        </div>
        <div class="domov_zobrazenie_komentare_pridaj_kontajner">
            <input id="text_input">
            <i class="fa fa-paper-plane fa-1x" onclick="sendMessage()"></i>
        </div>
    </div>
</div>

@include('include.footbar')


<script>
    var user = @json(session('user'));

    var conversations = @json(session('conversations'));
    var conversationsMessage = @json(session('conversations_first_message'));
    var conversationsUser = @json(session('conversations_user'));

    var another_user = @json(session('another_user'));
    var show_conversation = @json(session('conversation_show'));
    var show_messages = @json(session('conversation_messages'));

    const buttonBack = document.getElementById('domov_tlacitko_nazad');
    let conversationsPage = document.getElementById('spravy_kontajner_konverzacie');
    let messagesPage = document.getElementById('spravy_kontajner_zobrazena_konverzacia');

    let anotherUserProfilA = document.getElementById('another_profil_a');
    let anotherUserProfilImg = document.getElementById('another_profil_img');
    let anotherUserProfilH = document.getElementById('another_profil_h');

    let conversationsContainer = document.getElementById('spravy_kontajner_konverzacie_zoznam');
    let messagesContainer = document.getElementById('spravy_zobrazena_konverzacia_telo_spravy');
    let inputTextMessage = document.getElementById('text_input');



    function returnToConversations(){
        $.ajax({
            url: '/spravy/konverzacie/nacitanie',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function (response) {
                conversations = response.conversations;
                conversationsMessage = response.conversations_first_message;
                conversationsUser = response.conversations_user;
                loadConversations();
                conversationsPage.style.display = 'inline';
                messagesPage.style.display = 'none';
                buttonBack.style.display = 'none';
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });

    }

    document.addEventListener('DOMContentLoaded', function() {
        showConversation(show_conversation);

    });

    function openConversation($conv_id){
        $.ajax({
            url: '/spravy/konverzacia_id',
            method: 'POST',
            data: { conv_id: $conv_id, _token: '{{ csrf_token() }}' },
            success: function (response) {
                show_messages = response.conversation_messages;
                another_user = response.another_user;
                showConversation(true);

            },
            error: function (error) {
                console.error('Error:', error);
            }
        });

    }

    function showConversation($show){
        if($show){
            anotherUserProfilA.href = '/profil/' + another_user.id;
            anotherUserProfilImg.src = '/storage/' + another_user.image_name;
            anotherUserProfilH.textContent = another_user.name;

            while (messagesContainer.firstChild) {
                messagesContainer.removeChild(messagesContainer.firstChild);
            }
            if(show_messages !== null){
                for (let i = 0; i < show_messages.length; i++) {
                    createMessage(show_messages[i].sender_id, show_messages[i].text)
                }
            }
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        if($show){
            conversationsPage.style.display = 'none';
            messagesPage.style.display = 'inline';
            buttonBack.style.display = 'flex';
        }else {
            conversationsPage.style.display = 'inline';
            messagesPage.style.display = 'none';
            buttonBack.style.display = 'none';

            loadConversations();
        }
    }
    function loadConversations(){
        while (conversationsContainer.firstChild) {
            conversationsContainer.removeChild(conversationsContainer.firstChild);
        }
        for (let i = 0; i < conversations.length; i++) {
            createConversation(i)
        }
    }

    function createConversation($index){
        const konverzacia = document.createElement('div');
        konverzacia.className = 'domov_zobrazenie_komentare_komentar';
        conversationsContainer.appendChild(konverzacia);
        konverzacia.style.marginBottom = '3%';

        const komentarVrch = document.createElement('div');
        komentarVrch.className = 'domov_zobrazenie_komentare_komentar_vrch';
        konverzacia.appendChild(komentarVrch);

        const konverzaciaObrazokDiv = document.createElement('a');
        //konverzaciaObrazokDiv.href = '/profil/' + profile_id;
        konverzaciaObrazokDiv.className = 'domov_zobrazenie_komentare_komentar_obrazok';
        komentarVrch.appendChild(konverzaciaObrazokDiv);
        const konverzaciaObrazokImg = document.createElement('img');
        konverzaciaObrazokImg.src = '/storage/' + conversationsUser[$index].image_name;
        konverzaciaObrazokDiv.appendChild(konverzaciaObrazokImg);

        const konverzaciaTelo = document.createElement('div');
        konverzaciaTelo.className = 'domov_zobrazenie_komentare_komentar_telo';
        komentarVrch.appendChild(konverzaciaTelo);

        const komentarTeloMeno = document.createElement('p');
        komentarTeloMeno.textContent = conversationsUser[$index].name;
        komentarTeloMeno.className = 'domov_zobrazenie_komentare_komentar_meno';
        konverzaciaTelo.appendChild(komentarTeloMeno);

        const komentarTeloText = document.createElement('p');
        let text = conversationsMessage[$index].text;
        if(text.length > 50){
            komentarTeloText.textContent = text.substring(0, 50) + '...';
        }else {
            komentarTeloText.textContent = text;
        }
        komentarTeloText.className = 'domov_zobrazenie_komentare_komentar_text';
        konverzaciaTelo.appendChild(komentarTeloText);
        if(user.id == conversations[$index].user1_id){
            if(!conversations[$index].user1_openned){
                komentarTeloText.style.fontWeight = "bold";
            }
        } else {
            if(!conversations[$index].user2_openned){
                komentarTeloText.style.fontWeight = "bold";
            }
        }


        konverzacia.addEventListener("click", function() {
            openConversation(conversations[$index].id);
        });
    }

    inputTextMessage.addEventListener('keydown', function(event) {
        // Check if Enter key is pressed
        if (event.key === 'Enter') {
            sendMessage(); // Call sendMessage function
        }
    });

    function createMessage($user_id, $text){
        const spravaTelo = document.createElement('div');
        spravaTelo.className = 'spravy_zobrazena_konverzacia_telo_sprava';
        messagesContainer.appendChild(spravaTelo);

        if(user.id === $user_id){
            spravaTelo.style.marginLeft = 'auto';
            spravaTelo.style.backgroundColor = 'lightgray';
        }

        const spravaTeloP = document.createElement('p');
        spravaTeloP.textContent = $text;
        spravaTelo.appendChild(spravaTeloP);
    }

    function sendMessage(){
        if(inputTextMessage.value !== ''){

            $.ajax({
                url: '/spravy/konverzacia/posli_spravu',
                method: 'POST',
                data: { sender_id: user.id, receiver_id: another_user.id, message_text: inputTextMessage.value, _token: '{{ csrf_token() }}' },
                success: function (response) {
                    createMessage(user.id, inputTextMessage.value);
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    inputTextMessage.value = '';
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
