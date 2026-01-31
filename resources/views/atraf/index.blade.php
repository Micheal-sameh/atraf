@extends('layouts.sideBar')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>{{ __('messages.a3traf') }}</h3>
                    <a href="{{ route('atraf.create') }}" class="btn btn-primary">{{ __('messages.create_etraf') }}</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="GET" action="{{ route('atraf.index') }}" class="mb-3">
                        <div class="row g-2">
                            @if($canSearch)
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="{{ __('messages.search') }}..." value="{{ $search }}">
                            </div>
                            @endif
                            <div class="col-md-2">
                                <select name="status" class="form-control">
                                    <option value="">{{ __('messages.all_status') }}</option>
                                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                                    <option value="waiting" {{ $status == 'waiting' ? 'selected' : '' }}>{{ __('messages.waiting') }}</option>
                                    <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>{{ __('messages.completed') }}</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_from" class="form-control" placeholder="{{ __('messages.date_from') }}" value="{{ $dateFrom }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_to" class="form-control" placeholder="{{ __('messages.date_to') }}" value="{{ $dateTo }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">{{ __('messages.filter') }}</button>
                            </div>
                            @if($search || $status || $dateFrom || $dateTo)
                            <div class="col-md-1">
                                <a href="{{ route('atraf.index') }}" class="btn btn-secondary w-100">{{ __('messages.clear') }}</a>
                            </div>
                            @endif
                        </div>
                    </form>

                    @if($atraf->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.user') }}</th>
                                    <th>{{ __('messages.father') }}</th>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.time') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($atraf as $etraf)
                                <tr>
                                    <td>{{ $etraf->user->name }}</td>
                                    <td>{{ $etraf->father->name }}</td>
                                    <td>{{ $etraf->date->format('Y-m-d') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($etraf->from)->format('H:i') }} - {{ \Carbon\Carbon::parse($etraf->to)->format('H:i') }}</td>
                                    <td>
                                        @php
                                            $statusBadge = match($etraf->status->value) {
                                                'pending' => 'warning',
                                                'waiting' => 'info',
                                                'completed' => 'success',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusBadge }}">{{ $etraf->status->label() }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('atraf.show', $etraf->id) }}" class="btn btn-sm btn-info">{{ __('messages.view') }}</a>

                                        @if((auth()->user()->hasRole('father') || auth()->user()->hasRole('admin')) && $etraf->status->value != 'completed')
                                        <form action="{{ route('atraf.update-status', $etraf->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $etraf->status->value == 'pending' ? 'waiting' : 'completed' }}">
                                            <button type="submit" class="btn btn-sm btn-{{ $etraf->status->value == 'pending' ? 'warning' : 'success' }}">
                                                @if($etraf->status->value == 'pending')
                                                    {{ __('messages.mark_waiting') }}
                                                @else
                                                    {{ __('messages.mark_completed') }}
                                                @endif
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $atraf->links() }}
                    @else
                        <p class="text-center">{{ __('messages.no_data_found') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
