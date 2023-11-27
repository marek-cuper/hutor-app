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

<div id="home_container" class="home_prispevok">



</div>


@include('include.footbar')

<script>

    // Listen for the scroll event
    let canScroll = true;
    let index = 0;
    var posts = @json(session('posts'));
    var posts_tags = @json(session('posts_tags'));
    var tags = @json(session('tags'));

    var loadedPosts = [];
    const homeContainer = document.getElementById('home_container');

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
            //loadedPosts[1].style.transform = "translateY(-100%)";
            //loadedPosts[0].style.transform = "translateY(-100%)";
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
            //loadedPosts[1].style.transform = "top(100%)";
            //loadedPosts[2].style.transform = "top(100%)";
        }
        localStorage.setItem('oldIndex', index);
        updateLocation();
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

    function createPost(positionInLoadedPosts){
        let postIndex = parseInt(index) + (parseInt(positionInLoadedPosts) - 1);
        const post = posts[postIndex];

        // Create the main container div
        const containerDiv = document.createElement('div');
        containerDiv.id = 'home_prispevok_telo' + post.id;
        containerDiv.className = 'home_prispevok_telo home_prispevok_telo';

// Create the nadpis (title) div
        const nadpisDiv = document.createElement('div');
        nadpisDiv.className = 'home_nadpis_prispevok';
        const nadpisParagraph = document.createElement('p');
        nadpisParagraph.id = 'nadpis' + post.id;
        nadpisParagraph.textContent = post.title;
        nadpisDiv.appendChild(nadpisParagraph);

// Create the popis (description) div
        const popisDiv = document.createElement('div');
        popisDiv.className = 'home_popis_prispevok';
        const textParagraph = document.createElement('p');
        textParagraph.id = 'text' + post.id;
        textParagraph.textContent = post.text.substring(0, 350);
        popisDiv.appendChild(textParagraph);
        const fadeoutDiv = document.createElement('div');
        fadeoutDiv.className = 'fadeout';
        popisDiv.appendChild(fadeoutDiv);

// Create the obrazok (image) div
        const obrazokDiv = document.createElement('div');
        obrazokDiv.id = 'home_obrazok_prispevok' + post.id;
        obrazokDiv.className = 'home_obrazok_prispevok';
        const obrazokImage = document.createElement('img');
        obrazokImage.id = 'obrazok' + post.id;
        if(post.image_name == null){
            obrazokDiv.style.display = "none";
        }else {
            obrazokDiv.style.display = "block";
            obrazokImage.src = '/storage/' + post.image_name;
        }
        obrazokImage.alt = '';
        obrazokImage.style.maxWidth = '250px';
        obrazokImage.style.maxHeight = '250px';
        obrazokDiv.appendChild(obrazokImage);

// Create the prieskum (chart) div
        const prieskumDiv = document.createElement('div');
        prieskumDiv.id = 'home_prispevok_prieskum' + post.id;
        prieskumDiv.className = 'home_prispevok_prieskum2';
        const prieskumIkonaDiv = document.createElement('div');
        prieskumIkonaDiv.className = 'home_prispevok_prieskum_ikona';
        const ikonaElement = document.createElement('i');
        ikonaElement.className = 'fa fa-pie-chart fa-2x';
        prieskumIkonaDiv.appendChild(ikonaElement);
        const prieskumOtazkaDiv = document.createElement('div');
        prieskumOtazkaDiv.className = 'home_prispevok_prieskum_otazka';
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


// Create the oznacenia (tags) div
        const oznaceniaDiv = document.createElement('div');
        oznaceniaDiv.id = 'home_prispevok_oznacenia' + post.id;
        oznaceniaDiv.className = 'home_prispevok_oznacenia';

        while (oznaceniaDiv.firstChild) {
            oznaceniaDiv.removeChild(divTagsWhole.firstChild);
        }

        for (let i = 0; i < posts_tags[postIndex].length; i++) {
            var newTag = document.createElement("div");
            newTag.className = 'home_prispevok_oznacenie';
            newTag.textContent = tags.find(tag => tag.id === posts_tags[postIndex][i]).name;
            oznaceniaDiv.appendChild(newTag);
        }


// Append all created elements to the main container
        containerDiv.appendChild(nadpisDiv);
        containerDiv.appendChild(popisDiv);
        containerDiv.appendChild(obrazokDiv);
        containerDiv.appendChild(prieskumDiv);
        containerDiv.appendChild(oznaceniaDiv);

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

    window.addEventListener("wheel", (event) => {
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


</script>

</body>
</html>
