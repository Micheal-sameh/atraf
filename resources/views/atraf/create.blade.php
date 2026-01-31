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

                        @if($isAdmin)
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
                        @endif

                        <div class="mb-3">
                            <label for="date" class="form-label">{{ __('messages.date') }}</label>
                            <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" min="{{ date('Y-m-d') }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="slotContainer" style="display: none;">
                            <label for="slot" class="form-label">{{ __('messages.select_slot') }}</label>
                            <select name="slot" id="slot" class="form-control @error('from') is-invalid @enderror" required>
                                <option value="">{{ __('messages.select_slot') }}</option>
                            </select>
                            <input type="hidden" name="from" id="from_hidden">
                            <input type="hidden" name="to" id="to_hidden">
                            @error('from')
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fatherSelect = document.getElementById('father_id');
    const dateInput = document.getElementById('date');
    const slotContainer = document.getElementById('slotContainer');
    const slotSelect = document.getElementById('slot');
    const fromHidden = document.getElementById('from_hidden');
    const toHidden = document.getElementById('to_hidden');

    function fetchSlots() {
        const fatherId = fatherSelect.value;
        const date = dateInput.value;

        if (!fatherId || !date) {
            slotContainer.style.display = 'none';
            return;
        }

        fetch(`{{ route('atraf.available-slots') }}?father_id=${fatherId}&date=${date}`)
            .then(response => response.json())
            .then(data => {
                slotSelect.innerHTML = '<option value="">{{ __('messages.select_slot') }}</option>';

                if (data.slots && data.slots.length > 0) {
                    data.slots.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = JSON.stringify(slot);
                        option.textContent = `${slot.from} - ${slot.to}`;
                        slotSelect.appendChild(option);
                    });
                    slotContainer.style.display = 'block';
                } else {
                    slotContainer.style.display = 'none';
                    alert('{{ __('messages.no_available_slots') }}');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                slotContainer.style.display = 'none';
            });
    }

    fatherSelect.addEventListener('change', fetchSlots);
    dateInput.addEventListener('change', fetchSlots);

    slotSelect.addEventListener('change', function() {
        if (this.value) {
            const slot = JSON.parse(this.value);
            fromHidden.value = slot.from;
            toHidden.value = slot.to;
        }
    });
});
</script>
@endpush
@endsection
