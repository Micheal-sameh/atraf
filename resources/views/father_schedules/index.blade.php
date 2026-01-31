@extends('layouts.sideBar')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>{{ __('messages.father_schedules') }}</h3>
                    <a href="{{ route('father-schedules.create') }}" class="btn btn-primary">{{ __('messages.create_schedule') }}</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="GET" action="{{ route('father-schedules.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <select name="father_id" class="form-control">
                                    <option value="">{{ __('messages.all_fathers') }}</option>
                                    @foreach($fathers as $father)
                                        <option value="{{ $father->id }}" {{ request('father_id') == $father->id ? 'selected' : '' }}>
                                            {{ $father->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">{{ __('messages.filter') }}</button>
                            </div>
                        </div>
                    </form>

                    @if($schedules->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.father') }}</th>
                                    <th>{{ __('messages.day') }}</th>
                                    <th>{{ __('messages.from') }}</th>
                                    <th>{{ __('messages.to') }}</th>
                                    <th>{{ __('messages.slot_duration') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->father->name }}</td>
                                    <td>{{ __('messages.' . $schedule->day) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($schedule->from)->format('H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($schedule->to)->format('H:i') }}</td>
                                    <td>{{ $schedule->slot_duration }} {{ __('messages.minutes') }}</td>
                                    <td>
                                        <form action="{{ route('father-schedules.destroy', $schedule->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('messages.confirm') }}')">{{ __('messages.delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $schedules->links() }}
                    @else
                        <p class="text-center">{{ __('messages.no_data_found') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
