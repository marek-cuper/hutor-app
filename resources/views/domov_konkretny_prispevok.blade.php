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
            <p>{{ $posts[$postNum]->title }}</p>
        </div>
        <div class="home_popis_prispevok">
            <p>{{ substr($posts[$postNum]->text, 0, 350) }}</p>
            <div class="fadeout"></div>
        </div>
        <div class="home_obrazok_prispevok">
            <img src="{{ Storage::url($posts[$postNum]->image_name) }}" alt="">
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

    window.addEventListener("wheel", (event) => {
        if (!canScroll) {
            return;
        }

        canScroll = false;

        if (event.deltaY > 0) {
            // Scrolling down
            window.location.href = "{{URL::to('/domov/prispevok/dalsi')}}"
        } else if (event.deltaY < 0) {
            // Scrolling up
            window.location.href = "{{URL::to('/domov/prispevok/predosli')}}"
        }

        // Set a timeout to re-enable scrolling after a delay (e.g., 1000 milliseconds)
        setTimeout(() => {
            canScroll = true;
        }, 1000);
    });
</script>

</body>
</html>
