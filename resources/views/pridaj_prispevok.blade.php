@include('include.head')

<body>

@include('include.navbar')

<div class="domov_prispevok">
    <div class="pridaj_prispevok_telo" onclick="">
        <div class="pridaj_prispevok_formular">

            <form action="{{ route('pridaj_prispevok.post') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="pridaj_prispevok_formular_kolonka">
                    <label><b>Nadpis</b></label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
                <div class="pridaj_prispevok_formular_kolonka">
                    <label><b>Text</b></label>
                    <textarea name="text" id="text" class="form-control" rows="4" required></textarea>
                </div>
                <div class="pridaj_prispevok_formular_kolonka">
                    <label><b>Obrazok</b></label>
                    <input type="file" name="images[]" id="images" class="form-control-file" multiple>
                </div>



                <div class="pridaj_prispevok_formular_kolonka">
                    <div class="pridaj_prispevok_anketa_prepinac">
                        <label><b>Anketa</b></label>
                        <input type="checkbox" id="checkBoxPoll">
                    </div>
                    <div class="pridaj_prispevok_anketa_prepinac" id="pridaj_prispevok_anketa_otazka">
                        <label>Otazka do ankety</label>
                        <input name="poll_question" id="poll_question" class="form-control" rows="1">
                    </div>
                    <div class="pridaj_prispevok_anketa_prepinac" id="pridaj_prispevok_anketa_prepinac_obrazok">
                        <label>Obrazoky v ankete</label>
                        <input type="checkbox" name="checkBoxPollImage" id="checkBoxPollImage">
                    </div>
                    <div class="pridaj_prispevok_anketa_pridaj_moznost" id="pridaj_prispevok_anketa_pridaj_moznost">
                        <label><b>Moznost v ankete</b></label>
                        <div class="pridaj_prispevok_anketa_prepinac">
                            <label>Text</label>
                            <textarea name="option_text" id="option_text" class="form-control" rows="1"></textarea>
                        </div>
                        <div class="pridaj_prispevok_anketa_prepinac" id="pridaj_prispevok_anketa_pridaj_obrazok">
                            <label>Obrazok</label>
                            <input type="file" name="option_image" id="option_image" class="form-control-file">
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                        </div>
                        <button class="btn btn-primary" id="poll_option_button">Pridaj možnosť</button>
                    </div>


                </div>
                <div class="pridaj_prispevok_formular_kolonka" id="pridaj_prispevok_anketa_pridane_moznosti">
                    <label><b>Možnosti ankety</b></label>
                    <div id="pridaj_prispevok_anketa_moznosti" class="pridaj_prispevok_anketa_moznosti">

                    </div>
                    <div id="pridaj_prispevok_anketa_skryte_moznosti" style="display: none;">

                    </div>
                </div>


                <div class="pridaj_prispevok_formular_kolonka">
                    <label><b>Označenia príspevku</b></label>
                    <select id="pridaj_prispevok_select_oznacenia" onchange="hideSelectedOptionTags()">
                        <option value="" disabled selected>Vyber označenia(max 5)</option>
                    </select>
                </div>
                <div class="pridaj_prispevok_formular_kolonka">
                    <label><b>Vybraté označenia</b></label>
                    <div id="pridaj_prispevok_vybrate_oznacenia">

                    </div>
                    <div id="pridaj_prispevok_skryte_oznacenia" style="display: none;">

                    </div>
                </div>

                <div class="pridaj_prispevok_formular_kolonka">
                    <label><b>Regiony príspevku</b></label>
                    <select id="pridaj_prispevok_select_regiony" onchange="hideSelectedOptionRegions()">
                        <option value="" disabled selected>Vyber regiony(max 4)</option>
                    </select>
                </div>
                <div class="pridaj_prispevok_formular_kolonka">
                    <label><b>Vybraté regióny</b></label>
                    <div id="pridaj_prispevok_vybrate_regiony">

                    </div>
                    <div id="pridaj_prispevok_skryte_regiony" style="display: none;">

                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Vytvor príspevok</button>
                <div class="empty_space"></div>
            </form>

        </div>
    </div>
</div>

@include('include.footbar')

<script>

    var tags = @json(session('tags'));
    var regions = @json(session('regions'));

    var selectDivTags = document.getElementById("pridaj_prispevok_select_oznacenia");
    var hiddenInputsDivTags = document.getElementById("pridaj_prispevok_skryte_oznacenia");
    var selectedTags = document.getElementById("pridaj_prispevok_vybrate_oznacenia");

    var selectDivRegions = document.getElementById("pridaj_prispevok_select_regiony");
    var hiddenInputsDivRegions = document.getElementById("pridaj_prispevok_skryte_regiony");
    var selectedRegions = document.getElementById("pridaj_prispevok_vybrate_regiony");

    //for checkboxs
    var checkboxPoll = document.getElementById('checkBoxPoll');
    var checkboxPollImg = document.getElementById('checkBoxPollImage');

    var pollOptionQuestion = document.getElementById("pridaj_prispevok_anketa_otazka");
    var pollOptionImageCheckBox = document.getElementById("pridaj_prispevok_anketa_prepinac_obrazok");
    var pollAddOption = document.getElementById("pridaj_prispevok_anketa_pridaj_moznost");
    var pollAddedOptionsForm = document.getElementById("pridaj_prispevok_anketa_pridane_moznosti");
    var pollAddedOptionWithImage = document.getElementById("pridaj_prispevok_anketa_pridaj_obrazok");

    var pollOptionTextInput = document.getElementById("option_text");
    var pollOptionImageInput = document.getElementById("option_image");
    var pollOptionsDiv = document.getElementById("pridaj_prispevok_anketa_moznosti");
    var hiddenInputPollOptions = document.getElementById("pridaj_prispevok_anketa_skryte_moznosti");

    document.addEventListener('DOMContentLoaded', function() {
        //Add all tags options
        for (let i = 0; i < tags.length; i++) {
            createTag(tags[i].id, tags[i].name)
        }
        selectDivTags.selectedIndex = "";

        //Add all regions option
        for (let i = 0; i < regions.length; i++) {
            createRegion(regions[i].id, regions[i].name);
        }
        selectDivRegions.selectedIndex = "";

        checkboxPoll.addEventListener('change', function() {
            if (this.checked) {
                pollOptionQuestion.style.display = 'flex';
                pollOptionImageCheckBox.style.display = 'flex';
                pollAddOption.style.display = 'block';
                pollAddedOptionsForm.style.display = 'grid';
                changePollOptionImage();
            }else{
                pollOptionQuestion.style.display = 'none';
                pollOptionImageCheckBox.style.display = 'none';
                pollAddOption.style.display = 'none';
                pollAddedOptionsForm.style.display = 'none';
            }
        });
        checkboxPollImg.addEventListener('change', function() {
            changePollOptionImage();
        });
    });

    //If your layer is update to visible lower layer will automaticly go visible too, so we need there update automaticly lower layer to change his visibility depends on checkbox

    function changePollOptionImage(){
        var images = pollOptionsDiv.querySelectorAll("img");
        if (checkboxPollImg.checked) {
            pollAddedOptionWithImage.style.display = 'flex';
            images.forEach(function(image) {
                image.style.display = 'flex'; // Set the new src attribute here
            });
        }else{
            pollAddedOptionWithImage.style.display = 'none';
            images.forEach(function(image) {
                image.style.display = 'none'; // Set the new src attribute here
            });
        }
        hiddenInputPollOptions.style.display = 'none';
    }


    $('#poll_option_button').click(function(event) {
        event.preventDefault();

        if(checkboxPollImg.checked){
            var imageData = $('#option_image')[0].files[0];

            var formData = new FormData();
            formData.append('image', imageData);

            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            $.ajax({
                url: '/pridaj_prispevok/pridaj_moznost_anketa',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // Handle success response
                    createPollOption(pollOptionTextInput.value, response.imageName)
                    pollOptionTextInput.value = '';
                    pollOptionImageInput.value = '';
                },
                error: function(error) {
                    alert('Je potrebne vybrat obrazok pre moznost v ankete');
                }
            });
        }else {
            createPollOption(pollOptionTextInput.value, null)
            pollOptionTextInput.value = '';
            pollOptionImageInput.value = '';
        }


    });


    function createPollOption(text, imageName){
        var index = pollOptionsDiv.childElementCount;

        const polloptionDiv = document.createElement('div');
        polloptionDiv.id = 'pridaj_prispevok_anketa_moznost' + index;
        polloptionDiv.className = 'pridaj_prispevok_anketa_moznost';
        var hiddenpollOptionText = document.createElement("input");
        hiddenpollOptionText.value = text;
        hiddenpollOptionText.name = 'poll_text[]';
        hiddenpollOptionText.type = 'text';
        hiddenInputPollOptions.appendChild(hiddenpollOptionText);

        const polloptionImg = document.createElement('img');
        if(imageName !== null){
            polloptionImg.src = '/storage/' + imageName;
        }
        var hiddenpollOptionImg = document.createElement("input");
        hiddenpollOptionImg.value = imageName;
        hiddenpollOptionImg.name = 'poll_images[]';
        hiddenpollOptionImg.type = 'text';
        hiddenInputPollOptions.appendChild(hiddenpollOptionImg);

        const polloptionP = document.createElement('p');
        polloptionP.textContent = text;

        const polloptionI = document.createElement('i');
        polloptionI.className = 'fa fa-times fa-2x';

        polloptionI.addEventListener('click', function() {
            // Remove the polloptionDiv when the delete icon is clicked
            pollOptionsDiv.removeChild(polloptionDiv);
            hiddenInputPollOptions.removeChild(hiddenpollOptionText);
            hiddenInputPollOptions.removeChild(hiddenpollOptionImg);
        });

        polloptionDiv.appendChild(polloptionImg);
        polloptionDiv.appendChild(polloptionP);
        polloptionDiv.appendChild(polloptionI);

        pollOptionsDiv.appendChild(polloptionDiv);
    }

    function createTag(tagId, tagName){
        var newTag = document.createElement("option");
        newTag.value=tagId;
        newTag.textContent=tagName;
        selectDivTags.appendChild(newTag);
    }

    function createRegion(regId, regName){
        var newRegion = document.createElement("option");
        newRegion.value=regId;
        newRegion.textContent=regName;
        selectDivRegions.appendChild(newRegion);
    }


    function pridajOznacenie() {

        if(selectedTags.childElementCount < 5){
            // Get the select element
            var selectElement = document.getElementById("pridaj_prispevok_select_oznacenia");

            // Get the selected option
            var selectedOption = selectElement.options[selectElement.selectedIndex];

            // Create a new div element
            var newTag = document.createElement("div");
            var newHiddenInput = document.createElement("input");

            // Set the text content of the new div to the selected option's text
            newTag.className = 'pridaj_prispevok_vybrate_oznacenie';
            newTag.textContent = selectedOption.text;
            newHiddenInput.value = selectedOption.value;
            newHiddenInput.name = 'tags[]';
            newHiddenInput.type = 'number';


            // Append the new div to the output div
            selectedTags.appendChild(newTag);
            hiddenInputsDivTags.append(newHiddenInput);

            newTag.addEventListener("click", function() {
                // Show the corresponding option
                selectedOption.style.display = "block";

                hiddenInputsDivTags.removeChild(newHiddenInput);
                // Remove the created div
                selectedTags.removeChild(newTag);
            });
        }

    }

    function pridajRegion() {

        if(selectedRegions.childElementCount < 4){


            // Get the selected option
            var selectedOption = selectDivRegions.options[selectDivRegions.selectedIndex];

            // Create a new div element
            var newReg = document.createElement("div");
            var newHiddenInput = document.createElement("input");

            // Set the text content of the new div to the selected option's text
            newReg.className = 'pridaj_prispevok_vybraty_region';
            newReg.textContent = selectedOption.text;
            newHiddenInput.value = selectedOption.value;
            newHiddenInput.name = 'regions[]';
            newHiddenInput.type = 'number';


            // Append the new div to the output div
            selectedRegions.appendChild(newReg);
            hiddenInputsDivRegions.append(newHiddenInput);

            newReg.addEventListener("click", function() {
                // Show the corresponding option
                selectedOption.style.display = "block";

                hiddenInputsDivRegions.removeChild(newHiddenInput);
                // Remove the created div
                selectedRegions.removeChild(newReg);
            });
        }
    }

    function hideSelectedOptionTags() {
        if(selectedTags.childElementCount < 5){
            // Get the selected option
            var selectedOption = selectDivTags.options[selectDivTags.selectedIndex];

            // Hide the selected option
            selectedOption.style.display = "none";
        }
    }

    function hideSelectedOptionRegions() {
        if(selectedRegions.childElementCount < 4){
            // Get the selected option
            var selectedOption = selectDivRegions.options[selectDivRegions.selectedIndex];

            // Hide the selected option
            selectedOption.style.display = "none";
        }
    }

    // Attach the createDiv function to the change event of the select element
    document.getElementById("pridaj_prispevok_select_oznacenia").addEventListener("change", pridajOznacenie);
    document.getElementById("pridaj_prispevok_select_regiony").addEventListener("change", pridajRegion);

</script>

</body>
</html>
