<?php

namespace App\Http\Requests\Doctors;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateDoctorRequest extends StoreDoctorRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $doctorId = $this->route('doctor');

        return array_merge(parent::rules(), [
            // Ensure the email is unique, but ignore the current doctor's email
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($doctorId)],
            // leave blank to keep the current password
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);
    }
}
