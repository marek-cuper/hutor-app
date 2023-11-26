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

<div class="home_prispevok" onscroll="changeBackgroundColor()">

    <div id="home_prispevok_telo" class="home_prispevok_telo" onclick="">
        <div class="home_nadpis_prispevok">
            <p id="nadpis"></p>
        </div>
        <div class="home_popis_prispevok">
            <p id="text"></p>
            <div class="fadeout"></div>
        </div>
        <div id="home_obrazok_prispevok" class="home_obrazok_prispevok">
            <img id="obrazok" src="" alt="" style="max-width: 250px; max-height: 250px;">
        </div>


        <div id="home_prispevok_prieskum" class="home_prispevok_prieskum2">
            <div class="home_prispevok_prieskum_ikona">
                <i class="fa fa-pie-chart fa-2x"></i>
            </div>
            <div class="home_prispevok_prieskum_otazka">
                <p id="prieskum_text"></p>
            </div>
        </div>

        <div id="home_prispevok_oznacenia" class="home_prispevok_oznacenia">

        </div>
    </div>

</div>


@include('include.footbar')

<script>

    // Listen for the scroll event
    let canScroll = true;
    var index = 0;
    var posts = @json(session('posts'));
    var posts_tags = @json(session('posts_tags'));
    var tags = @json(session('tags'));

    const divTittle = document.getElementById('nadpis');
    const divText = document.getElementById('text');
    const divImage = document.getElementById('obrazok');
    const divPollText = document.getElementById('prieskum_text');

    const divPostWhole = document.getElementById('home_prispevok_telo');
    const divImageWhole = document.getElementById('home_obrazok_prispevok');
    const divPollTextWhole = document.getElementById('home_prispevok_prieskum');
    const divTagsWhole = document.getElementById('home_prispevok_oznacenia');

    function startAnimation() {
        divPostWhole.classList.add('animate');
    }

    function stopAnimation() {
        divPostWhole.classList.remove('animate');
    }

    window.addEventListener("wheel", (event) => {
        if (!canScroll) {
            return;
        }
        canScroll = false;

        if (event.deltaY > 0) {
            if(posts.length - 1 > index ){
                index++;
                updatePost(index);
            }

        } else if (event.deltaY < 0) {
            if(0 < index){
                index--;
                updatePost(index);
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

        updatePost(index);

    });

    function updatePost(index) {
        const post = posts[index];

        // Update elements in the DOM
        divTittle.textContent = post.title;
        divText.textContent = post.text.substring(0, 350);
        divImage.src = '/storage/' + post.image_name;
        divPollText.textContent = post.poll_text;

        if(post.image_name == null){
            divImageWhole.style.display = "none";
        }else {
            divImageWhole.style.display = "block";
            divImage.src = '/storage/' + post.image_name;
        }

        if(post.poll_text == null){
            divPollTextWhole.style.display = "none";
        }else {
            divPollTextWhole.style.display = "flex";
            divPollText.textContent = post.poll_text;
        }

        while (divTagsWhole.firstChild) {
            divTagsWhole.removeChild(divTagsWhole.firstChild);
        }

        for (let i = 0; i < posts_tags[index].length; i++) {
            var newTag = document.createElement("div");
            newTag.className = 'home_prispevok_oznacenie';
            newTag.textContent = tags.find(tag => tag.id === posts_tags[index][i]).name;
            divTagsWhole.appendChild(newTag);
        }

        // Save index to localStorage
        localStorage.setItem('oldIndex', index);
    }

</script>

</body>
</html>
