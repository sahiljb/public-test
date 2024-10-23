<div class="alert alert-{{ $type }}">
    @if ($type == 'success')
        <i class="fas fa-check-circle"></i>
    @elseif ($type == 'danger')
        <i class="fas fa-exclamation-circle"></i>
    @elseif ($type == 'info')
        <i class="fas fa-info-circle"></i>
    @endif
    {{ $slot }}
</div>
