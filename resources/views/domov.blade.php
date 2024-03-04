<!DOCTYPE html>
<html lang="en">
<head>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset("/css/main.css")}}" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
    </script>
    <title>Title</title>

</head>

<body>
@include('include.navbar')

<div id="domov_tlacitko_nazad" onclick="returnHome()">
    <i class="fa fa-angle-double-left  fa-3x"></i>
</div>

<div id="domov_kontajner_prispevky" class="domov_kontajner_prispevky">

</div>
<div id="domov_zobrazeny_prispevok" class="domov_zobrazeny_prispevok">

</div>


@include('include.footbar')

<script>

    // Listen for the scroll event
    let canScroll = true;
    let index = 0;
    var user_name = @json(session('user_name'));
    var user_profile_id = @json(session('user_profile_id'));
    var user_profile_image = @json(session('user_profile_image'));

    var posts = @json(session('posts'));
    var posts_images = @json(session('posts_images'));
    var posts_tags = @json(session('posts_tags'));
    var posts_regions = @json(session('posts_regions'));

    var show_posts_images = [];
    var show_post_poll_options_image = [];
    var show_post_poll_options_text = [];
    var show_user_poll_option_number;
    var show_poll_option_votes = [];
    // "","+","-"
    var show_post_vote_status = "";
    var show_post_up_voted;
    var show_post_down_voted;
    var show_post_openned;
    var show_comment_profile_id;
    var show_comment_id;
    var show_comment_upper_id;
    var show_comment_image;
    var show_comment_user_name;
    var show_comment_text;
    var show_comment_up_vote;
    var show_comment_down_vote;
    var show_comment_user_voted;


    var tags = @json(session('tags'));
    var regions = @json(session('regions'));

    var loadedPosts = [];
    const buttonBack = document.getElementById('domov_tlacitko_nazad');
    const homeContainer = document.getElementById('domov_kontajner_prispevky');
    let showContainer = document.getElementById('domov_zobrazeny_prispevok');

    let scrollListener = true;

    let showPostImages = [];
    let showPostImagesIndex = 0;
    let showPostImagesLeftButton;
    let showPostImagesRightButton;

    let showPostChosoenPollOption;
    let showPostPollOptions = [];

    let showPostStats = [];

    let showPostCommentsDiv;
    let showPostComments = [];

    function returnHome(){
        buttonBack.style.display = "none";
        showContainer.innerHTML = "";
        showPostImagesIndex = 0;
        showPostImages = [];
        showPostPollOptions = [];
        showPostStats = [];
        showPostComments = [];

        homeContainer.style.display = "inline";
        scrollListener = true;
    }

    function setDataShowPost(){
        //After click to post disable scrolling
        scrollListener = false;
        var post_id = posts[index].id;

        $.ajax({
            url: '/domov/zobrazenie',
            method: 'POST',
            data: { post_id: post_id, _token: '{{ csrf_token() }}' },
            success: function (response) {
                show_posts_images = response.show_posts_images;
                show_post_poll_options_image = response.post_poll_options_images;
                show_post_poll_options_text = response.post_poll_options_text;
                show_user_poll_option_number = response.user_poll_option_number;
                show_poll_option_votes = response.poll_option_votes;
                show_post_vote_status = response.post_vote_status;
                show_post_up_voted = response.poll_up_votes;
                show_post_down_voted = response.poll_down_votes;
                show_post_openned = response.poll_oppened;
                show_comment_profile_id = response.comment_profile_id;
                show_comment_id = response.comment_id;
                show_comment_upper_id = response.comment_upper_id;
                show_comment_image = response.comment_image;
                show_comment_user_name = response.comment_user_name;
                show_comment_text = response.comment_text;
                show_comment_up_vote = response.comment_up_votes;
                show_comment_down_vote = response.comment_down_votes;
                show_comment_user_voted = response.comment_user_voted;
                showPost();
                buttonBack.style.display = "flex";

            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }

    function showPost(){
        //Update part
        createShowingPost();

        //Showing part
        homeContainer.style.display = "none";
    }

    function createShowingPost(){
        let postIndex = parseInt(index);
        const post = posts[postIndex];

        // Create the main container div
        const containerDiv = document.createElement('div');
        containerDiv.id = 'domov_zobrazenie_telo' + post.id;
        containerDiv.className = 'domov_zobrazenie_telo';

        showContainer.appendChild(containerDiv);


// Create the nadpis (title) div
        const nadpisDiv = document.createElement('div');
        nadpisDiv.className = 'domov_nadpis_prispevok';
        const nadpisParagraph = document.createElement('p');
        nadpisParagraph.id = 'nadpis' + post.id;
        nadpisParagraph.textContent = post.title;
        nadpisDiv.appendChild(nadpisParagraph);

        containerDiv.appendChild(nadpisDiv);

// Create the popis (description) div
        const popisDiv = document.createElement('div');
        popisDiv.className = 'domov_popis_prispevok';
        const textParagraph = document.createElement('p');
        textParagraph.id = 'text' + post.id;
        textParagraph.textContent = post.text;
        popisDiv.appendChild(textParagraph);

        containerDiv.appendChild(popisDiv);


        const obrazkyKontajnerDiv = document.createElement('div');
        obrazkyKontajnerDiv.id = 'domov_zobrazenie_kontajner_obrazky';
        obrazkyKontajnerDiv.className = 'domov_zobrazenie_kontajner_obrazky';

        const obrazkyTlacitkoLaveDiv = document.createElement('div');
        obrazkyTlacitkoLaveDiv.id = 'domov_zobrazenie_obrazky_tlacitko_lave';
        obrazkyTlacitkoLaveDiv.className = 'domov_prispevok_icon_pozadie domov_zobrazenie_obrazky_tlacitka';
        const lavaSipka = document.createElement('i');
        lavaSipka.className = 'fa fa-arrow-left fa-3x';
        obrazkyTlacitkoLaveDiv.appendChild(lavaSipka);
        showPostImagesLeftButton = obrazkyTlacitkoLaveDiv;
        obrazkyTlacitkoLaveDiv.addEventListener("click", function() {
            moveImageContainer('-');
        });

        const obrazkyTlacitkoPraveDiv = document.createElement('div');
        obrazkyTlacitkoPraveDiv.id = 'domov_zobrazenie_obrazky_tlacitko_prave';
        obrazkyTlacitkoPraveDiv.className = 'domov_prispevok_icon_pozadie domov_zobrazenie_obrazky_tlacitka';
        const pravaSipka = document.createElement('i');
        pravaSipka.className = 'fa fa-arrow-right fa-3x';
        obrazkyTlacitkoPraveDiv.appendChild(pravaSipka);
        showPostImagesRightButton = obrazkyTlacitkoPraveDiv;
        obrazkyTlacitkoPraveDiv.addEventListener("click", function() {
            moveImageContainer('+');
        });

// Create the obrazok (image) div
        const obrazkyDiv = document.createElement('div');
        obrazkyDiv.id = 'domov_obrazok_prispevok' + post.id;
        obrazkyDiv.className = 'domov_obrazok_prispevok';
        for (let i = 0; i < show_posts_images.length; i++) {
            const obrazokImage = document.createElement('img');
            obrazokImage.id = 'obrazok' + post.id + ':' + i;

            obrazokImage.style.display = "none";
            if(i === 0){
                obrazokImage.style.display = "block";
            }
            obrazokImage.src = '/storage/' + show_posts_images[i];
            obrazokImage.alt = '';


            obrazkyDiv.appendChild(obrazokImage);
            showPostImages[showPostImages.length] = obrazokImage;
        }

        obrazkyKontajnerDiv.appendChild(obrazkyTlacitkoLaveDiv);
        obrazkyKontajnerDiv.appendChild(obrazkyDiv);
        obrazkyKontajnerDiv.appendChild(obrazkyTlacitkoPraveDiv);
        containerDiv.appendChild(obrazkyKontajnerDiv);

        //It shows buttons ifthere are more images
        updateShowImageButtons();


        if(post.poll_text !== null){
            const anketaKontajner = document.createElement('div');
            anketaKontajner.id = 'domov_zobrazenie_anketa_telo';
            anketaKontajner.className = 'domov_zobrazenie_anketa_telo';

            const anketaOtazkaDiv = document.createElement('label');
            anketaOtazkaDiv.textContent = post.poll_text;

            // Append the main container to the document body or any other desired parent element
            anketaKontajner.appendChild(anketaOtazkaDiv);

            for (let i = 0; i < show_post_poll_options_image.length; i++) {
                const anketaMoznostKontajnerDiv = document.createElement('div');
                anketaMoznostKontajnerDiv.id = 'domov_zobrazenie_anketa_moznost_kontajner' + post.id;
                anketaMoznostKontajnerDiv.className = 'domov_zobrazenie_anketa_moznost_kontajner';


                const anketaMoznostObrazokDiv = document.createElement('div');
                anketaMoznostObrazokDiv.id = 'domov_zobrazenie_anketa_moznost_obrazok' + post.id;
                anketaMoznostObrazokDiv.className = 'domov_zobrazenie_anketa_moznost_obrazok';

                const anketaMoznostObrazok = document.createElement('img');
                if(show_post_poll_options_image[i] === null){
                    anketaMoznostObrazok.style.visibility = 'hidden';
                }else {
                    anketaMoznostObrazok.src = '/storage/' + show_post_poll_options_image[i];
                }
                anketaMoznostObrazokDiv.appendChild(anketaMoznostObrazok);
                anketaMoznostKontajnerDiv.appendChild(anketaMoznostObrazokDiv);


                const anketaMoznostTextDiv = document.createElement('div');
                anketaMoznostTextDiv.id = 'domov_zobrazenie_anketa_moznost_text' + post.id;
                anketaMoznostTextDiv.className = 'domov_zobrazenie_anketa_moznost_text';

                const anketaMoznostTextNazov = document.createElement('p');
                anketaMoznostTextNazov.className = 'domov_zobrazenie_anketa_moznost_text_nazov'
                anketaMoznostTextNazov.textContent = show_post_poll_options_text[i];

                const anketaMoznostTextCislo = document.createElement('p');
                anketaMoznostTextCislo.className = 'domov_zobrazenie_anketa_moznost_text_cislo'
                //anketaMoznostTextCislo.textContent = '70' + '%';

                const anketaMoznostTextPozadie = document.createElement('div');
                anketaMoznostTextPozadie.className = 'domov_zobrazenie_anketa_moznost_text_pozadie'

                anketaMoznostTextDiv.appendChild(anketaMoznostTextNazov);
                anketaMoznostTextDiv.appendChild(anketaMoznostTextCislo);
                anketaMoznostTextDiv.appendChild(anketaMoznostTextPozadie);
                anketaMoznostKontajnerDiv.appendChild(anketaMoznostTextDiv);

                showPostPollOptions[i] = anketaMoznostKontajnerDiv;
                anketaMoznostKontajnerDiv.addEventListener("click", function() {
                    choosePollOption(i);
                });

                anketaKontajner.appendChild(anketaMoznostKontajnerDiv);
            }

            const anketaTlacitkoDiv = document.createElement('div');
            anketaTlacitkoDiv.className = 'preferencie_tlacitka_okno';

            const anketaTlacitko = document.createElement('button');
            anketaTlacitko.className = 'preferencie_tlacitko_uloz';
            anketaTlacitko.textContent = 'Hlasuj';
            anketaTlacitko.addEventListener("click", votePollOption);
            anketaTlacitkoDiv.appendChild(anketaTlacitko);
            anketaKontajner.appendChild(anketaTlacitkoDiv);

            containerDiv.appendChild(anketaKontajner);

            //update if user already voted in poll
            if(show_user_poll_option_number > -1){
                choosePollOption(show_user_poll_option_number);
                userVotedPoll();
            }

        }

        const oznaceniaRegionyDiv = document.createElement('div');
        oznaceniaRegionyDiv.id = 'domov_oznacenia_a_regiony' + post.id;
        oznaceniaRegionyDiv.className = 'domov_oznacenia_a_regiony';

        // Create the oznacenia (tags) div
        if(posts_tags[postIndex].length > 0){
            const oznaceniaDiv = document.createElement('div');
            oznaceniaDiv.id = 'domov_oznacenia' + post.id;
            oznaceniaDiv.className = 'domov_oznacenia';

            for (let i = 0; i < posts_tags[postIndex].length; i++) {
                var newTag = document.createElement("div");
                newTag.className = 'domov_oznacenie';
                newTag.textContent = tags.find(tag => tag.id === posts_tags[postIndex][i]).name;
                oznaceniaDiv.appendChild(newTag);
            }
            oznaceniaRegionyDiv.appendChild(oznaceniaDiv);
        }

        // Create the regiony (regions) div
        if(posts_regions[postIndex].length > 0){
            const regionyDiv = document.createElement('div');
            regionyDiv.id = 'domov_regiony' + post.id;
            regionyDiv.className = 'domov_regiony';


            for (let i = 0; i < posts_regions[postIndex].length; i++) {
                var newReg = document.createElement("div");
                newReg.className = 'domov_region';
                newReg.textContent = regions.find(region => region.id === posts_regions[postIndex][i]).name;
                regionyDiv.appendChild(newReg);
            }

            oznaceniaRegionyDiv.appendChild(regionyDiv);
        }
        containerDiv.appendChild(oznaceniaRegionyDiv);

        //Voting
        const hlasovanieTelo = document.createElement('div');
        hlasovanieTelo.className = 'domov_zobrazenie_telo_hlasovanie';

        const hlasovanieTeloHlasy = document.createElement('div');
        hlasovanieTeloHlasy.className = 'domov_zobrazenie_telo_hlasovanie_hlasy';
        hlasovanieTelo.appendChild(hlasovanieTeloHlasy);
        const hlasovanieLabel = document.createElement('label');
        hlasovanieLabel.textContent = 'Hlasovanie: ';
        hlasovanieTeloHlasy.appendChild(hlasovanieLabel);

        const hlasovanieTeloHlasyZaKontajner  = document.createElement('div');
        showPostStats[showPostStats.length] = hlasovanieTeloHlasyZaKontajner;
        hlasovanieTeloHlasyZaKontajner.className = 'domov_zobrazenie_telo_hlasovanie_kontajner';
        hlasovanieTeloHlasy.appendChild(hlasovanieTeloHlasyZaKontajner);
        const hlasZaSipkaHore = document.createElement('i');
        hlasZaSipkaHore.className = 'fa fa-arrow-up fa-1x';
        hlasovanieTeloHlasyZaKontajner.appendChild(hlasZaSipkaHore);
        const hlasZaCislo = document.createElement('p');
        //hlasZaCislo.textContent = show_post_up_voted;
        hlasovanieTeloHlasyZaKontajner.appendChild(hlasZaCislo);
        hlasovanieTeloHlasyZaKontajner.addEventListener("click", function() {
            votePost(1);
        });

        const hlasovanieTeloHlasyProtiKontajner  = document.createElement('div');
        showPostStats[showPostStats.length] = hlasovanieTeloHlasyProtiKontajner;
        hlasovanieTeloHlasyProtiKontajner.className = 'domov_zobrazenie_telo_hlasovanie_kontajner';
        hlasovanieTeloHlasy.appendChild(hlasovanieTeloHlasyProtiKontajner);
        const hlasProtiSipkaHore = document.createElement('i');
        hlasProtiSipkaHore.className = 'fa fa-arrow-down fa-1x';
        hlasovanieTeloHlasyProtiKontajner.appendChild(hlasProtiSipkaHore);
        const hlasProtiCislo = document.createElement('p');
        //hlasProtiCislo.textContent = show_post_down_voted;
        hlasovanieTeloHlasyProtiKontajner.appendChild(hlasProtiCislo);
        hlasovanieTeloHlasyProtiKontajner.addEventListener("click", function() {
            votePost(0);
        });

        const hlasovanieTeloZobrazenia = document.createElement('div');
        hlasovanieTeloZobrazenia.className = 'domov_zobrazenie_telo_hlasovanie_zobrazenia';
        hlasovanieTelo.appendChild(hlasovanieTeloZobrazenia);
        const hlasovanieTeloZobrazeniaKontajner = document.createElement('div');
        hlasovanieTeloZobrazeniaKontajner.className = 'domov_zobrazenie_telo_hlasovanie_kontajner';
        const zobrazenieLabel = document.createElement('label');
        zobrazenieLabel.textContent = 'Zobrazenia: ';
        hlasovanieTeloZobrazenia.appendChild(zobrazenieLabel);
        const hlasovanieTeloZobrazeniaCislaKontajner  = document.createElement('div');
        showPostStats[showPostStats.length] = hlasovanieTeloZobrazeniaCislaKontajner;
        hlasovanieTeloZobrazeniaCislaKontajner.className = 'domov_zobrazenie_telo_hlasovanie_kontajner';
        hlasovanieTeloZobrazenia.appendChild(hlasovanieTeloZobrazeniaCislaKontajner);
        const zobrazenieUzivatelia = document.createElement('i');
        zobrazenieUzivatelia.className = 'fa fa-users  fa-1x';
        hlasovanieTeloZobrazeniaCislaKontajner.appendChild(zobrazenieUzivatelia);
        const zobrazenieCislo = document.createElement('p');
        //zobrazenieCislo.textContent = show_post_openned;
        hlasovanieTeloZobrazeniaCislaKontajner.appendChild(zobrazenieCislo);

        containerDiv.appendChild(hlasovanieTelo);
        userVotedPost();


        //KOMENTARE
        const komentareTelo = document.createElement('div');
        komentareTelo.className = 'domov_zobrazenie_komentare_telo';

        const komentarePridajKontjaner = document.createElement('div');
        komentarePridajKontjaner.className = 'domov_zobrazenie_komentare_pridaj_kontajner';
        komentareTelo.appendChild(komentarePridajKontjaner);

        let showPostCommentInput = document.createElement('input');
        komentarePridajKontjaner.appendChild(showPostCommentInput);
        const komentarePridajTlacitko = document.createElement('i');
        komentarePridajTlacitko.className = 'fa fa-paper-plane fa-1x';
        komentarePridajKontjaner.appendChild(komentarePridajTlacitko);
        komentarePridajKontjaner.addEventListener("click", function() {
            sendComment(showPostCommentInput ,null);
        });

        showPostCommentsDiv = document.createElement('div');
        showPostCommentsDiv.className = 'domov_zobrazenie_komentare_telo_komentare_kontajner';
        komentareTelo.appendChild(showPostCommentsDiv);

        for (let i = 0; i < show_comment_image.length; i++) {
            if(show_comment_upper_id[i] === null){
                createComment(showPostComments.length, show_comment_profile_id[i],
                    show_comment_image[i], show_comment_user_name[i], show_comment_text[i],
                    show_comment_up_vote[i], show_comment_down_vote[i], show_comment_user_voted[i]);
            }else {
                createLowerComment(showPostComments.length, show_comment_profile_id[i],
                    show_comment_image[i], show_comment_user_name[i], show_comment_text[i],
                    show_comment_up_vote[i], show_comment_down_vote[i], show_comment_user_voted[i]);
            }
        }

        containerDiv.appendChild(komentareTelo);

    }

    function moveImageContainer(way){
        var change = false;
        oldShowPostImagesIndex = showPostImagesIndex
        if(way === '+'){
            if(showPostImagesIndex + 1 < showPostImages.length){
                change = true;
                showPostImagesIndex++;
            }
        }
        if(way === '-'){
            if((showPostImagesIndex - 1) > -1){
                change = true;
                showPostImagesIndex--;
            }
        }
        if(change === true){
            showPostImages[oldShowPostImagesIndex].style.display = 'none';
            showPostImages[showPostImagesIndex].style.display = 'block';
        }
        updateShowImageButtons();
    }

    function updateShowImageButtons(){
        if(showPostImagesIndex >= showPostImages.length - 1){
            showPostImagesRightButton.style.visibility = 'hidden';
        } else {
            showPostImagesRightButton.style.visibility = 'visible';
        }
        if(showPostImagesIndex === 0){
            showPostImagesLeftButton.style.visibility = 'hidden';
        }else {
            showPostImagesLeftButton.style.visibility = 'visible';
        }
    }

    function choosePollOption(number){
        showPostChosoenPollOption = number;
        for (let i = 0; i < showPostPollOptions.length; i++) {
            showPostPollOptions[i].style.border = '2px solid lightgray';
            var pollOptionText = showPostPollOptions[i].querySelector('.domov_zobrazenie_anketa_moznost_text');
            pollOptionText.style.color = 'black'

        }
        showPostPollOptions[showPostChosoenPollOption].style.border = '2px solid lightblue';
        var pollOptionTextChange = showPostPollOptions[showPostChosoenPollOption].querySelector('.domov_zobrazenie_anketa_moznost_text');
        pollOptionTextChange.style.color = 'dodgerblue'
    }

    function votePollOption(){
        var post_id = posts[index].id;
        $.ajax({
            url: '/domov/zobrazenie/anketa_hlasuj',
            method: 'POST',
            data: { post_id: post_id, poll_option_number: showPostChosoenPollOption, _token: '{{ csrf_token() }}' },
            success: function (response) {
                show_poll_option_votes = response.poll_option_votes
                userVotedPoll();
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }

    function userVotedPoll(){
        let sumVotes = 0;
        for (let i = 0; i < show_poll_option_votes.length; i++) {
            sumVotes += show_poll_option_votes[i];
        }

        for (let i = 0; i < showPostPollOptions.length; i++) {
            //Adding filled background depends on % votes
            const optionBackgroundDiv = showPostPollOptions[i].querySelector('.domov_zobrazenie_anketa_moznost_text_pozadie');
            let number = (show_poll_option_votes[i] / sumVotes * 100);
            let roundedNumber = Math.round(number * 10) / 10;
            optionBackgroundDiv.style.width = roundedNumber + '%';

            //Adding percentage to every poll option
            var textPerc = document.createElement("div");
            textPerc.className = 'domov_zobrazenie_anketa_moznost_text_cislo';
            textPerc.textContent = roundedNumber + '%';
            let optionDivNumber = showPostPollOptions[i].querySelector('.domov_zobrazenie_anketa_moznost_text');
            optionDivNumber.appendChild(textPerc);

            //Changing color depends if user vote for it
            if(i === showPostChosoenPollOption){
                textPerc.style.color = 'dodgerblue'
                optionBackgroundDiv.style.backgroundColor = 'lightblue';
            }

        }

        let pollContainer = document.getElementById('domov_zobrazenie_anketa_telo');
        //Removing button for voting
        pollContainer.removeChild(pollContainer.lastChild);

        //Adding sum of votes to Poll
        var textPerc = document.createElement("p");
        textPerc.className = 'domov_zobrazenie_anketa_moznost_pocet_hlasov';
        textPerc.textContent = 'Votes: ' + sumVotes;
        pollContainer.appendChild(textPerc);

        //Delete all event listeners from div
        pollContainer.outerHTML = pollContainer.outerHTML;
        pollContainer = document.getElementById('domov_zobrazenie_anketa_telo');
    }

    function votePost(up_vote){
        var post_id = posts[index].id;
        $.ajax({
            url: '/domov/zobrazenie/post_hlasuj',
            method: 'POST',
            data: { post_id: post_id, up_vote: up_vote, _token: '{{ csrf_token() }}' },
            success: function (response) {
                show_post_vote_status = response.post_vote_status;
                show_post_up_voted = response.poll_up_votes;
                show_post_down_voted = response.poll_down_votes;
                show_post_openned = response.poll_oppened;
                userVotedPost();
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }

    function userVotedPost(){
        let up_votes_div = showPostStats[0].querySelector('p');
        up_votes_div.textContent = show_post_up_voted;

        let down_votes_div = showPostStats[1].querySelector('p');
        down_votes_div.textContent = show_post_down_voted;

        let openned_div = showPostStats[2].querySelector('p');
        openned_div.textContent = show_post_openned;

        showPostStats[0].style.backgroundColor = 'white';
        showPostStats[1].style.backgroundColor = 'white';
        if(show_post_vote_status === '+'){
            showPostStats[0].style.backgroundColor = 'lightgreen';
        }else if(show_post_vote_status === '-'){
            showPostStats[1].style.backgroundColor = 'lightcoral';
        }

    }

    function createComment(position, profile_id, profile_image, profile_name, comment_text, comment_up_votes, comment_down_votes, vote_status){
        const komentar = document.createElement('div');
        komentar.className = 'domov_zobrazenie_komentare_komentar';
        showPostCommentsDiv.appendChild(komentar);
        showPostComments[showPostComments.length] = komentar;


        const komentarVrch = document.createElement('div');
        komentarVrch.className = 'domov_zobrazenie_komentare_komentar_vrch';
        komentar.appendChild(komentarVrch);

        const komentarSpodok = document.createElement('div');
        komentarSpodok.className = 'domov_zobrazenie_komentare_komentar_spodok';
        komentar.appendChild(komentarSpodok);

        const komentarStred = document.createElement('div');
        komentarStred.className = 'domov_zobrazenie_komentare_komentar_stred';
        komentar.appendChild(komentarStred);

        const komentarPodKomentare = document.createElement('div');
        komentarPodKomentare.className = 'domov_zobrazenie_komentare_komentar_komentare';
        komentarPodKomentare.id = 'domov_zobrazenie_komentare_komentar_komentare';
        komentar.appendChild(komentarPodKomentare);


        const komentarObrazokDiv = document.createElement('a');
        komentarObrazokDiv.href = '/profil/' + profile_id;
        komentarObrazokDiv.className = 'domov_zobrazenie_komentare_komentar_obrazok';
        komentarVrch.appendChild(komentarObrazokDiv);
        const komentarObrazokImg = document.createElement('img');
        komentarObrazokImg.src = '/storage/' + profile_image;
        //'images/profiles/1OqCPZidVBRtc6Ngz9XouhnBT5Ux0nrtQMDhSB58.jpg'
        komentarObrazokDiv.appendChild(komentarObrazokImg);

        const komentarTelo = document.createElement('div');
        komentarTelo.className = 'domov_zobrazenie_komentare_komentar_telo';
        komentarVrch.appendChild(komentarTelo);

        const komentarTeloMeno = document.createElement('p');
        komentarTeloMeno.textContent = profile_name;
        komentarTeloMeno.className = 'domov_zobrazenie_komentare_komentar_meno';
        komentarTelo.appendChild(komentarTeloMeno);

        const komentarTeloText = document.createElement('p');
        komentarTeloText.textContent = comment_text;
        komentarTeloText.className = 'domov_zobrazenie_komentare_komentar_text';
        komentarTelo.appendChild(komentarTeloText);

        const komentarTeloHlasyZaKontajner  = document.createElement('div');
        //showPostStats[showPostStats.length] = hlasovanieTeloHlasyZaKontajner;
        komentarTeloHlasyZaKontajner.className = 'domov_zobrazenie_komentare_hlasovanie_za_kontajner';
        komentarStred.appendChild(komentarTeloHlasyZaKontajner);

        const hlasZaSipkaHore = document.createElement('i');
        hlasZaSipkaHore.className = 'fa fa-arrow-up fa-1x';
        komentarTeloHlasyZaKontajner.appendChild(hlasZaSipkaHore);
        const hlasZaCislo = document.createElement('p');
        hlasZaCislo.textContent = comment_up_votes;
        komentarTeloHlasyZaKontajner.appendChild(hlasZaCislo);
        komentarTeloHlasyZaKontajner.addEventListener("click", function() {
            voteComment(position, 1);
        });

        const komentarTeloHlasyProtiKontajner  = document.createElement('div');
        //showPostStats[showPostStats.length] = hlasovanieTeloHlasyZaKontajner;
        komentarTeloHlasyProtiKontajner.className = 'domov_zobrazenie_komentare_hlasovanie_proti_kontajner';
        komentarStred.appendChild(komentarTeloHlasyProtiKontajner);

        const hlasProtiSipkaHore = document.createElement('i');
        hlasProtiSipkaHore.className = 'fa fa-arrow-down fa-1x';
        komentarTeloHlasyProtiKontajner.appendChild(hlasProtiSipkaHore);
        const hlasProtiCislo = document.createElement('p');
        hlasProtiCislo.textContent = comment_down_votes;
        komentarTeloHlasyProtiKontajner.appendChild(hlasProtiCislo);
        komentarTeloHlasyProtiKontajner.addEventListener("click", function() {
            voteComment(position, 0);
        });
        setVoteComment(position, vote_status);

        const komentarTeloPridajKomentarKontajner  = document.createElement('div');
        komentarTeloPridajKomentarKontajner.className = 'domov_zobrazenie_komentare_hlasovanie_proti_kontajner';
        komentarStred.appendChild(komentarTeloPridajKomentarKontajner);

        const komentarZnak = document.createElement('i');
        komentarZnak.className = 'fa fa-comment fa-1x';
        komentarTeloPridajKomentarKontajner.appendChild(komentarZnak);
        komentarTeloPridajKomentarKontajner.addEventListener("click", function() {
            komentarTeloPridajKomentarKontajner.style.display = 'none';
            const komentarePridajKontjaner = document.createElement('div');
            komentarePridajKontjaner.className = 'domov_zobrazenie_komentare_pridaj_kontajner';
            //komentarePridajKontjaner.style.width = "80%";
            //komentarePridajKontjaner.style.marginRight = "auto";
            komentarSpodok.appendChild(komentarePridajKontjaner);
            komentarSpodok.style.height = "35px";

            let showPostCommentInput = document.createElement('input');
            komentarePridajKontjaner.appendChild(showPostCommentInput);
            const komentarePridajTlacitko = document.createElement('i');
            komentarePridajTlacitko.className = 'fa fa-paper-plane fa-1x';
            komentarePridajKontjaner.appendChild(komentarePridajTlacitko);
            komentarePridajKontjaner.addEventListener("click", function() {
                sendComment(showPostCommentInput, show_comment_id[position]);
            });
        });
    }

    function createLowerComment(position, profile_id, profile_image, profile_name, comment_text, comment_up_votes, comment_down_votes, vote_status){
        const komentar = document.createElement('div');
        komentar.className = 'domov_zobrazenie_komentare_komentar';
        komentar.style.width = '90%';
        komentar.style.marginLeft = 'auto';
        komentar.style.marginRight = '0';
        mainPosition = position;
        while (true){
            mainPosition--;
            if (show_comment_upper_id[mainPosition] == null){
                break;
            }
        }
        let hlavnyKomentar = showPostComments[mainPosition].querySelector('#domov_zobrazenie_komentare_komentar_komentare');
        hlavnyKomentar.appendChild(komentar);
        showPostComments.splice(position, 0, komentar);


        const komentarVrch = document.createElement('div');
        komentarVrch.className = 'domov_zobrazenie_komentare_komentar_vrch';
        komentar.appendChild(komentarVrch);


        const komentarStred = document.createElement('div');
        komentarStred.className = 'domov_zobrazenie_komentare_komentar_stred';
        komentar.appendChild(komentarStred);


        const komentarObrazokDiv = document.createElement('a');
        komentarObrazokDiv.href = '/profil/' + profile_id;
        komentarObrazokDiv.className = 'domov_zobrazenie_komentare_komentar_obrazok';
        komentarVrch.appendChild(komentarObrazokDiv);
        const komentarObrazokImg = document.createElement('img');
        komentarObrazokImg.src = '/storage/' + profile_image;
        //'images/profiles/1OqCPZidVBRtc6Ngz9XouhnBT5Ux0nrtQMDhSB58.jpg'
        komentarObrazokDiv.appendChild(komentarObrazokImg);

        const komentarTelo = document.createElement('div');
        komentarTelo.className = 'domov_zobrazenie_komentare_komentar_telo';
        komentarVrch.appendChild(komentarTelo);

        const komentarTeloMeno = document.createElement('p');
        komentarTeloMeno.textContent = profile_name;
        komentarTeloMeno.className = 'domov_zobrazenie_komentare_komentar_meno';
        komentarTelo.appendChild(komentarTeloMeno);

        const komentarTeloText = document.createElement('p');
        komentarTeloText.textContent = comment_text;
        komentarTeloText.className = 'domov_zobrazenie_komentare_komentar_text';
        komentarTelo.appendChild(komentarTeloText);

        const komentarTeloHlasyZaKontajner  = document.createElement('div');
        //showPostStats[showPostStats.length] = hlasovanieTeloHlasyZaKontajner;
        komentarTeloHlasyZaKontajner.className = 'domov_zobrazenie_komentare_hlasovanie_za_kontajner';
        komentarStred.appendChild(komentarTeloHlasyZaKontajner);

        const hlasZaSipkaHore = document.createElement('i');
        hlasZaSipkaHore.className = 'fa fa-arrow-up fa-1x';
        komentarTeloHlasyZaKontajner.appendChild(hlasZaSipkaHore);
        const hlasZaCislo = document.createElement('p');
        hlasZaCislo.textContent = comment_up_votes;
        komentarTeloHlasyZaKontajner.appendChild(hlasZaCislo);
        komentarTeloHlasyZaKontajner.addEventListener("click", function() {
            voteComment(position, 1);
        });

        const komentarTeloHlasyProtiKontajner  = document.createElement('div');
        //showPostStats[showPostStats.length] = hlasovanieTeloHlasyZaKontajner;
        komentarTeloHlasyProtiKontajner.className = 'domov_zobrazenie_komentare_hlasovanie_proti_kontajner';
        komentarStred.appendChild(komentarTeloHlasyProtiKontajner);

        const hlasProtiSipkaHore = document.createElement('i');
        hlasProtiSipkaHore.className = 'fa fa-arrow-down fa-1x';
        komentarTeloHlasyProtiKontajner.appendChild(hlasProtiSipkaHore);
        const hlasProtiCislo = document.createElement('p');
        hlasProtiCislo.textContent = comment_down_votes;
        komentarTeloHlasyProtiKontajner.appendChild(hlasProtiCislo);
        komentarTeloHlasyProtiKontajner.addEventListener("click", function() {
            voteComment(position, 0);
        });
        setVoteComment(position, vote_status);

    }

    function sendComment(inputText, upperCommentNumber){
        if(inputText.value !== ''){
            var post_id = posts[index].id;

            $.ajax({
                url: '/domov/zobrazenie/pridaj_koment',
                method: 'POST',
                data: { post_id: post_id, upper_comment_id: upperCommentNumber, comment_text: inputText.value, _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if(upperCommentNumber === null){
                        show_comment_profile_id[show_comment_profile_id.length] = user_profile_id;
                        show_comment_id[show_comment_id.length] = response.comment_id;
                        show_comment_upper_id[show_comment_upper_id.length] = null;
                        show_comment_image[show_comment_image.length] = user_profile_image;
                        show_comment_user_name[show_comment_user_name.length] = user_name;
                        show_comment_text[show_comment_text.length] = inputText.value;
                        show_comment_up_vote[show_comment_up_vote.length] = 0;
                        show_comment_down_vote[show_comment_down_vote.length] = 0;
                        show_comment_user_voted[show_comment_user_voted.length] = '';
                        createComment(showPostComments.length, user_profile_id, user_profile_image, user_name, inputText.value, show_comment_up_vote[show_comment_up_vote.length - 1], show_comment_down_vote[show_comment_down_vote.length - 1], '');
                        inputText.value = '';
                    }else {
                        inputText.value = '';
                        //alert(upperCommentNumber);
                    }

                    //show_post_vote_status = response.post_vote_status;


                },
                error: function (error) {
                    console.error('Error:', error);
                }
            });
        }
    }

    function voteComment(position, up_vote){
        let comment_id = show_comment_id[position];

        $.ajax({
            url: '/domov/zobrazenie/hlasuj_koment',
            method: 'POST',
            data: { comments_id: show_comment_id, comment_id: comment_id, up_vote: up_vote, _token: '{{ csrf_token() }}' },
            success: function (response) {

                show_comment_up_vote = response.comment_up_votes;
                show_comment_down_vote =response.comment_down_votes;
                setVoteComment(position,response.comment_vote_result);
                //show_post_vote_status = response.post_vote_status;

            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }

    function setVoteComment(position, vote_status){
        let upVoteDiv = showPostComments[position].getElementsByClassName("domov_zobrazenie_komentare_hlasovanie_za_kontajner");
        let downVoteDiv = showPostComments[position].getElementsByClassName("domov_zobrazenie_komentare_hlasovanie_proti_kontajner");

        upVoteDiv[0].style.backgroundColor = 'white';
        downVoteDiv[0].style.backgroundColor = 'white';

        if(vote_status === '+'){
            upVoteDiv[0].style.backgroundColor = 'lightgreen';

        }else if(vote_status === '-'){
            downVoteDiv[0].style.backgroundColor = 'lightcoral';
        }

        //let upVoteNumP = upVoteDiv[0].querySelector("p");
        //upVoteNumP.textContent = show_comment_up_vote[position];
        //let downVoteNumP = downVoteDiv[0].querySelector("p");
        //downVoteNumP.textContent = show_comment_down_vote[position];

        let upVoteNumP = upVoteDiv[0].querySelector("p");
        upVoteNumP.textContent = show_comment_up_vote[position];
        let downVoteNumP = downVoteDiv[0].querySelector("p");
        downVoteNumP.textContent = show_comment_down_vote[position];
    }


    function updateLoadPosts(next){
        //position 0-is previous, 1 is actuall and 2 is next post
        if(next){
            if (loadedPosts[0] !== null) {
                while (loadedPosts[0].firstChild) {
                    loadedPosts[0].removeChild(loadedPosts[0].firstChild);
                }
                loadedPosts[0].parentNode.removeChild(loadedPosts[0]);
            }

            loadedPosts[0] = loadedPosts[1];
            loadedPosts[1] = loadedPosts[2];
            if(posts.length - 1 > index ){
                createPost(2);
            }else {
                loadedPosts[2] = null;
            }
            direction = 1;
        }else {
            if (loadedPosts[2] !== null) {
                while (loadedPosts[2].firstChild) {
                    loadedPosts[2].removeChild(loadedPosts[2].firstChild);
                }
                loadedPosts[2].parentNode.removeChild(loadedPosts[2]);
            }

            loadedPosts[2] = loadedPosts[1];
            loadedPosts[1] = loadedPosts[0];
            if(0 < index){
                createPost(0);
            }else {
                loadedPosts[0] = null;
            }
            direction = -1;
        }
        localStorage.setItem('oldIndex', index);
        animate()
    }

    function updateLocation(){
        if (loadedPosts[0] !== null) {
            loadedPosts[0].style.top = '-50%';
        }
        if (loadedPosts[1] !== null) {
            loadedPosts[1].style.top = '50%';
        }
        if (loadedPosts[2] !== null) {
            loadedPosts[2].style.top = '150%';
        }
    }

    let top0 = -50;
    let top1 = 50;
    let top2 = 150;

    function animate() {

        // Apply positions
        if (loadedPosts[0] !== null) {
            loadedPosts[0].style.top = top0 + '%';
        }

        loadedPosts[1].style.top = top1 + '%';

        if (loadedPosts[2] !== null) {
            loadedPosts[2].style.top = top2 + '%';
        }

        // Request the next animation frame
        requestAnimationFrame(animate);
    }

    window.addEventListener("wheel", (event) => {
        //Enable or disable scroll listener if user watch just one post
        if(scrollListener){
            //This block fast scrolling
            if (!canScroll) {
                return;
            }
            canScroll = false;

            if (event.deltaY > 0) {
                if(posts.length - 1 > index ){
                    index++;
                    updateLoadPosts(true);
                    //updatePost(index);

                }

            } else if (event.deltaY < 0) {
                if(0 < index){
                    index--;
                    updateLoadPosts(false);
                    //updatePost(index);
                }
            }

            // Set a timeout to re-enable scrolling after a delay (e.g., 1000 milliseconds)
            setTimeout(() => {
                canScroll = true;
            }, 450);
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        buttonBack.style.display = "none";
        var oldIndex = localStorage.getItem('oldIndex');

        if (oldIndex !== null) {
            index = oldIndex;
        }

        loadedPosts[0] = null;
        loadedPosts[1] = null;
        loadedPosts[2] = null;


        if (index > 0){
            createPost(0);
        }
        createPost(1);
        if (index < posts.length - 1){
            createPost(2);
        }


    });


    function createPost(positionInLoadedPosts){
        let postIndex = parseInt(index) + (parseInt(positionInLoadedPosts) - 1);
        const post = posts[postIndex];

        // Create the main container div
        const containerDiv = document.createElement('div');
        containerDiv.id = 'domov_prispevok_telo' + post.id;
        containerDiv.className = 'domov_prispevok_telo';
        //Listener to show whole post
        containerDiv.addEventListener("click", function() {
            setDataShowPost();
        });

// Create the nadpis (title) div
        const nadpisDiv = document.createElement('div');
        nadpisDiv.className = 'domov_nadpis_prispevok';
        const nadpisParagraph = document.createElement('p');
        nadpisParagraph.id = 'nadpis' + post.id;
        nadpisParagraph.textContent = post.title;
        nadpisDiv.appendChild(nadpisParagraph);

// Create the popis (description) div
        const popisDiv = document.createElement('div');
        popisDiv.className = 'domov_popis_prispevok';
        const textParagraph = document.createElement('p');
        textParagraph.id = 'text' + post.id;
        textParagraph.textContent = post.text.substring(0, 350);
        popisDiv.appendChild(textParagraph);
        const fadeoutDiv = document.createElement('div');
        fadeoutDiv.className = 'fadeout';
        popisDiv.appendChild(fadeoutDiv);

// Create the obrazok (image) div
        const obrazokDiv = document.createElement('div');
        obrazokDiv.id = 'domov_obrazok_prispevok' + post.id;
        obrazokDiv.className = 'domov_obrazok_prispevok';
        const obrazokImage = document.createElement('img');
        obrazokImage.id = 'obrazok' + post.id;
        if(posts_images[postIndex] == null){
            obrazokDiv.style.display = "none";
        }else {
            obrazokDiv.style.display = "block";
            obrazokImage.src = '/storage/' + posts_images[postIndex];
        }
        obrazokImage.alt = '';

        obrazokDiv.appendChild(obrazokImage);

// Create the prieskum (chart) div
        const prieskumDiv = document.createElement('div');
        prieskumDiv.id = 'domov_prispevok_prieskum' + post.id;
        prieskumDiv.className = 'domov_prispevok_prieskum';
        const prieskumIkonaDiv = document.createElement('div');
        prieskumIkonaDiv.className = 'domov_prispevok_prieskum_ikona';
        const ikonaElement = document.createElement('i');
        ikonaElement.className = 'fa fa-pie-chart fa-2x';
        prieskumIkonaDiv.appendChild(ikonaElement);
        const prieskumOtazkaDiv = document.createElement('div');
        prieskumOtazkaDiv.className = 'domov_prispevok_prieskum_otazka';
        const prieskumTextParagraph = document.createElement('p');
        prieskumTextParagraph.id = 'prieskum_text' + post.id;
        if(post.poll_text == null){
            prieskumDiv.style.display = "none";
        }else {
            prieskumDiv.style.display = "flex";
            prieskumTextParagraph.textContent = post.poll_text;
        }
        prieskumOtazkaDiv.appendChild(prieskumTextParagraph);
        prieskumDiv.appendChild(prieskumIkonaDiv);
        prieskumDiv.appendChild(prieskumOtazkaDiv);


        // Append all created elements to the main container
        containerDiv.appendChild(nadpisDiv);
        containerDiv.appendChild(popisDiv);
        containerDiv.appendChild(obrazokDiv);
        containerDiv.appendChild(prieskumDiv);


        const oznaceniaRegionyDiv = document.createElement('div');
        oznaceniaRegionyDiv.id = 'domov_oznacenia_a_regiony' + post.id;
        oznaceniaRegionyDiv.className = 'domov_oznacenia_a_regiony';

        // Create the oznacenia (tags) div
        if(posts_tags[postIndex].length > 0){
            const oznaceniaDiv = document.createElement('div');
            oznaceniaDiv.id = 'domov_oznacenia' + post.id;
            oznaceniaDiv.className = 'domov_oznacenia';

            for (let i = 0; i < posts_tags[postIndex].length; i++) {
                var newTag = document.createElement("div");
                newTag.className = 'domov_oznacenie';
                newTag.textContent = tags.find(tag => tag.id === posts_tags[postIndex][i]).name;
                oznaceniaDiv.appendChild(newTag);
            }
            oznaceniaRegionyDiv.appendChild(oznaceniaDiv);
        }

        // Create the regiony (regions) div
        if(posts_regions[postIndex].length > 0){
            const regionyDiv = document.createElement('div');
            regionyDiv.id = 'domov_regiony' + post.id;
            regionyDiv.className = 'domov_regiony';


            for (let i = 0; i < posts_regions[postIndex].length; i++) {
                var newReg = document.createElement("div");
                newReg.className = 'domov_region';
                newReg.textContent = regions.find(region => region.id === posts_regions[postIndex][i]).name;
                regionyDiv.appendChild(newReg);
            }

            oznaceniaRegionyDiv.appendChild(regionyDiv);
        }
        containerDiv.appendChild(oznaceniaRegionyDiv);

// Append the main container to the document body or any other desired parent element
        homeContainer.appendChild(containerDiv);

        loadedPosts[positionInLoadedPosts] = containerDiv;

        if(positionInLoadedPosts === 0){
            containerDiv.style.top = '-50%';
        }
        if(positionInLoadedPosts === 1){
            containerDiv.style.top = '50%';
        }
        if(positionInLoadedPosts === 2){
            containerDiv.style.top = '150%';
        }
    }


</script>

</body>
</html>
