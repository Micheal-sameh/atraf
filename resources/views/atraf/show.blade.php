@extends('layouts.sideBar')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>{{ __('messages.etraf_details') }}</h3>
                    <a href="{{ route('atraf.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-3"><strong>User:</strong></div>
                        <div class="col-md-9">{{ $etraf->user->name }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Father:</strong></div>
                        <div class="col-md-9">{{ $etraf->father->name }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Date:</strong></div>
                        <div class="col-md-9">{{ $etraf->date->format('Y-m-d') }} ({{ ucfirst($etraf->getDayOfWeek()) }})</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Time:</strong></div>
                        <div class="col-md-9">{{ \Carbon\Carbon::parse($etraf->from)->format('H:i') }} - {{ \Carbon\Carbon::parse($etraf->to)->format('H:i') }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Status:</strong></div>
                        <div class="col-md-9">
                            @if($etraf->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($etraf->status == 'waiting')
                                <span class="badge bg-info">Waiting</span>
                            @else
                                <span class="badge bg-success">Completed</span>
                            @endif
                        </div>
                    </div>

                    @if($etraf->notes)
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Notes:</strong></div>
                        <div class="col-md-9">{{ $etraf->notes }}</div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Created:</strong></div>
                        <div class="col-md-9">{{ $etraf->created_at->format('Y-m-d H:i:s') }}</div>
                    </div>

                    <hr>
                    <h5>Update Status</h5>
                    <form method="POST" action="{{ route('atraf.update-status', $etraf->id) }}" class="mt-3">
                        @csrf
                        @method('PATCH')

                        <div class="btn-group" role="group">
                            <button type="submit" name="status" value="pending" class="btn btn-warning" {{ $etraf->status == 'pending' ? 'disabled' : '' }}>
                                {{ __('messages.set_pending') }}
                            </button>
                            <button type="submit" name="status" value="waiting" class="btn btn-info" {{ $etraf->status == 'waiting' ? 'disabled' : '' }}>
                                {{ __('messages.set_waiting') }}
                            </button>
                            <button type="submit" name="status" value="completed" class="btn btn-success" {{ $etraf->status == 'completed' ? 'disabled' : '' }}>
                                {{ __('messages.set_completed') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
