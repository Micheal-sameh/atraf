@extends('layouts.sideBar')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>{{ __('messages.create_schedule') }}</h3>
                    <a href="{{ route('father-schedules.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('father-schedules.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="father_id" class="form-label">{{ __('messages.father') }}</label>
                            <select name="father_id" id="father_id" class="form-control @error('father_id') is-invalid @enderror" required {{ count($fathers) == 1 ? 'readonly' : '' }}>
                                <option value="">{{ __('messages.select_father') }}</option>
                                @foreach($fathers as $father)
                                    <option value="{{ $father->id }}" {{ (old('father_id') == $father->id || (count($fathers) == 1 && $father->id == auth()->id())) ? 'selected' : '' }}>
                                        {{ $father->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('father_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="day" class="form-label">{{ __('messages.day') }}</label>
                            <select name="day" id="day" class="form-control @error('day') is-invalid @enderror" required>
                                <option value="">{{ __('messages.select_day') }}</option>
                                @foreach($days as $day)
                                    <option value="{{ $day }}" {{ old('day') == $day ? 'selected' : '' }}>
                                        {{ __('messages.' . $day) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('day')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="from" class="form-label">{{ __('messages.from') }}</label>
                            <input type="time" name="from" id="from" class="form-control @error('from') is-invalid @enderror" value="{{ old('from') }}" required>
                            @error('from')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="to" class="form-label">{{ __('messages.to') }}</label>
                            <input type="time" name="to" id="to" class="form-control @error('to') is-invalid @enderror" value="{{ old('to') }}" required>
                            @error('to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="slot_duration" class="form-label">{{ __('messages.slot_duration') }} ({{ __('messages.minutes') }})</label>
                            <input type="number" name="slot_duration" id="slot_duration" class="form-control @error('slot_duration') is-invalid @enderror" value="{{ old('slot_duration', 15) }}" min="5" max="120">
                            @error('slot_duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('messages.create_schedule') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
