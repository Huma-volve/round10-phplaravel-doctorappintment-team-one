@extends('master')

@section('title', 'Show Notifications')

@section('content')
@php
    $role = auth()->user()?->role;
    $routeName = match ($role) {
        'doctor' => 'doctor.notifications.index',
        'admin' => 'admin.notifications.index',
        default => 'notifications.index',
    };
    $isUnread = request()->boolean('unread');
@endphp

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Notifications</h2>

        <div class="d-flex gap-2">
            <a href="{{ route($routeName) }}"
               class="btn btn-sm {{ $isUnread ? 'btn-outline-secondary' : 'btn-primary' }}">
                All
            </a>

            {{-- <a href="{{ route($routeName, ['unread' => 1]) }}"
               class="btn btn-sm {{ $isUnread ? 'btn-primary' : 'btn-outline-secondary' }}">
                Unread
            </a> --}}

            <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-sm btn-success"
                    onclick="return confirm('Mark all notifications as read?')">
                    Mark all as read
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($notifications->count())
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Body</th>
                            <th>Type</th>
                            <th>Channel</th>
                            <th>Status</th>
                            <th>Sent At</th>
                            <th>Details</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notifications as $notification)
                            @php
                                $isUnreadRow = is_null($notification->read_at_utc);
                                $data = is_array($notification->data) ? $notification->data : [];
                            @endphp

                            <tr class="{{ $isUnreadRow ? 'table-warning' : '' }}">
                                <td>{{ $notification->id }}</td>
                                <td>{{ $notification->title }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($notification->body, 90) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $notification->type)) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $notification->channel)) }}</td>
                                <td>
                                    @if($isUnreadRow)
                                        <span class="badge bg-warning text-dark">Unread</span>
                                    @else
                                        <span class="badge bg-success">Read</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($notification->sent_at_utc ?? $notification->created_at)->format('Y-m-d h:i A') }}</td>
                                <td>
                                    @if(!empty($data))
                                        <details>
                                            <summary>View data</summary>
                                            <pre class="mt-2 mb-0 small">{{ json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </details>
                                    @else
                                        <span class="text-muted">No data</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-2">
                                        @if($isUnreadRow)
                                            <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-warning w-100">
                                                    Mark as read
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('notifications.delete', $notification->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger w-100"
                                                onclick="return confirm('Delete this notification?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $notifications->withQueryString()->links() }}
        </div>
    @else
        <div class="alert alert-info">
            No notifications found.
        </div>
    @endif
</div>
@endsection