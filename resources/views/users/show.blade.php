@extends('layouts.sideBar')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>{{ __('messages.user_details') }}</h3>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>{{ __('messages.id') }}:</strong></div>
                        <div class="col-md-9">{{ $user->id }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>{{ __('messages.name') }}:</strong></div>
                        <div class="col-md-9">{{ $user->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>{{ __('messages.email') }}:</strong></div>
                        <div class="col-md-9">{{ $user->email }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>{{ __('messages.email_verified') }}:</strong></div>
                        <div class="col-md-9">
                            @if($user->email_verified_at)
                                <span class="badge bg-success">{{ __('messages.verified') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('messages.not_verified') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>{{ __('messages.created_at') }}:</strong></div>
                        <div class="col-md-9">{{ $user->created_at->format('Y-m-d H:i:s') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>{{ __('messages.updated_at') }}:</strong></div>
                        <div class="col-md-9">{{ $user->updated_at->format('Y-m-d H:i:s') }}</div>
                    </div>

                    @if($atrafCount > 0)
                    <hr>
                    <h5>{{ __('messages.user_statistics') }}</h5>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>{{ __('messages.total_atraf') }}:</strong></div>
                        <div class="col-md-9">{{ $atrafCount }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
