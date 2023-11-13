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

    <div class="home_prispevok_telo" onclick="">
        <div class="home_nadpis_prispevok">
            <p id="nadpis">{{ $post->title }}</p>
        </div>
        <div class="home_popis_prispevok">
            <p id="text">{{ substr($post->text, 0, 350) }}</p>
            <div class="fadeout"></div>
        </div>
        <div class="home_obrazok_prispevok">
            <img id="obrazok" src="{{ Storage::url($post->image_name) }}" alt="" style="max-width: 250px; max-height: 250px;">
        </div>
        <div class="home_prispevok_prieskum">
            <div class="home_prispevok_prieskum_moznost">
                <!--                <input type="radio" name="vote" value="0" onclick=""> -->
                <p>Zbieram</p>
            </div>
            <div class="home_prispevok_prieskum_moznost">
                <p>Nezbieram</p>
            </div>
            <div class="home_prispevok_prieskum_moznost">
                <p>Netusim na co by to bolo dobre...</p>
            </div>
            <div class="home_prispevok_prieskum_moznost">
                <p>...</p>
            </div>
        </div>

        <div class="home_prispevok_oznacenia">
            <div class="oznacenie" style="background-color: lightsalmon">
                Koníček
            </div>
            <div class="oznacenie" style="background-color: burlywood">
                Anketa
            </div>

        </div>
    </div>

</div>


@include('include.footbar')

<script>

    // Listen for the scroll event
    let canScroll = true;
    var index = 0;
    var posts = @json(session('posts'));

    const divTittle = document.getElementById('nadpis');
    const divText = document.getElementById('text');
    const divImage = document.getElementById('obrazok');

    window.addEventListener("wheel", (event) => {
        if (!canScroll) {
            return;
        }
        canScroll = false;

        if (event.deltaY > 0) {
            if(posts.length > index){
                index++;
                const post = posts[index];

                divTittle.textContent = post.title;
                divText.textContent = post.text.substring(0,350);
                divImage.src = '/storage/' + post.image_name;
            }

        } else if (event.deltaY < 0) {
            if(0 < index){
                index--;
                const post = posts[index];

                divTittle.textContent = post.title;
                divText.textContent = post.text.substring(0,350);
                divImage.src = '/storage/' + post.image_name;
            }
        }

        // Set a timeout to re-enable scrolling after a delay (e.g., 1000 milliseconds)
        setTimeout(() => {
            canScroll = true;
        }, 500);
    });
</script>

</body>
</html>
