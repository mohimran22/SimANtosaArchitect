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
@push('js')
<script>
document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(el => {
    el.addEventListener('click', function (e) {

        const target = document.querySelector(this.getAttribute('data-bs-target'));

        if(target.classList.contains('show')){
            e.preventDefault();
            target.classList.remove('show');
        }

    });
});
</script>
@endpush
<style>
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
    background-color: #f1f5f9;
    color: #000;
}

.navbar-nav > .nav-item > .nav-link.active {
    background-color: #000;
    color: white !important;
    position: relative;
}

.collapse .nav-link.active {
    background-color: #c4c4c4;
    color: #000;
    font-weight: 500;
}

.collapse .nav-link:hover {
    background-color: #e4e4e4;
    color: #000;
}

/* ===== SUBMENU CONTAINER ===== */
.collapse .nav {
    margin-left: 0 !important;
    padding-left: 8px;
    border-left: 2px solid #e5e7eb;
}

/* ===== SUBMENU ITEM ===== */
.collapse .nav-item {
    margin: 2px 6x;
}

.collapse .nav-link {
    display: flex;
    align-items: center;
    gap: 10px;

    width: 100%;
    min-width: 0;

    padding: 8px 10px;
    border-radius: 6px;

    font-size: 0.9rem;
    color: #374151;

    overflow: hidden;
}

.collapse .nav-link span,
.collapse .nav-link-title {
    flex: 1;
    min-width: 0;

    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap !important;
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
    margin: 0;
}

/* icon anak */
.collapse .nav-link i {
    font-size: 14px;
    width: 16px;
    min-width: 16px;
    text-align: center;
}

.collapse {
    transition: height 0.2s ease;
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
    display: flex !important;
    align-items: center !important;
    justify-content: center;
    overflow: visible !important;
}
.sidebar-collapsed .nav-link-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: auto !important;
    min-width: auto !important;
    margin: 0;
}

.sidebar-collapsed .nav-link-icon i {
    font-size: 18px !important;
    line-height: 1;
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
    position: absolute;
    left: 70px;
    top: 50%;
    transform: translateY(-50%);
    
    background: #111827;
    color: #fff;

    padding: 6px 10px;
    border-radius: 6px;
    font-size: 13px;
    white-space: nowrap;

    opacity: 0;
    visibility: hidden;

    transition: .2s;
}

.sidebar-collapsed .nav-link:hover::before {
    opacity: 1;
    visibility: visible;
}
</style>