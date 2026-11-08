<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if (! $this->has('court_ids') && $this->filled('court_id')) {
            $this->merge([
                'court_ids' => [$this->input('court_id')],
            ]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'court_ids.required' => 'Select at least one court to reserve.',
            'court_ids.min' => 'Select at least one court to reserve.',
            'court_ids.*.exists' => 'One or more selected courts are no longer active. Choose different courts.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'court_ids' => ['required', 'array', 'min:1'],
            'court_ids.*' => ['required', 'integer', 'distinct', 'exists:courts,id,is_active,1'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'duration_hours' => ['required', 'integer', 'min:1', 'max:15'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'equipment' => ['nullable', 'array'],
            'equipment.*' => ['nullable', 'integer', 'min:0', 'max:20'],
        ];
    }
}
