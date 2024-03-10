
@guest
    <script>window.location = "{{ route('prihlasenie') }}";</script>
@endguest
<div id="footbar">
    <a class="footbar_icon icon {{ Request::is('domov*') ? 'active_footbar_icon' : 'inactive_footbar_icon' }}" href="{{ route('domov') }}">
        <i class="fa fa-globe fa-3x"></i>
    </a>

    <a class="footbar_icon icon {{ Request::is('prefe   rencie') ? 'active_footbar_icon' : 'inactive_footbar_icon' }}" href="{{ route('preferencie') }}">
        <i class="fa fa-tasks fa-3x"></i>
    </a>

    <a class="footbar_icon icon {{ Request::is('spravy') ? 'active_footbar_icon' : 'inactive_footbar_icon' }}" href="{{ route('spravy_konverzacie') }}">
        <i class="fa fa-comments-o fa-3x"></i>
    </a>

    <a class="footbar_icon icon {{ Request::is('pridaj_prispevok') ? 'active_footbar_icon' : 'inactive_footbar_icon' }}" href="{{ route('pridaj_prispevok') }}">
        <i class="fa fa-plus fa-3x"></i>
    </a>


</div>
