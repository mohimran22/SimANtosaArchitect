<aside class="{{$layoutData['cssClasses'] ?? 'navbar navbar-vertical navbar-expand-lg'}}"
       @if(config('tablar.layout_light_sidebar') !== null)
           data-bs-theme="{{ config('tablar.layout_light_sidebar') ? 'light' : 'dark' }}"
    @endif
>
    <div class="sidebar-inner">
        {{-- <div class="sidebar-logo-wrapper">
            <h5 class="navbar-brand navbar-brand-autodark d-flex justify-content-center">
                @include('tablar::partials.common.logo')
            </h5>
        </div> --}}
        <div class="sidebar-logo-wrapper">

            <img src="{{ asset('images/antosa.png') }}"
                class="logo-expand"
                alt="Logo Antosa">

            <img src="{{ asset('images/logo-icon.png') }}"
                class="logo-collapse"
                alt="Logo Icon">

        </div>
        {{-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
                aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button> --}}
        {{-- <button class="btn btn-outline-secondary" id="sidebarToggle">
            <i class="ti ti-menu-2"></i>
        </button> --}}
            <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox"id="sidebarToggle">
            </div>
        <div class="navbar-nav flex-row d-lg-none">
            <div class="nav-item d-none d-lg-flex me-3">
                <div class="btn-list">
                    @include('tablar::partials.header.header-button')
                </div>
            </div>
            <div class="d-none d-lg-flex">
                @include('tablar::partials.header.theme-mode')
                @include('tablar::partials.header.notifications')
            </div>
            @include('tablar::partials.header.top-right')
        </div>

        <div class="sidebar-menu-wrapper" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
                @include('partials.menu_item', ['menus' => $menus ?? []])
            </ul>
            {{-- <ul class="navbar-nav pt-lg-3">
                @each('tablar::partials.navbar.dropdown-item',$tablar->menu('sidebar'), 'item')
            </ul> --}}
        </div>
    </div>
</aside>

{{-- @if(config('tablar.layout_enable_top_header'))
    @include('tablar::partials.header.sidebar-top')
@endif --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    const btn = document.getElementById("sidebarToggle");

    if(!btn) return;

    btn.addEventListener("click", function(){

        document.documentElement.classList.toggle("sidebar-collapsed");

        localStorage.setItem(
            "sidebarCollapsed",
            document.documentElement.classList.contains("sidebar-collapsed")
        );

    });

});
</script>
<script>
document.addEventListener("mousemove", function(e) {
    document.documentElement.style.setProperty(
        "--mouse-y",
        e.clientY + "px"
    );
});
</script>