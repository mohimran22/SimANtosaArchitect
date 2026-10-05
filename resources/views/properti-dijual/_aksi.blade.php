<div class="text-nowrap">
    <a href="{{ route('jual.edit', $row) }}" class="btn btn-sm btn-outline-primary">Edit</a>
    <form action="{{ route('jual.destroy', $row) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus properti ini?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm btn-outline-danger">Hapus</button>
    </form>
</div>