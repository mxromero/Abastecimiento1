<form action="{{ route('produccion.destroy', $uma) }}" method="POST" style="display: inline;" onclick="confirmarEliminacion(event, {{ $uma }})">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-link text-danger"><i class="fas fa-trash" aria-hidden="true"></i></button>
</form>
