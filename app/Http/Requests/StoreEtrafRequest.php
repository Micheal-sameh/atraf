<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEtrafRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // If user is not admin, set user_id to authenticated user
        if (! $this->user()->hasRole('admin')) {
            $this->merge([
                'user_id' => $this->user()->id,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'father_id' => 'required|exists:users,id',
            'date' => [
                'required',
                'date',
                'after_or_equal:today',
                // User cannot have 2 etraf on the same day
                function ($attribute, $value, $fail) {
                    $userId = $this->user()->hasRole('admin') ? $this->user_id : $this->user()->id;

                    $exists = \App\Models\Etraf::where('user_id', $userId)
                        ->whereDate('date', $value)
                        ->exists();

                    if ($exists) {
                        $fail(__('messages.user_already_has_etraf_today'));
                    }
                },
            ],
            'from' => [
                'required',
                'date_format:H:i',
                // Cannot have 2 users in the same slot time with the same father
                function ($attribute, $value, $fail) {
                    $exists = \App\Models\Etraf::where('father_id', $this->father_id)
                        ->whereDate('date', $this->date)
                        ->where('from', $value)
                        ->where('to', $this->to)
                        ->exists();

                    if ($exists) {
                        $fail(__('messages.slot_already_taken'));
                    }
                },
            ],
            'to' => 'required|date_format:H:i|after:from',
            'notes' => 'nullable|string|max:1000',
        ];

        // Admin can choose user_id, others cannot
        if ($this->user()->hasRole('admin')) {
            $rules['user_id'] = 'required|exists:users,id';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'father_id.required' => __('messages.field_required'),
            'father_id.exists' => __('messages.user_not_found'),
            'user_id.required' => __('messages.field_required'),
            'user_id.exists' => __('messages.user_not_found'),
            'date.required' => __('messages.field_required'),
            'date.after_or_equal' => __('messages.date_in_past'),
            'from.required' => __('messages.field_required'),
            'from.date_format' => __('messages.invalid_time_format'),
            'to.required' => __('messages.field_required'),
            'to.date_format' => __('messages.invalid_time_format'),
            'to.after' => __('messages.to_must_be_after_from'),
        ];
    }
}
