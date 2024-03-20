@include('include.head')

<body>

@include('include.navbar')

<div class="mapa_stranka">
    <div class="mapa_area">

        <img src="{{ asset('images/regions/Slovak_reg_map_trans.png') }}" usemap="#image-map">

        <img id="reg1" src="{{ asset('images/regions/org_reg1.png') }}">
        <img id="reg2" src="{{ asset('images/regions/org_reg2.png') }}">
        <img id="reg3" src="{{ asset('images/regions/org_reg3.png') }}">
        <img id="reg4" src="{{ asset('images/regions/org_reg4.png') }}">

        <map name="image-map">
            <area id="area1" target="" alt="Blava" title="Blava" coords="42,433,50,449,66,453,80,442,92,419,104,426,117,408,112,379,105,359,86,347,66,368,61,387,48,394,29,389,27,405,42,419" shape="poly">
        </map>
    </div>
</div>

@include('include.footbar')

<script>

    const reg1 = document.getElementById('reg1');
    const reg2 = document.getElementById('reg2');
    const reg3 = document.getElementById('reg3');
    const reg4 = document.getElementById('reg4');

    const trans_layer = document.getElementById('trans_layer');

    const regions = [reg1, reg2, reg3, reg4];


    const area1 = document.getElementById('area1');

    area1.addEventListener('click', function () {
        regions[0].style.display = "block";
    });

    document.addEventListener('DOMContentLoaded', function() {
        regions.forEach(function (region) {
            region.style.display = "none";
        });
    });

</script>

</body>
</html>
