@extends('admin.layouts.admin')

@section('page-title', 'Audit Trail & Log Aktivitas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Riwayat Aktivitas Sistem</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-vcenter table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>User</th>
                                <th>Role</th>
                                <th>Aksi</th>
                                <th>Deskripsi</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr>
                                <td class="text-nowrap">{{ $log->created_at->format('d M Y, H:i') }}</td>
                                <td class="fw-bold">{{ $log->user_name ?? '-' }}</td>
                                <td>
                                    @if($log->role == 'superadmin')
                                        <span class="badge bg-danger">Superadmin</span>
                                    @elseif($log->role == 'admin')
                                        <span class="badge bg-primary">Admin</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($log->role) }}</span>
                                    @endif
                                </td>
                                <td><span class="badge bg-info">{{ $log->action }}</span></td>
                                <td>{{ $log->description }}</td>
                                <td><small class="text-muted">{{ $log->ip_address }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada riwayat aktivitas yang tercatat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
