    @php
        use App\Helpers\ActiveRole;
        use Spatie\Permission\Models\Permission;

        $user = auth()->user();
        if (!$user) return;
        $isSuperAdmin = $user->hasRole('Super-Admin');

        $userPermissions = $isSuperAdmin
            ? Permission::pluck('name')->toArray()     
            : (ActiveRole::permissions() ?? []);              
    @endphp

    @foreach($menus as $menu)
        @php
            // Jangan ubah $menu['children'] langsung, simpan dulu
            $children = isset($menu['children']) ? $menu['children'] : [];

            // Filter child tanpa mengubah variabel asli
            $filteredChildren = array_filter($children, function ($child) use ($userPermissions) {
                if (empty($child['permission_name'])) return true;

                foreach (explode('|', $child['permission_name']) as $perm) {
                    if (in_array(trim($perm), $userPermissions)) {
                        return true;
                    }
                }
                return false;
            });

            $hasChildren = count($filteredChildren) > 0;

            // Permission parent
            $canViewParent = true;
            if (!empty($menu['permission_name'])) {
                $canViewParent = false;
                foreach (explode('|', $menu['permission_name']) as $perm) {
                    if (in_array(trim($perm), $userPermissions)) {
                        $canViewParent = true;
                        break;
                    }
                }
            }

            // Detect active
            $isActive = false;

            if ($menu['type'] === 'route') {
                $isActive = request()->routeIs($menu['url']);
            } elseif ($menu['type'] === 'url') {
                $isActive = request()->is(ltrim($menu['url'], '/').'*');
            }

            if (!$isActive && $hasChildren) {
                foreach ($filteredChildren as $child) {
                    if ($child['type'] === 'route' && request()->routeIs($child['url'])) {
                        $isActive = true;
                        break;
                    }
                    if ($child['type'] === 'url' && request()->is(ltrim($child['url'], '/').'*')) {
                        $isActive = true;
                        break;
                    }
                }
            }
            $submenuId = 'submenu-' . md5($menu['text'] . $loop->index);
        @endphp

        @if($canViewParent)
            <li class="nav-item {{ $hasChildren ? 'has-dropdown' : '' }}"
                data-title="{{ $menu['text'] }}">
                <a class="nav-link {{ $isActive ? 'active open' : '' }}"
                data-title="{{ $menu['text'] }}"
                data-submenu="{{ $submenuId }}"
                href="{{ $hasChildren 
                ? 'javascript:void(0)' 
                : ($menu['type'] === 'route' 
                    ? route($menu['url']) 
                    : url($menu['url'])) }}"
                >
                    <span class="nav-link-icon">
                        <i class="{{ $menu['icon'] ?? 'ti ti-circle' }}"></i>
                    </span>

                    <span class="nav-link-title">{{ $menu['text'] }}</span>
                    @if($hasChildren)
                        <span class="submenu-arrow"></span>
                    @endif
                </a>

                @if($hasChildren)
                    <div class="submenu {{ $isActive ? 'show' : '' }}"
                        id="{{ $submenuId }}">
                        <ul class="nav nav-sm flex-column ms-4">
                            @foreach($filteredChildren as $child)
                                @php
                                    $childActive = $child['type'] === 'route'
                                        ? request()->routeIs($child['url'])
                                        : request()->is(ltrim($child['url'], '/').'*');
                                @endphp

                                <li class="nav-item">
                                    <a href="{{ $child['type'] === 'route' ? route($child['url']) : url($child['url']) }}"
                                    class="nav-link {{ $childActive ? 'active' : '' }}"
                                    data-title="{{ $child['text'] }}">
                                        <i class="{{ $child['icon'] ?? 'ti ti-point' }} me-2"></i>
                                        {{ $child['text'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </li>
        @endif
    @endforeach
    @push('js')
<script>
document.querySelectorAll('[data-submenu]').forEach(menu => {

    menu.addEventListener('click', function(){

        const submenuId = this.dataset.submenu;

        if(!submenuId) return;

        const submenu = document.getElementById(submenuId);

        submenu.classList.toggle('show');
                this.classList.toggle('open');

    });

});
</script>
@endpush
<style>
.submenu-arrow {
    margin-left: auto;

    width: 8px;
    height: 8px;

    border-right: 2px solid currentColor;
    border-bottom: 2px solid currentColor;

    transform: rotate(45deg);

    transition: .2s ease;
}

/* rotate */
.nav-link.open .submenu-arrow {
    transform: rotate(-135deg);
}

/* hide saat collapsed */
.sidebar-collapsed .submenu-arrow {
    display: none;
}
.navbar-nav .nav-item .nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 8px 6px;
    padding: 8px 12px;
    border-radius: 8px;
    color: #ffff;
    transition: all 0.2s ease-in-out;
    text-decoration: none;
}

.navbar-nav .nav-item .nav-link:hover {
    background-color: #c4c4c4;
    color: #000;
}

.navbar-nav > .nav-item > .nav-link.active {
    background-color: #000;
    color: white !important;
    position: relative;
}

.submenu .nav-link.active {
    background-color: #c4c4c4;
    color: #000;
    font-weight: 400;
    border-radius: 8px;
    margin: 0;
    position: relative;
}

.submenu .nav-link:hover {
    background-color: #e4e4e4;
    color: #000;
}

/* ===== SUBMENU CONTAINER ===== */
.submenu .nav {
    margin-left: 0 !important;
    padding-left: 8px;
}

/* ===== SUBMENU ITEM ===== */
.submenu .nav-item {
    margin: 2px 6px;
}

.submenu .nav-link {
    display: flex;
    align-items: center;
    gap: 10px;

    width: 100%;
    min-width: 0;

    padding: 8px 10px;
    border-radius: 8px;

    font-size: 0.9rem;
    color: #374151;

    overflow: visible;
}

.submenu .nav-link span,
.submenu .nav-link-title {
    flex: 1;
    min-width: 0;

    overflow: visible;
    text-overflow: ellipsis;
    white-space: nowrap !important;
}

/* icon anak */
.submenu .nav-link i {
    font-size: 14px;
    width: 16px;
    min-width: 16px;
    text-align: center;
}
.submenu {
    max-height: 0;
    overflow: visible;

    transition:
        max-height .25s ease,
        opacity .2s ease;

    opacity: 0;
}

.submenu.show {
    max-height: 500px;
    opacity: 1;
}

.nav-link-icon {
    width: 10%;
    min-width: 10%;
    text-align: center;
}

/* icon selalu tampil */
.nav-link-icon i {
    font-size: 20px;
    display: inline-block !important;
}

.sidebar-collapsed .navbar-nav .nav-link {
    display: flex ;
    align-items: center ;
    justify-content: center !important;
    overflow: visible !important;
    padding: 12px 16px !important;
}
.sidebar-collapsed .nav-link-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: auto;
    min-width: 20px;
    margin: 0;
}

.sidebar-collapsed .nav-link-icon i {
    font-size: 18px !important;
    line-height: 1;
}
/* Tooltip custom */
.sidebar-collapsed .nav-item,
.sidebar-collapsed .nav-link {
    overflow: visible !important;
    position: relative;
}

.sidebar-collapsed .navbar-nav > .nav-item > .nav-link::before {
    content: attr(data-title);

    position: fixed;
    top: var(--mouse-y);
    left: 78px;

    transform: translateY(-50%);

    background: #111827;
    color: #fff;

    padding: 8px 12px;

    border-radius: 8px;

    white-space: nowrap;

    opacity: 0;
    visibility: hidden;

    transition: .2s ease;

    z-index: 999999;

    pointer-events: none;
}

.sidebar-collapsed .navbar-nav > .nav-item > .nav-link:hover::before {
    opacity: 1;
    visibility: visible;
}
.sidebar-collapsed .nav-link-title {
    display: none !important;
}

.sidebar-collapsed .submenu {
    position: fixed;
    left: 78px;
    max-height: calc(100vh - 20px);
    overflow-y: auto;
    min-width: 220px;
    background: white;

    border-radius: 12px;

    padding: 10px;

    box-shadow: 0 10px 30px rgba(0,0,0,.12);

    z-index: 99999;

    opacity: 0;
    visibility: hidden;

    transform: translateX(10px);

    transition: none !important;

    pointer-events: none;
}

.sidebar-collapsed .submenu.show-floating {
    opacity: 1;
    visibility: visible;
    transform: translateX(0);
    pointer-events: auto;

    min-width: 190px;
    max-width: 260px;
    padding: 10px;
    overflow: hidden;
    border-radius: 10px;
}

.sidebar-collapsed .submenu.show-floating .nav {
    padding-left: 0 !important;
}

.sidebar-collapsed .submenu.show-floating .nav-item {
    margin: 2px 0;
}

.sidebar-collapsed .submenu.show-floating .nav-link {

    justify-content: flex-start !important;

    padding: 6px 8px !important;

    gap: 8px;

    min-height: 34px;

    border-radius: 8px;

    font-size: 13px;
    font-weight: 400;
}

.sidebar-collapsed .submenu.show-floating .nav-link-icon {
    width: 14px !important;
    min-width: 14px !important;
}

.sidebar-collapsed .submenu.show-floating .nav-link-icon i,
.sidebar-collapsed .submenu.show-floating .nav-link i {
    font-size: 13px !important;
}

.sidebar-collapsed .submenu.show-floating .nav-link-title {
    font-size: 13px;
}
.sidebar-collapsed .nav-item.floating-active > .nav-link::before {
    display: none !important;
}
</style>