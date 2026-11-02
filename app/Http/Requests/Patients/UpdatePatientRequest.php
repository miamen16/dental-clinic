<?php

namespace App\Http\Requests\Patients;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
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
        $patientId = $this->route('patient'); // Get the patient ID from the route parameter
        return [
            //
            'patient_number' => ['required', 'string', 'max:255', 'unique:patients,patient_number,' . $patientId],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],

            'date_of_birth' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],

            'gender' => [
                'nullable',
                'in:male,female,other',
            ],

            'phone_number' => ['nullable', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255', 'unique:patients,email,' . $patientId],
            'blood_type' => ['nullable', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'address' => ['nullable', 'string', 'max:5000'],

            'medical_history' => ['nullable', 'string', 'max:10000'],
            'allergies' => ['nullable', 'string', 'max:10000'],
            'current_medications' => ['nullable', 'string', 'max:10000'],
            'notes' => ['nullable', 'string', 'max:10000'],
        ];
    }
}
