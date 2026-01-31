@extends('layouts.sideBar')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>{{ __('messages.my_etraf') }}</h3>
                    <a href="{{ route('atraf.create') }}" class="btn btn-primary">{{ __('messages.create_etraf') }}</a>
                </div>
                <div class="card-body">
                    <!-- Upcoming Etraf -->
                    <h4 class="mb-3">{{ __('messages.upcoming_etraf') }}</h4>
                    @if($upcoming->count() > 0)
                        <table class="table table-striped mb-5">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.father') }}</th>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.time') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcoming as $etraf)
                                <tr>
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
                    @else
                        <p class="text-center mb-5">{{ __('messages.no_upcoming_etraf') }}</p>
                    @endif

                    <!-- History -->
                    <h4 class="mb-3">{{ __('messages.etraf_history') }}</h4>
                    @if($history->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.father') }}</th>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.time') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($history as $etraf)
                                <tr>
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
                    @else
                        <p class="text-center">{{ __('messages.no_etraf_history') }}</p>
                    @endif

                    <div class="mt-3">
                        {{ $atraf->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
