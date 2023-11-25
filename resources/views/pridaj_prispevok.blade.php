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

<div class="home_prispevok">
    <div class="home_prispevok_telo" onclick="">
        <div class="prihlasenie_formular">

            <form action="{{ route('pridaj_prispevok.post') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="prihlasenie_formular_kolonka">
                    <label><b>Title</b></label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
                <div class="prihlasenie_formular_kolonka">
                    <label><b>Text</b></label>
                    <textarea name="text" id="text" class="form-control" rows="4" required></textarea>
                </div>
                <div class="prihlasenie_formular_kolonka">
                    <label><b>Image</b></label>
                    <input type="file" name="image" id="image" class="form-control-file">
                </div>
                <div class="prihlasenie_formular_kolonka">
                    <label><b>Nadpis prieskum</b></label>
                    <input type="text" name="poll_text" id="poll_text" class="form-control">
                </div>
                <div class="prihlasenie_formular_kolonka">
                    <label><b>Oznacenie prispevku</b></label>
                    <select id="pridaj_prispevok_select_oznacenia" placeholder="Vyber oznacenie" onchange="hideSelectedOption()">
                        <?php

                        for ($i = 0;
                             $i < $tags->count();
                             $i++) {
                            ?>
                        <option value={{$tags[$i]->id}}>{{$tags[$i]->name}}</option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="prihlasenie_formular_kolonka">
                    <label><b>Vybrate oznacenia</b></label>
                    <div id="pridaj_prispevok_vybrate_oznacenia">

                    </div>
                    <div id="pridaj_prispevok_skryte_oznacenia" style="display: none;">

                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Create Post</button>
            </form>

        </div>
    </div>
</div>

@include('include.footbar')

<script>

    var hiddenInputsDiv = document.getElementById("pridaj_prispevok_skryte_oznacenia");

    function pridajOznacenie() {
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

        // Get the output div by its ID
        var outputDiv = document.getElementById("pridaj_prispevok_vybrate_oznacenia");

        // Append the new div to the output div
        outputDiv.appendChild(newTag);
        hiddenInputsDiv.append(newHiddenInput);

        newTag.addEventListener("click", function() {
            // Show the corresponding option
            selectedOption.style.display = "block";

            hiddenInputsDiv.removeChild(newHiddenInput);
            // Remove the created div
            outputDiv.removeChild(newTag);
        });
    }

    function hideSelectedOption() {
        // Get the select element
        var selectElement = document.getElementById("pridaj_prispevok_select_oznacenia");

        // Get the selected option
        var selectedOption = selectElement.options[selectElement.selectedIndex];

        // Hide the selected option
        selectedOption.style.display = "none";
    }

    // Attach the createDiv function to the change event of the select element
    document.getElementById("pridaj_prispevok_select_oznacenia").addEventListener("change", pridajOznacenie);

</script>

</body>
</html>
