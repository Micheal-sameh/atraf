@extends('layouts.sideBar')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>{{ __('messages.create_etraf') }}</h3>
                    <a href="{{ route('atraf.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('atraf.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="father_id" class="form-label">{{ __('messages.father') }}</label>
                            <select name="father_id" id="father_id" class="form-control @error('father_id') is-invalid @enderror" required>
                                <option value="">{{ __('messages.select_father') }}</option>
                                @foreach($fathers as $father)
                                    <option value="{{ $father->id }}" {{ old('father_id') == $father->id ? 'selected' : '' }}>
                                        {{ $father->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('father_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="user_id" class="form-label">{{ __('messages.user') }}</label>
                            <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                <option value="">{{ __('messages.select_user') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">{{ __('messages.date') }}</label>
                            <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" min="{{ date('Y-m-d') }}" required>
                            @error('date')
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
                            <label for="notes" class="form-label">{{ __('messages.notes') }}</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('messages.create_etraf') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
