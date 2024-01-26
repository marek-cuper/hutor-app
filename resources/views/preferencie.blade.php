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
            <input type="checkbox" id="mapSwitch">
            <span class="slider"></span>
        </label>

        <div class="home_prispevok_icon_pozadie">
            <i class="fa fa-map fa-3x"></i>
        </div>
    </div>
    <form id="pref_form" action="{{ route('preferencie.post') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div id="prepinace_preferencie" class="prepinace_preferencie"></div>

        <div class="preferencie_tlacitka_okno">
            <button type="submit" class="preferencie_tlacitko_uloz">ULOZ</button>

            <button class="preferencie_tlacitko_reset">RESET</button>

        </div>
    </form>


    <div id="mapa_preferencie" class="mapa_preferencie">

        <img id="sk_regs" src="{{ asset('images/regions/Slovak_reg_map_trans.png') }}">

        <img id="reg1" src="{{ asset('images/regions/org_reg1.png') }}">
        <img id="reg2" src="{{ asset('images/regions/org_reg2.png') }}">
        <img id="reg3" src="{{ asset('images/regions/org_reg3.png') }}">
        <img id="reg4" src="{{ asset('images/regions/org_reg4.png') }}">

    </div>
    <form id="map_form" action="{{ route('regiony.post') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="prihlasenie_formular_kolonka">
            <label><b>Regiony na vyber:</b></label>
            <select id="select_regiony" placeholder="Vyber region(max 5)" onchange="hideSelectedOption()">
                <option value="" disabled selected>Vyber region(max 5)</option>
            </select>
        </div>
        <div class="prihlasenie_formular_kolonka">
            <label><b>Vybrate preferovane regiony:</b></label>
            <div id="vybrate_regiony">

            </div>
            <div id="skryte_regiony" style="display: none;">

            </div>
        </div>

        <div class="preferencie_tlacitka_okno">
            <button type="submit" class="preferencie_tlacitko_uloz">ULOZ</button>

            <button class="preferencie_tlacitko_reset">RESET</button>

        </div>
    </form>
</div>


@include('include.footbar')

<script>

    var tags = @json(session('tags'));
    var user_tags_pref = @json(session('user_tags_pref'));
    var user_tags_block = @json(session('user_tags_block'));

    var regions = @json(session('regions'));
    var user_regions = @json(session('user_regions'));

    const prefSwitchsContainer = document.getElementById('prepinace_preferencie');
    const mapSwitch = document.getElementById('mapSwitch');

    const prefForm = document.getElementById('pref_form');
    const mapForm = document.getElementById('map_form');
    const mapPref = document.getElementById('mapa_preferencie');

    var selectDivRegions = document.getElementById("select_regiony");
    var selectedRegions = document.getElementById("vybrate_regiony");
    var hiddenInputsDivRegions = document.getElementById("skryte_regiony");

    const reg1 = document.getElementById('reg1');
    const reg2 = document.getElementById('reg2');
    const reg3 = document.getElementById('reg3');
    const reg4 = document.getElementById('reg4');

    const regionsDiv = [reg1, reg2, reg3, reg4];

    mapForm.style.display = "none";
    mapPref.style.display = "none";


    let blockBackColour = '#ff3d00';
    let neutralBackColour = '#00b0ff';
    let PreferBackColour = '#64dd17';
    let AnyBackColour = 'white';

    let chooseIconColour = 'white';
    let notChooseIconColour = 'black';


    mapSwitch.addEventListener('change', function () {
        // Check the current state of the switch
        if (mapSwitch.checked) {
            prefForm.style.display = "none";
            mapForm.style.display = "block";
            mapPref.style.display = "block";

            regionsDiv.forEach(function (region) {
                region.style.display = "none";
            });
        } else {
            prefForm.style.display = "block";
            mapForm.style.display = "none";
            mapPref.style.display = "none";
        }
    });


    document.addEventListener('DOMContentLoaded', function() {
        for (let i = 0; i < tags.length; i++) {
            if(user_tags_pref.includes(tags[i].id)){
                createTag(tags[i].id ,tags[i].name, 1)
            }
            else if(user_tags_block.includes(tags[i].id)){
                createTag(tags[i].id ,tags[i].name, -1)
            }
            else {
                createTag(tags[i].id ,tags[i].name, 0)
            }
        }
        for (let i = 0; i < regions.length; i++) {
            createRegion(regions[i].id, regions[i].name);
        }
        for (let i = 0; i < user_regions.length; i++) {
            selectDivRegions.selectedIndex = user_regions[i].id;
        }
    });

    //choosedOption -1 blokovat, 0 neutral, 1 je preferuje
    function createTag(tagId, tagName, choosedOption){
        // Create div elements
        const preferenciaOznacenieTelo = document.createElement("div");
        preferenciaOznacenieTelo.classList.add("preferencia_oznacenie_telo");
        preferenciaOznacenieTelo.id = 'pref' + tagId;

        var hiddenInput = document.createElement("input");
        hiddenInput.name = 'user_tags[]';
        hiddenInput.type = 'number';
        hiddenInput.value = choosedOption;
        hiddenInput.style.display = "none";
        preferenciaOznacenieTelo.appendChild(hiddenInput);

        const sportParagraph = document.createElement("p");
        sportParagraph.textContent = tagName;

        const preferenciaOznacenieIkony = document.createElement("div");
        preferenciaOznacenieIkony.classList.add("preferencia_oznacenie_ikony");

        const zakazIkona = document.createElement("div");
        zakazIkona.classList.add("preferencia_oznacenie_ikona_zakaz");

        const zakazFontAwesome = document.createElement("i");
        zakazFontAwesome.classList.add("fa", "fa-ban", "fa-2x");

        zakazIkona.appendChild(zakazFontAwesome);
        zakazIkona.addEventListener('click', function(){setHiddenInput(preferenciaOznacenieTelo, -1)});

        const neutralIkona = document.createElement("div");
        neutralIkona.classList.add("preferencia_oznacenie_ikona_neutral");

        const neutralFontAwesome = document.createElement("i");
        neutralFontAwesome.classList.add("fa", "fa-minus", "fa-2x");

        neutralIkona.appendChild(neutralFontAwesome);
        neutralIkona.addEventListener('click', function(){setHiddenInput(preferenciaOznacenieTelo, 0)});

        const oblubenaIkona = document.createElement("div");
        oblubenaIkona.classList.add("preferencia_oznacenie_ikona_oblubena");

        const oblubenaFontAwesome = document.createElement("i");
        oblubenaFontAwesome.classList.add("fa", "fa-heart", "fa-2x");

        zakazIkona.addEventListener("click", () =>
            chooseTagStatus(zakazIkona, zakazFontAwesome, neutralIkona, neutralFontAwesome, oblubenaIkona, oblubenaFontAwesome, -1)
        );
        neutralIkona.addEventListener("click", () =>
            chooseTagStatus(zakazIkona, zakazFontAwesome, neutralIkona, neutralFontAwesome, oblubenaIkona, oblubenaFontAwesome, 0)
        );
        oblubenaIkona.addEventListener("click", () =>
            chooseTagStatus(zakazIkona, zakazFontAwesome, neutralIkona, neutralFontAwesome, oblubenaIkona, oblubenaFontAwesome, 1)
        );

        oblubenaIkona.appendChild(oblubenaFontAwesome);
        oblubenaIkona.addEventListener('click', function(){setHiddenInput(preferenciaOznacenieTelo, 1)});

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
        if(choosedOption === -1){
            zakazIkona.style.backgroundColor = blockBackColour;
            zakazFontAwesome.style.color = chooseIconColour;
            neutralIkona.style.backgroundColor = AnyBackColour;
            neutralFontAwesome.style.color = notChooseIconColour;
            oblubenaIkona.style.backgroundColor = AnyBackColour;
            oblubenaFontAwesome.style.color = notChooseIconColour;
        }else if(choosedOption === 0){
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




    function setHiddenInput(fatherDiv, value){
        const inputElement = fatherDiv.querySelector('input');
        inputElement.value = value;
    }

    //map
    function createRegion(regId, regName){
        var newRegion = document.createElement("option");
        newRegion.value=regId;
        newRegion.textContent=regName;
        selectDivRegions.appendChild(newRegion);
    }

    function pridajRegion() {

        if(selectedRegions.childElementCount <5){


            // Get the selected option
            var selectedOption = selectDivRegions.options[selectDivRegions.selectedIndex];

            // Create a new div element
            var newTag = document.createElement("div");
            var newHiddenInput = document.createElement("input");

            // Set the text content of the new div to the selected option's text
            newTag.className = 'pridaj_prispevok_vybrate_oznacenie';
            newTag.textContent = selectedOption.text;
            newHiddenInput.value = selectedOption.value;
            newHiddenInput.name = 'user_regions[]';
            newHiddenInput.type = 'number';


            // Append the new div to the output div
            selectedRegions.appendChild(newTag);
            hiddenInputsDivRegions.append(newHiddenInput);

            newTag.addEventListener("click", function() {
                // Show the corresponding option
                selectedOption.style.display = "block";

                hiddenInputsDivRegions.removeChild(newHiddenInput);
                // Remove the created div
                selectedRegions.removeChild(newTag);
            });
        }
    }

    function hideSelectedOption() {
        // Get the select element
        if(selectedRegions.childElementCount < 5){

            // Get the selected option
            var selectedOption = selectDivRegions.options[selectDivRegions.selectedIndex];

            // Hide the selected option
            selectedOption.style.display = "none";
        }
    }

    // Attach the createDiv function to the change event of the select element
    document.getElementById("select_regiony").addEventListener("change", pridajRegion);
</script>
</body>
</html>
