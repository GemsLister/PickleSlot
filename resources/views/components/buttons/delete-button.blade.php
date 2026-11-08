<form method="POST" action="{{ $action }}" onsubmit="return confirm('Delete this {{ $type }}?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="rounded-lg border border-red-200 text-red-600 px-2.5 py-1 text-xs font-semibold hover:bg-red-50">
        Delete
    </button>
</form>