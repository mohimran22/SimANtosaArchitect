
<script>
document.querySelectorAll('.progress-table thead tr')
  .forEach(tr => {
    let count = 0;
    tr.querySelectorAll('th').forEach(th => count += th.colSpan || 1);
    console.log(count);
<script>
function applyAutoFreeze() {

    const table = document.querySelector(".progress-table");
    if (!table) return;

    // RESET
    table.querySelectorAll(".sticky-col").forEach(cell => {
        cell.classList.remove("sticky-col");
        cell.style.left = "";
        cell.style.width = "";
    });

    const freezeCount = 6;
    const colgroup = table.querySelectorAll("colgroup col");

    let offsets = [];
    let left = 0;

    for (let i = 0; i < freezeCount; i++) {
        offsets.push(left);
        left += colgroup[i].offsetWidth;
    }

    const rowspanMap = [];

    table.querySelectorAll("tr").forEach(row => {

        let colIndex = 0;

        Array.from(row.children).forEach(cell => {

            while (rowspanMap[colIndex] && rowspanMap[colIndex] > 0) {
                rowspanMap[colIndex]--;
                colIndex++;
            }

            const colspan = parseInt(cell.getAttribute("colspan")) || 1;
            const rowspan = parseInt(cell.getAttribute("rowspan")) || 1;

            if (colIndex < freezeCount) {
                cell.classList.add("sticky-col");
                cell.style.left = offsets[colIndex] + "px";
            }

            if (rowspan > 1) {
                for (let i = 0; i < colspan; i++) {
                    rowspanMap[colIndex + i] = rowspan - 1;
                }
            }

            colIndex += colspan;
        });
    });

    // 🔥 HANDLE ROW CATEGORY KHUSUS
table.querySelectorAll("tr.row-category").forEach(row => {
    const cell = row.querySelector("td");
    if (!cell) return;

    cell.classList.add("sticky-col");
    cell.style.left = "0px";
    cell.style.width = colgroup
        ? Array.from(colgroup).slice(0, freezeCount).reduce((sum, c) => sum + c.offsetWidth, 0) + "px"
        : "";
});
}

document.addEventListener("DOMContentLoaded", applyAutoFreeze);
window.addEventListener("resize", applyAutoFreeze);
</script>


@foreach($menus as $menu)
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
@endforeach

<script>
function markAllAsRead() {
    fetch(`/notifications/read-all`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === 'ok') {
            document.querySelectorAll('.notification-item').forEach(el => el.remove());

            const badge = document.querySelector('#notification-count');
            if (badge) {
                badge.remove();
            }

            const container = document.querySelector('#notification-list');
            if (container) {
                container.innerHTML = `
                    <div class="list-group-item text-center text-muted">
                        Tidak ada notifikasi baru
                    </div>
                `;
            }
            const btn = document.querySelector('#mark-all-btn');
                if (btn) btn.remove();

        }
    });
}
</script>
        // document.addEventListener('input', function(e) {

        //     if (e.target.name !== 'volume') return;
        //     if (!currentRabJob) return;

        //     const volume = parseFloat(e.target.value) || 0;
        //     if (volume <= 0) return;

        //     const harga = parseFloat(currentRabJob.harga ?? currentRabJob.price ?? 0);
            
        //     let profitPercent = globalProfit;
        //     let overheadPercent = globalOverhead;

        //     let total = calculateItemTotal(volume, harga, globalProfit, globalOverhead);

        //     document.getElementById('rab_totalPrice').value = total;
        //     document.getElementById('rab_totalPriceFormatted').value = formatRp(total);

        //     const jobId = currentRabJob.id;

        //     rabItems[jobId] = {
        //         ...currentRabJob,
        //         volume: volume,
        //         base_price: harga,
        //         harga: applyGlobalMarginToUnit(harga),
        //         profit: profitPercent,
        //         overhead: overheadPercent,
        //         total: total
        //     };

        //     renderRabTable();
        // });