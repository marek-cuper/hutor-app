@include('include.head')

<body>

@include('include.navbar')

<div class="preferencie_stranka">

    <div class="prepinac_medzi_preferenciami_mapou">
        <div class="vseobecne_ikona_pozadie">
            <i class="fa fa-tasks fa-3x"></i>
        </div>

        <!-- Rounded switch -->
        <label class="switch">
            <input type="checkbox" id="mapSwitch">
            <span class="slider"></span>
        </label>

        <div class="vseobecne_ikona_pozadie">
            <i class="fa fa-map fa-3x"></i>
        </div>
    </div>
    <div class="preferencie_stranka_oznacenia" id="preferencie_stranka_oznacenia">
        <form id="pref_form" action="{{ route('preferencie.post') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div id="prepinace_preferencie" class="prepinace_preferencie"></div>

            <div class="preferencie_tlacitka_okno">
                <button type="submit" onclick="resetIndexHome()" class="preferencie_tlacitko_uloz">ULOŽ</button>

                <button class="preferencie_tlacitko_reset">RESET</button>

            </div>
        </form>

    </div>


    <div class="preferencie_stranka_regiony" id="preferencie_stranka_regiony">
        <div id="mapa_preferencie" class="mapa_preferencie">

            <img id="sk_regs" src="{{ asset('images/regions/Slovak_reg_map_trans.png') }}">

            <img id="reg1" class="mapa_regiony" src="{{ asset('images/regions/color_reg1.png') }}">
            <img id="reg2" class="mapa_regiony" src="{{ asset('images/regions/color_reg2.png') }}">
            <img id="reg3" class="mapa_regiony" src="{{ asset('images/regions/color_reg3.png') }}">
            <img id="reg4" class="mapa_regiony" src="{{ asset('images/regions/color_reg4.png') }}">
            <img id="reg5" class="mapa_regiony" src="{{ asset('images/regions/color_reg5.png') }}">
            <img id="reg6" class="mapa_regiony" src="{{ asset('images/regions/color_reg6.png') }}">
            <img id="reg7" class="mapa_regiony" src="{{ asset('images/regions/color_reg7.png') }}">
            <img id="reg8" class="mapa_regiony" src="{{ asset('images/regions/color_reg8.png') }}">
            <img id="reg9" class="mapa_regiony" src="{{ asset('images/regions/color_reg9.png') }}">
            <img id="reg10" class="mapa_regiony" src="{{ asset('images/regions/color_reg10.png') }}">
            <img id="reg11" class="mapa_regiony" src="{{ asset('images/regions/color_reg11.png') }}">
            <img id="reg12" class="mapa_regiony" src="{{ asset('images/regions/color_reg12.png') }}">
            <img id="reg13" class="mapa_regiony" src="{{ asset('images/regions/color_reg13.png') }}">
            <img id="reg14" class="mapa_regiony" src="{{ asset('images/regions/color_reg14.png') }}">
            <img id="reg15" class="mapa_regiony" src="{{ asset('images/regions/color_reg15.png') }}">
            <img id="reg16" class="mapa_regiony" src="{{ asset('images/regions/color_reg16.png') }}">
            <img id="reg17" class="mapa_regiony" src="{{ asset('images/regions/color_reg17.png') }}">
            <img id="reg18" class="mapa_regiony" src="{{ asset('images/regions/color_reg18.png') }}">
            <img id="reg19" class="mapa_regiony" src="{{ asset('images/regions/color_reg19.png') }}">
            <img id="reg20" class="mapa_regiony" src="{{ asset('images/regions/color_reg20.png') }}">
            <img id="reg21" class="mapa_regiony" src="{{ asset('images/regions/color_reg21.png') }}">
            <img id="reg22" class="mapa_regiony" src="{{ asset('images/regions/color_reg22.png') }}">
            <img id="reg23" class="mapa_regiony" src="{{ asset('images/regions/color_reg23.png') }}">
            <img id="reg24" class="mapa_regiony" src="{{ asset('images/regions/color_reg24.png') }}">
            <img id="reg25" class="mapa_regiony" src="{{ asset('images/regions/color_reg25.png') }}">

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
                <button type="submit" onclick="resetIndexHome()" class="preferencie_tlacitko_uloz">ULOŽ</button>

                <button class="preferencie_tlacitko_reset">RESET</button>

            </div>
        </form>
    </div>
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

    const tagPage = document.getElementById('preferencie_stranka_oznacenia');
    const regionPage = document.getElementById('preferencie_stranka_regiony');

    var selectDivRegions = document.getElementById("select_regiony");
    var selectedRegions = document.getElementById("vybrate_regiony");
    var hiddenInputsDivRegions = document.getElementById("skryte_regiony");

    regionPage.style.display = "none";

    let blockBackColour = '#ff3d00';
    let neutralBackColour = '#00b0ff';
    let PreferBackColour = '#64dd17';
    let AnyBackColour = 'white';

    let chooseIconColour = 'white';
    let notChooseIconColour = 'black';


    mapSwitch.addEventListener('change', function () {
        if (mapSwitch.checked) {
            tagPage.style.display = "none";
            regionPage.style.display = "block";

        } else {
            tagPage.style.display = "block";
            regionPage.style.display = "none";
        }
    });


    document.addEventListener('DOMContentLoaded', function() {
        //Add all tags as a option and adding their status from database
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

        //Add all regions as a option and adding choosen regions from database
        for (let i = 0; i < regions.length; i++) {
            createRegion(regions[i].id, regions[i].name);
        }
        for (let i = 0; i < user_regions.length; i++) {
            selectDivRegions.selectedIndex = user_regions[i];
            pridajRegion();
            hideSelectedOption();
        }
        selectDivRegions.selectedIndex = "";
    });

    function resetIndexHome(){
        localStorage.setItem('oldIndex', 0);
    }


    //choosedOption -1 blokovat, 0 neutral, 1 je preferuje
    function createTag(tagId, tagName, choosedOption){
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

        if(selectedRegions.childElementCount < 5){

            var selectedOption = selectDivRegions.options[selectDivRegions.selectedIndex];
            var newReg = document.createElement("div");
            var newHiddenInput = document.createElement("input");

            newReg.className = 'pridaj_prispevok_vybrate_oznacenie';
            newReg.textContent = selectedOption.text;
            newHiddenInput.value = selectedOption.value;
            newHiddenInput.name = 'user_regions[]';
            newHiddenInput.type = 'number';

            selectedRegions.appendChild(newReg);
            hiddenInputsDivRegions.append(newHiddenInput);

            let idRegion = 'reg' + selectedOption.value;
            let mapReg = document.getElementById(idRegion);
            mapReg.style.display = 'block';

            //delete selected
            newReg.addEventListener("click", function() {
                selectedOption.style.display = "block";
                hiddenInputsDivRegions.removeChild(newHiddenInput);
                selectedRegions.removeChild(newReg);
                mapReg.style.display = 'none';
            });

        }
    }

    //hide option in selector
    function hideSelectedOption() {
        if(selectedRegions.childElementCount < 5){
            var selectedOption = selectDivRegions.options[selectDivRegions.selectedIndex];
            selectedOption.style.display = "none";
        }
    }

    //on change selector choose that region
    document.getElementById("select_regiony").addEventListener("change", pridajRegion);
</script>
</body>
</html>
