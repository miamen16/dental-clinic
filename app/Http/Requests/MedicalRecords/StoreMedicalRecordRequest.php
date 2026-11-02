<?php

namespace App\Http\Requests\MedicalRecords;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMedicalRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'exists:patients,id'],
            'dentist_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', User::ROLE_DOCTOR),
            ],
            // optional, but must be one of this patient's appointments and not already have a record
            'appointment_id' => [
                'nullable',
                Rule::exists('appointments', 'id')->where('patient_id', $this->input('patient_id')),
                Rule::unique('medical_records', 'appointment_id')
                    ->whereNull('deleted_at')
                    ->ignore($this->route('medical_record')),
            ],
            'diagnosis' => ['required', 'string', 'max:5000'],
            'clinical_notes' => ['nullable', 'string'],
            'recommendations' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        // Provide more user-friendly names for the fields in error messages
        return [
            'patient_id' => 'patient',
            'dentist_id' => 'doctor',
            'appointment_id' => 'appointment',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        // Provide custom error messages for specific validation rules
        return [
            'appointment_id.exists' => 'The selected appointment does not belong to this patient.',
            'appointment_id.unique' => 'This appointment already has a medical record.',
        ];
    }
}
