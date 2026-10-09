{{--
    Partial rekursif untuk anak menu (level 2 dan level 3).
    Dipanggil dari partials/menu_item.blade.php

    Variabel: $items, $userPermissions, $isItemActive (closure), $parentKey
--}}
@foreach($items as $idx => $child)
    @php
        // Filter anak (level 3) berdasarkan permission
        $grandChildren = array_filter($child['children'] ?? [], function ($g) use ($userPermissions) {
            if (empty($g['permission_name'])) return true;

            foreach (explode('|', $g['permission_name']) as $perm) {
                if (in_array(trim($perm), $userPermissions)) {
                    return true;
                }
            }
            return false;
        });

        $hasGrand    = count($grandChildren) > 0;
        $childActive = $isItemActive($child);
        $childType   = $child['type'] ?? 'url';

        $href = $hasGrand
            ? 'javascript:void(0)'
            : ($childType === 'route' ? route($child['url']) : url($child['url']));

        $l3Id = 'submenu-l3-' . md5(($parentKey ?? '') . ($child['text'] ?? '') . $idx);
    @endphp

    <li class="nav-item">
        <a href="{{ $href }}"
           class="nav-link {{ $hasGrand ? ($childActive ? 'open' : '') : ($childActive ? 'active' : '') }}"
           data-title="{{ $child['text'] }}"
           @if($hasGrand) data-submenu-l3="{{ $l3Id }}" @endif>
            <i class="{{ $child['icon'] ?? 'ti ti-point' }} me-2"></i>
            {{ $child['text'] }}
            @if($hasGrand)
                <span class="submenu-arrow"></span>
            @endif
        </a>

        @if($hasGrand)
            <div class="submenu-l3 {{ $childActive ? 'show' : '' }}" id="{{ $l3Id }}">
                <ul class="nav nav-sm flex-column">
                    @include('partials.menu_child', [
                        'items' => $grandChildren,
                        'userPermissions' => $userPermissions,
                        'isItemActive' => $isItemActive,
                        'parentKey' => $l3Id,
                    ])
                </ul>
            </div>
        @endif
    </li>
@endforeach