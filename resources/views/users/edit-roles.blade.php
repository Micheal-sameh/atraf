@extends('layouts.sideBar')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>{{ __('messages.edit_user_roles') }}</h3>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('users.update-roles', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label"><strong>{{ __('messages.user') }}:</strong> {{ $user->name }}</label>
                            <p class="text-muted">{{ $user->email }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.assign_roles') }}</label>
                            @foreach($allRoles as $role)
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="roles[]"
                                        value="{{ $role->name }}"
                                        id="role-{{ $role->id }}"
                                        {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label" for="role-{{ $role->id }}">
                                        {{ ucfirst($role->name) }}
                                    </label>
                                </div>
                            @endforeach
                            @error('roles')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('messages.update_roles') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
