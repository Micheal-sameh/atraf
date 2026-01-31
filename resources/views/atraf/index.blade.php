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
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="{{ __('messages.search') }}..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">{{ __('messages.all_status') }}</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                                    <option value="waiting" {{ request('status') == 'waiting' ? 'selected' : '' }}>{{ __('messages.waiting') }}</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('messages.completed') }}</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">{{ __('messages.filter') }}</button>
                            </div>
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
                                        @if($etraf->status == 'pending')
                                            <span class="badge bg-warning">{{ __('messages.pending') }}</span>
                                        @elseif($etraf->status == 'waiting')
                                            <span class="badge bg-info">{{ __('messages.waiting') }}</span>
                                        @else
                                            <span class="badge bg-success">{{ __('messages.completed') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('atraf.show', $etraf->id) }}" class="btn btn-sm btn-info">{{ __('messages.view') }}</a>
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
