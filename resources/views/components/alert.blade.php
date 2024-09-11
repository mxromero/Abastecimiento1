<div id="{{ $id }}" class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
    {{ $slot }}
</div>

<script>
    setTimeout(function() {
        document.getElementById('{{ $id }}').style.display = 'none';
    }, 5000);
</script>
