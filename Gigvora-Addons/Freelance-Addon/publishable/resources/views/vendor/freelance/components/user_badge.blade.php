<div class="d-flex align-items-center gap-2 user-badge">
    <div class="avatar rounded-circle bg-light d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
        <span class="fw-bold">{{ strtoupper(substr($user['name'] ?? 'U',0,1)) }}</span>
    </div>
    <div>
        <div class="fw-semibold">{{ $user['name'] ?? 'User Name' }}</div>
        <small class="text-muted">{{ $user['headline'] ?? 'Role/Headline' }}</small>
    </div>
    @isset($user['rating'])
        <span class="badge bg-warning text-dark ms-auto">★ {{ $user['rating'] }}</span>
    @endisset
</div>
