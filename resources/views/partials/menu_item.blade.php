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
        @endphp

        @if($canViewParent)
            <li class="nav-item {{ $hasChildren ? 'has-dropdown' : '' }}"
                data-title="{{ $menu['text'] }}">

                <a class="nav-link {{ $isActive ? 'active' : '' }} {{ $hasChildren ? 'dropdown-toggle' : '' }}"
                data-title="{{ $menu['text'] }}"
                href="{{ $hasChildren ? '#' : ($menu['type'] === 'route' ? route($menu['url']) : url($menu['url'])) }}" role="button"
                @if($hasChildren) 
                data-bs-toggle="collapse"
                data-bs-target="#submenu-{{ $loop->index }}"
                @endif
                aria-controls="submenu-{{ $loop->index }}"
                aria-expanded="{{ $isActive ? 'true' : 'false' }}"
                >
                    <span class="nav-link-icon">
                        <i class="{{ $menu['icon'] ?? 'ti ti-circle' }}"></i>
                    </span>

                    <span class="nav-link-title">{{ $menu['text'] }}</span>
                </a>

                @if($hasChildren)
                    <div class="collapse {{ $isActive ? 'show' : '' }}"
                        id="submenu-{{ $loop->index }}">
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

<style>
/* .navbar-nav .nav-item .nav-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    border-radius: 8px;
    color: #ffff;
    transition: all 0.2s ease-in-out;
    text-decoration: none;
}

.navbar-nav .nav-item .nav-link:hover {
    background-color: #f1f5f9;
    color: #000;
}
 */
.navbar-nav > .nav-item > .nav-link.active {
    background-color: #000;
    color: white !important;
    position: relative;
}

/* .collapse .nav-link {
    font-size: 0.9rem;
    padding-left: 2rem;
    color: #333;
    border-radius: 6px;
    white-space: normal !important;
    word-break: break-word;
}

.collapse .nav-link.active {
    background-color: #c4c4c4;
    color: #000;
    font-weight: 500;
}

.collapse .nav-link:hover {
    background-color: #e4e4e4;
    color: #000;
} */

/* ===== SUBMENU CONTAINER ===== */
.collapse .nav {
    margin-left: 0 !important;
    padding-left: 8px;
    border-left: 2px solid #e5e7eb;
}

/* ===== SUBMENU ITEM ===== */
.collapse .nav-item {
    margin: 2px 6px;
}

/* ===== SUBMENU LINK ===== */
.collapse .nav-link {
    font-size: 0.9rem;
    padding: 6px 10px;
    border-radius: 6px;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 10px;
    white-space: normal;
    word-break: break-word;
}

/* hover */
.collapse .nav-link:hover {
    background: #c4c4c4;
    color: #000;
}

/* ===== ACTIVE SUBMENU (rapi, tidak besar) ===== */
.collapse .nav-link.active {
    background: #c4c4c4;
    color: #000;
    font-weight: 500;
    margin: 5px 10px;
}

/* icon anak */
.collapse .nav-link i {
    font-size: 14px;
    width: 18px;
    text-align: center;
}

.collapse {
    transition: height 0.2s ease;
}
.sidebar-collapsed .page-wrapper {
    margin-left: 80px !important;
} 
.sidebar-collapsed .navbar.navbar-vertical {
    width: 80px !important;
}

.sidebar-collapsed .nav-link {
    justify-content: center !important;
    padding: 10px 0 !important;
    position: relative;
}
.nav-link-icon {
    width: 26px;
    min-width: 26px;
    text-align: center;
}

/* icon selalu tampil */
.nav-link-icon i {
    font-size: 20px;
    display: inline-block !important;
}
.sidebar-collapsed .nav-link-icon {
    width: 26px !important;
    min-width: 26px !important;
    margin: 0 auto !important;
    display: block !important;
}

.sidebar-collapsed .nav-link-icon i {
    display: inline-block !important;
    font-size: 20px !important;
}
.sidebar-collapsed .nav-link-title {
    display: none !important;
}

/* Hilangkan submenu */
.sidebar-collapsed .collapse {
    display: none !important;
}

/* Tooltip custom */
.sidebar-collapsed .nav-item {
    position: relative;
}

/* Tooltip hidden default */
.sidebar-collapsed .nav-link::before {
    content: attr(data-title);
    position: fixed;
    left: 80px;
    top: calc(var(--mouse-y, 50%));
    transform: translateY(-50%);
    background: #000;
    color: #fff;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 13px;
    white-space: nowrap;
    z-index: 9999;

    opacity: 0;
    pointer-events: none;
    transition: 0.15s;
}

/* Show tooltip */
.sidebar-collapsed .nav-link:hover::before {
    opacity: 1;
}
</style>