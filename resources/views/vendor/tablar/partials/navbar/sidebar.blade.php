<aside class="{{$layoutData['cssClasses'] ?? 'navbar navbar-vertical navbar-expand-lg'}}"
       @if(config('tablar.layout_light_sidebar') !== null)
           data-bs-theme="{{ config('tablar.layout_light_sidebar') ? 'light' : 'dark' }}"
    @endif
>
    <div class="sidebar-inner">
        <div class="sidebar-header">
            <div class="sidebar-logo-wrapper">

                <img src="{{ asset('images/antosa.png') }}"
                    class="logo-expand"
                    alt="Logo Antosa">

                <img src="{{ asset('images/logo-icon.png') }}"
                    class="logo-collapse"
                    alt="Logo Icon">

            </div>
            <button id="sidebarToggle" class="sidebar-toggle-btn d-none d-lg-flex">
                <i class="ti ti-layout-sidebar-left-collapse"></i>
            </button>
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
@push('js')
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
    if(window.innerWidth <= 576){

        document.documentElement.classList.remove('sidebar-collapsed');

    }

});
</script>
{{-- <script>
document.addEventListener("mousemove", function(e) {
    document.documentElement.style.setProperty(
        "--mouse-y",
        e.clientY + "px"
    );
});
</script> --}}

<script>
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll('.has-dropdown').forEach(item => {

        const submenu = item.querySelector('.submenu');

        if (!submenu) return;

        let timeout;

        function showMenu() {

            if (!document.documentElement.classList.contains('sidebar-collapsed')) {
                return;
            }

            clearTimeout(timeout);
            item.classList.add('floating-active');
            const rect = item.getBoundingClientRect();

            // reset dulu
            submenu.style.top = '0px';

            submenu.classList.add('show-floating');

            // tinggi submenu
            const submenuHeight = submenu.offsetHeight;

            // posisi default
            let top = rect.top;

            // viewport
            const viewportHeight = window.innerHeight;

            // kalau kebawah keluar layar
            if (top + submenuHeight > viewportHeight - 20) {

                top = viewportHeight - submenuHeight - 20;

            }

            // jangan minus
            if (top < 10) {
                top = 10;
            }

            // submenu.style.top = top + 'px';
            submenu.style.top = `${top}px`;
            submenu.style.left = `${rect.right + 8}px`;

            const submenuWidth = submenu.offsetWidth;

            if(rect.right + submenuWidth > window.innerWidth){
                submenu.style.left =
                    (window.innerWidth - submenuWidth - 10) + 'px';
            }
        }

        function hideMenu() {
            timeout = setTimeout(() => {

                submenu.classList.remove('show-floating');
                item.classList.remove('floating-active');

            }, 200);

        }
        if(window.innerWidth > 576){
            item.addEventListener('mouseenter', showMenu);
            submenu.addEventListener('mouseenter', () => {
                clearTimeout(timeout);
            });
            item.addEventListener('mouseleave', hideMenu);
            submenu.addEventListener('mouseleave', hideMenu);
        }
    });

});
</script>
<script>
document.addEventListener('click', function(e){

    // klik area luar sidebar
    if(!e.target.closest('.navbar-vertical')){

        document.querySelectorAll('.submenu.show-floating')
            .forEach(el => {

                el.classList.remove('show-floating');

            });

        document.querySelectorAll('.floating-active')
            .forEach(el => {

                el.classList.remove('floating-active');

            });

    }

});
</script>
@endpush
<style>

@media (min-width: 992px) and (max-width: 1200px) {

    .navbar.navbar-vertical.navbar-expand-lg {
        width: 80px;
        z-index:20;
    }

    .page-wrapper {
        margin-left: 80px;
    }

    .nav-link-title,
    .logo-expand,
    .submenu-arrow {
        display: none !important;
    }

    .logo-collapse {
        display: block !important;
    }

    /* floating submenu */
    .submenu {
        position: fixed;
        left: 80px;
    }

    .d-none {
        display: none !important;
    }
}

@media (max-width: 991.98px) {

    .navbar.navbar-vertical.navbar-expand-lg {

        position: fixed !important;

        top: 0;
        left: 0;

        transform: translateX(-100%);

        transition: .3s ease;

        width: 260px !important;

        height: 100vh;

        z-index: 1050;

        overflow-y: auto;
    }

    .navbar.navbar-vertical.navbar-expand-lg.mobile-open {
        transform: translateX(0);
    }

    .page-wrapper {
        margin-left: 0 !important;
    }

    /* title tampil */
    .nav-link-title {
        display: inline !important;
    }

    /* arrow tampil */
    .submenu-arrow{
        display:block !important;
    }

    /* submenu mobile jadi accordion */
    .submenu{

        position: static !important;

        width: 100% !important;

        left: unset !important;
        top: unset !important;

        background: transparent !important;

        box-shadow: none !important;

        border-radius: 0 !important;

        padding: 0 !important;

        display: none;
    }

    .submenu.show{
        display:block;
    }

    .nav-link-title{
        display:inline !important;
        opacity:1 !important;
        visibility:visible !important;
    }

}

@media (min-width: 992px){

    .sidebar-collapsed .submenu.show-floating{

        display:block !important;

        position: fixed;

        left:72px;

        width:220px;

        background:#fff;

        border-radius:10px;

        box-shadow:0 10px 30px rgba(0,0,0,.08);

        z-index:99999;

        padding:8px;
    }

}

</style>