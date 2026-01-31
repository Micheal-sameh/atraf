<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreFatherScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $fatherId = $this->input('father_id');

        // Admin can create schedule for any father
        if ($user->hasRole('admin')) {
            return true;
        }

        // Father can only create their own schedule
        if ($user->hasRole('father') && $user->id == $fatherId) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'father_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value);
                    if (! $user || ! $user->hasRole('father')) {
                        $fail(__('messages.user_must_be_father'));
                    }
                },
            ],
            'day' => 'required|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'from' => 'required|date_format:H:i',
            'to' => 'required|date_format:H:i|after:from',
            'slot_duration' => 'nullable|integer|min:5|max:120',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'father_id.required' => __('messages.field_required'),
            'father_id.exists' => __('messages.user_not_found'),
            'day.required' => __('messages.field_required'),
            'day.in' => __('messages.invalid_day'),
            'from.required' => __('messages.field_required'),
            'from.date_format' => __('messages.invalid_time_format'),
            'to.required' => __('messages.field_required'),
            'to.date_format' => __('messages.invalid_time_format'),
            'to.after' => __('messages.to_must_be_after_from'),
        ];
    }
}
