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
                <button type="submit" class="btn btn-primary">Create Post</button>
            </form>

        </div>
    </div>
</div>


@include('include.footbar')

</body>
</html>
