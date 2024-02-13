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

<div id="domov_kontajner_prispevky" class="domov_kontajner_prispevky">

</div>
<div id="domov_zobrazeny_prispevok" class="domov_zobrazeny_prispevok">

</div>


@include('include.footbar')

<script>

    // Listen for the scroll event
    let canScroll = true;
    let index = 0;
    var posts = @json(session('posts'));
    var posts_images = @json(session('posts_images'));
    var posts_tags = @json(session('posts_tags'));
    var posts_regions = @json(session('posts_regions'));

    var show_posts_images = [];
    var show_post_poll_options_image = [];
    var show_post_poll_options_text = [];
    var show_user_poll_option_number;

    var tags = @json(session('tags'));
    var regions = @json(session('regions'));

    var loadedPosts = [];
    const homeContainer = document.getElementById('domov_kontajner_prispevky');
    const showContainer = document.getElementById('domov_zobrazeny_prispevok');

    let scrollListener = true;

    let showPostImages = [];
    let showPostImagesIndex = 0;
    let showPostImagesLeftButton;
    let showPostImagesRightButton;

    let showPostChosoenPollOption;
    let showPostPollOptions = [];

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
                showPost();

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


        if(post.poll_text !== null){
            const anketaKontajner = document.createElement('div');
            anketaKontajner.id = 'domov_zobrazenie_anketa_telo';
            anketaKontajner.className = 'domov_zobrazenie_anketa_telo';

            const anketaOtazkaDiv = document.createElement('label');
            anketaOtazkaDiv.textContent = post.poll_text;

            anketaKontajner.appendChild(anketaOtazkaDiv);

            for (let i = 0; i < show_post_poll_options_image.length; i++) {
                const anketaMoznost = document.createElement('div');
                anketaMoznost.id = 'domov_zobrazenie_anketa_moznost' + post.id;
                anketaMoznost.className = 'domov_zobrazenie_anketa_moznost';

                const anketaMoznostObrazok = document.createElement('img');
                anketaMoznostObrazok.src = '/storage/' + show_post_poll_options_image[i];
                if(show_post_poll_options_image[i] === null){
                    anketaMoznostObrazok.style.visibility = 'hidden';
                }


                const anketaMoznostText = document.createElement('p');
                anketaMoznostText.textContent = show_post_poll_options_text[i];

                anketaMoznost.appendChild(anketaMoznostObrazok);
                anketaMoznost.appendChild(anketaMoznostText);

                showPostPollOptions[i] = anketaMoznost;
                anketaMoznost.addEventListener("click", function() {
                    choosePollOption(i);
                });

                anketaKontajner.appendChild(anketaMoznost);
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

// Append the main container to the document body or any other desired parent element
        showContainer.appendChild(containerDiv);
        updateShowImageButtons();

        //update if user already voted in poll
        if(show_user_poll_option_number > -1){
            choosePollOption(show_user_poll_option_number);
            userVoted();
        }
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
            showPostPollOptions[i].style.backgroundColor = 'white';
        }
        showPostPollOptions[showPostChosoenPollOption].style.backgroundColor = 'lightgray';
    }

    function votePollOption(){
        var post_id = posts[index].id;
        $.ajax({
            url: '/domov/zobrazenie/anketa_hlasuj',
            method: 'POST',
            data: { post_id: post_id, poll_option_number: showPostChosoenPollOption, _token: '{{ csrf_token() }}' },
            success: function (response) {
                userVoted();
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }

    function userVoted(){
        let pollContainer = document.getElementById('domov_zobrazenie_anketa_telo');
        pollContainer.removeChild(pollContainer.lastChild);
        //Delete all event listeners from div
        pollContainer.outerHTML = pollContainer.outerHTML;
        pollContainer = document.getElementById('domov_zobrazenie_anketa_telo');

        const statisticPollButtonDiv = document.createElement('div');
        statisticPollButtonDiv.className = 'domov_zobrazenie_anketa_statistika_tlacitko';
        const statisticPoll = document.createElement('i');
        statisticPoll.className = 'fa fa-pie-chart fa-2x';
        statisticPollButtonDiv.appendChild(statisticPoll);
        pollContainer.appendChild(statisticPollButtonDiv);
        statisticPollButtonDiv.addEventListener("click", function() {
            moveImageContainer('+');
        });

    }

    function showPollStatistic(){

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
