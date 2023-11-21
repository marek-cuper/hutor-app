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
                    <select id="select-state" placeholder="Vyber oznacenie">
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

                </div>

                <button type="submit" class="btn btn-primary">Create Post</button>
            </form>

        </div>
    </div>
</div>

@include('include.footbar')

<script>


</script>

</body>
</html>
