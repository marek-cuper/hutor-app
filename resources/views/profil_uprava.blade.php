<!DOCTYPE html>
<html lang="en">
<head>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset("/css/main.css")}}" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>Title</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>

<body>

@include('include.navbar')

<div class="domov_prispevok">
    <div class="pridaj_prispevok_telo" onclick="">
        <div class="profil_uprava_kontajner">
            <div class="profil_uprava_kontajner_cast">
                <div class="profil_uprava_profil_obrazok">
                    <img id="profil_obrazok" src="">
                </div>
                <form id="profil_uprava_profil_obrazok_vstup" class="profil_uprava_profil_obrazok_vstup" action="{{ route('uloz_obrazok.post') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <label><b>Vyber profilovu fotku</b></label>
                    <input type="file" name="profil_obrazok_vstup" id="profil_obrazok_vstup" class="form-control-file">
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    <div class="preferencie_tlacitka_okno">
                        <button type="submit" class="preferencie_tlacitko_uloz">Uloz</button>
                        <button onclick="resetFunction()" class="preferencie_tlacitko_reset">Reset</button>
                    </div>

                </form>

            </div>

        </div>

    </div>
</div>

@include('include.footbar')

<script>
    var hiddenImageNameInput;

    var user_profile_image = @json(session('user_profile_image'));

    const profileImageImg = document.getElementById('profil_obrazok');
    const profileImageInput = document.getElementById('profil_obrazok_vstup');
    const formProfileImageInput = document.getElementById('profil_uprava_profil_obrazok_vstup');


    document.addEventListener('DOMContentLoaded', function() {
        profileImageImg.src = '/storage/' + user_profile_image;

        hiddenImageNameInput = document.createElement('input');
        hiddenImageNameInput.value = user_profile_image;
        hiddenImageNameInput.name = 'profile_image_name';
        hiddenImageNameInput.type = 'text';
        hiddenImageNameInput.style.display = 'none';
        formProfileImageInput.appendChild(hiddenImageNameInput);

    });

    $('#profil_obrazok_vstup').on('input', function(event) {
        event.preventDefault(); // Prevent the default button click behavior

        if (profileImageInput.value.trim() !== '') {
            var imageData = $('#profil_obrazok_vstup')[0].files[0]; // Get the selected image file

            var formData = new FormData(); // Create FormData object
            formData.append('image', imageData); // Append the image file to FormData

            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            // Set the CSRF token in the request headers
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            // Make AJAX request
            $.ajax({
                url: '/profil_uprava/pridaj_obrazok', // Replace with your server endpoint
                method: 'POST',
                data: formData,
                contentType: false, // Important: set contentType to false
                processData: false, // Important: set processData to false
                success: function(response) {
                    // Handle success response
                    profileImageImg.src = '/storage/' + response.imageName;
                    hiddenImageNameInput.value = response.imageName;
                },
                error: function(error) {
                    // Handle error
                    alert('Error');
                }
            });
        }
    });

    function resetFunction(){
        profileImageImg.src = '/storage/' + user_profile_image;
        hiddenImageNameInput.value = user_profile_image;
    }
</script>

</body>
</html>
