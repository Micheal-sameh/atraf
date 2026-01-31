@extends('layouts.sideBar')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>{{ __('messages.users') }}</h3>
                    <form method="GET" action="{{ route('users.index') }}" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="{{ __('messages.search') }}..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">{{ __('messages.search') }}</button>
                    </form>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.id') }}</th>
                                    <th>{{ __('messages.name') }}</th>
                                    <th>{{ __('messages.email') }}</th>
                                    <th>{{ __('messages.membership_code') }}</th>
                                    <th>{{ __('messages.roles') }}</th>
                                    @if(auth()->user()->hasRole('father'))
                                    <th>{{ __('messages.assigned_to_me') }}</th>
                                    @endif
                                    <th>{{ __('messages.created_at') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->membership_code ?? '-' }}</td>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-primary">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    @if(auth()->user()->hasRole('father'))
                                    <td>
                                        @if($user->fathers->contains(auth()->id()))
                                            <span class="badge bg-success">{{ __('messages.yes') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('messages.no') }}</span>
                                        @endif
                                    </td>
                                    @endif
                                    <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> {{ __('messages.view') }}
                                        </a>
                                        @if(auth()->user()->hasRole('admin'))
                                        <a href="{{ route('users.edit-roles', $user->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-user-tag"></i> {{ __('messages.edit_roles') }}
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $users->links() }}
                    @else
                        <p class="text-center">{{ __('messages.no_data_found') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
