<?php

namespace App\Http\Requests\Appointments;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust this based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    //  User::ROLE_DOCTOR
    // User::ROLE_ADMIN
    public function rules(): array
    {
        return [
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'patient_id' => 'required|exists:patients,id',
            // only active doctors can be booked
            'dentist_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', User::ROLE_DOCTOR)->where('is_active', true),
            ],
            // only active services that haven't been deleted
            'service_id' => [
                'required',
                Rule::exists('services', 'id')->where('is_active', true)->whereNull('deleted_at'),
            ],
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'patient_id' => 'patient',
            'dentist_id' => 'doctor',
            'service_id' => 'service',
        ];
    }
}
