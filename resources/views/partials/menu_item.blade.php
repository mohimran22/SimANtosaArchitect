

{{-- @foreach($menus as $menu)
        @php
            $hasChildren = isset($menu['children']) && count($menu['children']) > 0;

            $user = auth()->user();
            $userPermissions = App\Helpers\ActiveRole::permissions();
            $activeRole = $user->activeRole;
            $activePermissions = $activeRole
                ? $activeRole->permissions->pluck('name')->toArray()
                : [];

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


            $menu['children'] = $hasChildren
                ? array_filter($menu['children'], function ($child) use ($userPermissions){
                    if (empty($child['permission_name'])) {
                        return true;
                    }
                    foreach (explode('|', $child['permission_name']) as $perm) {
                        if (auth()->user()->can(trim($perm), $userPermissions)) {
                            return true;
                        }
                    }
                    return false;
                })
                : [];

            $hasChildren = count($menu['children']) > 0;

            $isActive = false;

            if ($menu['type'] === 'route') {
                $isActive = request()->routeIs($menu['url']);
            } elseif ($menu['type'] === 'url') {
                $isActive = request()->is(ltrim($menu['url'], '/').'*');
            }

            if (!$isActive && $hasChildren) {
                foreach ($menu['children'] as $child) {
                    if ($child['type'] === 'route' && request()->routeIs($child['url'])) {
                        $isActive = true;
                        break;
                    } elseif ($child['type'] === 'url' && request()->is(ltrim($child['url'], '/').'*')) {
                        $isActive = true;
                        break;
                    }
                }
            }
        @endphp

        @if($canViewParent && ($hasChildren || empty($menu['permission_name']) || $canViewParent))
            <li class="nav-item {{ $hasChildren ? 'has-dropdown' : '' }}">
                <a class="nav-link {{ $isActive ? 'active show' : '' }} {{ $hasChildren ? 'dropdown-toggle' : '' }}"
                    href="{{ $hasChildren ? '#submenu-'.$loop->index : ($menu['type'] === 'route' ? route($menu['url']) : url($menu['url'])) }}"
                    @if($hasChildren) data-bs-toggle="collapse" @endif
                    aria-expanded="{{ $isActive ? 'true' : 'false' }}"
                    aria-controls="submenu-{{ $loop->index }}"
                >
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <i class="{{ $menu['icon'] ?? 'ti ti-circle' }}"></i>
                    </span>

                    <span class="nav-link-title">{{ $menu['text'] }}</span>
                </a>

                @if($hasChildren)
                    <div class="collapse {{ $isActive ? 'show' : '' }}" 
                        id="submenu-{{ $loop->index }}" 
                        data-bs-parent="#sidebar-menu"
                    >
                        <ul class="nav nav-sm flex-column ms-4">
                            @foreach($menu['children'] as $child)
                                @php
                                    $childActive =
                                        $child['type'] === 'route'
                                            ? request()->routeIs($child['url'])
                                            : request()->is(ltrim($child['url'], '/').'*');
                                @endphp

                                <li class="nav-item">
                                    <a href="{{ $child['type'] === 'route' ? route($child['url']) : url($child['url']) }}"
                                        class="nav-link {{ $childActive ? 'active' : '' }}"
                                    >
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
    @endforeach --}}
<ul class="navbar-nav" id="sidebar-menu">
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
        <li class="nav-item {{ $hasChildren ? 'has-dropdown' : '' }}">

            <a class="nav-link {{ $isActive ? 'active show' : '' }} {{ $hasChildren ? 'dropdown-toggle' : '' }}"
               href="{{ $hasChildren ? '#submenu-'.$loop->index : ($menu['type'] === 'route' ? route($menu['url']) : url($menu['url'])) }}"
               @if($hasChildren) data-bs-toggle="collapse" @endif
               aria-expanded="{{ $isActive ? 'true' : 'false' }}"
               aria-controls="submenu-{{ $loop->index }}"
            >
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class="{{ $menu['icon'] ?? 'ti ti-circle' }}"></i>
                </span>

                <span class="nav-link-title">{{ $menu['text'] }}</span>
            </a>

            @if($hasChildren)
                <div class="collapse {{ $isActive ? 'show' : '' }}"
                     id="submenu-{{ $loop->index }}"
                     data-bs-parent="#sidebar-menu"
                >
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
                                >
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

</ul>



<style>
    .navbar-nav .nav-item .nav-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    border-radius: 8px;
    color: #ffff;
    transition: all 0.2s ease-in-out;
    margin: 5px 10px;
    text-decoration: none;
}

/* Hover efek untuk semua menu */
.navbar-nav .nav-item .nav-link:hover {
    background-color: #f1f5f9;
    color: #000;
}

/* MENU UTAMA aktif (warna hitam) */
.navbar-nav > .nav-item > .nav-link.active {
    background-color: #000;
    color: #fff !important;
    font-weight: 500;
}

/* Submenu (anak) */
.collapse .nav-link {
    font-size: 0.9rem;
    padding-left: 2rem;
    color: #333;
    border-radius: 6px;
}

/* SUBMENU aktif (warna abu-abu muda) */
.collapse .nav-link.active {
    background-color: #c4c4c4;
    color: #000;
}

/* Hover di submenu */
.collapse .nav-link:hover {
    background-color: #e4e4e4;
    color: #000;
}

/* Ikon */
.nav-link-icon {
    font-size: 1.2rem;
}

/* Animasi collapse */
.collapse {
    transition: height 0.2s ease;
}

</style>


