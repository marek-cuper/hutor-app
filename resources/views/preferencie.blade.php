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

<div class="preferencie_stranka">

    <div class="prepinac_medzi_preferenciami_mapou">
        <div class="home_prispevok_icon_pozadie">
            <i class="fa fa-tasks fa-3x"></i>
        </div>

        <!-- Rounded switch -->
        <label class="switch">
            <input type="checkbox">
            <span class="slider"></span>
        </label>

        <div class="home_prispevok_icon_pozadie">
            <i class="fa fa-map fa-3x"></i>
        </div>
    </div>

    <div id="prepinace_preferencie" class="prepinace_preferencie"></div>

    <div class="preferencie_tlacitka_okno">
        <button class="preferencie_tlacitko_uloz">ULOZ</button>

        <button class="preferencie_tlacitko_reset">RESET</button>

    </div>
</div>


@include('include.footbar')

<script>

    var tags = @json(session('tags'));
    var user_tags_pref = @json(session('user_tags_pref'));
    var user_tags_block = @json(session('user_tags_block'));

    const prefSwitchsContainer = document.getElementById('prepinace_preferencie');

    let blockBackColour = '#ff3d00';
    let neutralBackColour = '#00b0ff';
    let PreferBackColour = '#64dd17';
    let AnyBackColour = 'white';

    let chooseIconColour = 'white';
    let notChooseIconColour = 'black';


    document.addEventListener('DOMContentLoaded', function() {
        for (let i = 0; i < tags.length; i++) {
            if(user_tags_pref.includes(tags[i].id)){
                createTag(tags[i].id ,tags[i].name, 'prefer')
            }
            else if(user_tags_block.includes(tags[i].id)){
                createTag(tags[i].id ,tags[i].name, 'block')
            }
            else {
                createTag(tags[i].id ,tags[i].name, 'neutral')
            }

        }

    });

    function createTag(tagId, tagName, choosedOption){
        // Create div elements
        const preferenciaOznacenieTelo = document.createElement("div");
        preferenciaOznacenieTelo.classList.add("preferencia_oznacenie_telo");
        preferenciaOznacenieTelo.id = 'pref' + tagId;

        const sportParagraph = document.createElement("p");
        sportParagraph.textContent = tagName;

        const preferenciaOznacenieIkony = document.createElement("div");
        preferenciaOznacenieIkony.classList.add("preferencia_oznacenie_ikony");

        const zakazIkona = document.createElement("div");
        zakazIkona.classList.add("preferencia_oznacenie_ikona_zakaz");

        const zakazFontAwesome = document.createElement("i");
        zakazFontAwesome.classList.add("fa", "fa-ban", "fa-2x");

        zakazIkona.appendChild(zakazFontAwesome);

        const neutralIkona = document.createElement("div");
        neutralIkona.classList.add("preferencia_oznacenie_ikona_neutral");

        const neutralFontAwesome = document.createElement("i");
        neutralFontAwesome.classList.add("fa", "fa-minus", "fa-2x");

        neutralIkona.appendChild(neutralFontAwesome);

        const oblubenaIkona = document.createElement("div");
        oblubenaIkona.classList.add("preferencia_oznacenie_ikona_oblubena");

        const oblubenaFontAwesome = document.createElement("i");
        oblubenaFontAwesome.classList.add("fa", "fa-heart", "fa-2x");

        zakazIkona.addEventListener("click", () =>
            chooseTagStatus(zakazIkona, zakazFontAwesome, neutralIkona, neutralFontAwesome, oblubenaIkona, oblubenaFontAwesome, 'block')
        );
        neutralIkona.addEventListener("click", () =>
            chooseTagStatus(zakazIkona, zakazFontAwesome, neutralIkona, neutralFontAwesome, oblubenaIkona, oblubenaFontAwesome, 'neutral')
        );
        oblubenaIkona.addEventListener("click", () =>
            chooseTagStatus(zakazIkona, zakazFontAwesome, neutralIkona, neutralFontAwesome, oblubenaIkona, oblubenaFontAwesome, 'prefer')
        );

        oblubenaIkona.appendChild(oblubenaFontAwesome);

        chooseTagStatus(zakazIkona, zakazFontAwesome, neutralIkona, neutralFontAwesome, oblubenaIkona, oblubenaFontAwesome, choosedOption)

        // Append elements to the DOM
        prefSwitchsContainer.appendChild(preferenciaOznacenieTelo);
        preferenciaOznacenieTelo.appendChild(sportParagraph);
        preferenciaOznacenieTelo.appendChild(preferenciaOznacenieIkony);
        preferenciaOznacenieIkony.appendChild(zakazIkona);
        preferenciaOznacenieIkony.appendChild(neutralIkona);
        preferenciaOznacenieIkony.appendChild(oblubenaIkona);
    }

    function chooseTagStatus(zakazIkona, zakazFontAwesome, neutralIkona, neutralFontAwesome, oblubenaIkona, oblubenaFontAwesome, choosedOption){
        if(choosedOption === 'block'){
            zakazIkona.style.backgroundColor = blockBackColour;
            zakazFontAwesome.style.color = chooseIconColour;
            neutralIkona.style.backgroundColor = AnyBackColour;
            neutralFontAwesome.style.color = notChooseIconColour;
            oblubenaIkona.style.backgroundColor = AnyBackColour;
            oblubenaFontAwesome.style.color = notChooseIconColour;
        }else if(choosedOption === 'neutral'){
            zakazIkona.style.backgroundColor = AnyBackColour;
            zakazFontAwesome.style.color = notChooseIconColour;
            neutralIkona.style.backgroundColor = neutralBackColour;
            neutralFontAwesome.style.color = chooseIconColour;
            oblubenaIkona.style.backgroundColor = AnyBackColour;
            oblubenaFontAwesome.style.color = notChooseIconColour;
        }else{
            zakazIkona.style.backgroundColor = AnyBackColour;
            zakazFontAwesome.style.color = notChooseIconColour;
            neutralIkona.style.backgroundColor = AnyBackColour;
            neutralFontAwesome.style.color = notChooseIconColour;
            oblubenaIkona.style.backgroundColor = PreferBackColour;
            oblubenaFontAwesome.style.color = chooseIconColour;
        }
    }
</script>
</body>
</html>
