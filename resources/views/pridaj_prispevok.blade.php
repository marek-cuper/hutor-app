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

<div class="domov_prispevok">
    <div class="pridaj_prispevok_telo" onclick="">
        <div class="pridaj_prispevok_formular">

            <form action="{{ route('pridaj_prispevok.post') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="pridaj_prispevok_formular_kolonka">
                    <label><b>Title</b></label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
                <div class="pridaj_prispevok_formular_kolonka">
                    <label><b>Text</b></label>
                    <textarea name="text" id="text" class="form-control" rows="4" required></textarea>
                </div>
                <div class="pridaj_prispevok_formular_kolonka">
                    <label><b>Image</b></label>
                    <input type="file" name="images[]" id="images" class="form-control-file" multiple>
                </div>
                <div class="pridaj_prispevok_formular_kolonka">
                    <label><b>Nadpis prieskum</b></label>
                    <input type="text" name="poll_text" id="poll_text" class="form-control">
                </div>

                <div class="pridaj_prispevok_formular_kolonka">
                    <label><b>Oznacenie prispevku</b></label>
                    <select id="pridaj_prispevok_select_oznacenia" onchange="hideSelectedOptionTags()">
                        <option value="" disabled selected>Vyber oznacenia(max 8)</option>
                    </select>
                </div>
                <div class="pridaj_prispevok_formular_kolonka">
                    <label><b>Vybrate oznacenia</b></label>
                    <div id="pridaj_prispevok_vybrate_oznacenia">

                    </div>
                    <div id="pridaj_prispevok_skryte_oznacenia" style="display: none;">

                    </div>
                </div>

                <div class="pridaj_prispevok_formular_kolonka">
                    <label><b>Oznacenie prispevku</b></label>
                    <select id="pridaj_prispevok_select_regiony" onchange="hideSelectedOptionRegions()">
                        <option value="" disabled selected>Vyber regiony(max 4)</option>
                    </select>
                </div>
                <div class="pridaj_prispevok_formular_kolonka">
                    <label><b>Vybrate regiony</b></label>
                    <div id="pridaj_prispevok_vybrate_regiony">

                    </div>
                    <div id="pridaj_prispevok_skryte_regiony" style="display: none;">

                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Create Post</button>
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
    });

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

        if(selectedTags.childElementCount < 8){
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
        if(selectedTags.childElementCount < 8){
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
