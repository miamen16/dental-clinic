<?php

namespace App\Http\Requests\Doctors;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreDoctorRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => ['required', 'string', 'max:30'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'specialty' => ['required', Rule::in(User::SPECIALIZATIONS)],
            'experience' => ['required', 'integer', 'min:0', 'max:70'],
            'is_active' => ['required', 'boolean'],
            'gender' => ['required', 'in:male,female,other'],
            'bio' => ['nullable', 'string', 'max:5000'],

            // address information
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        // Custom attribute names for better error messages
        return [
            'phone' => 'contact number',
            'date_of_birth' => 'date of birth',
            'specialty' => 'specialization',
            'experience' => 'experience (in years)',
            'is_active' => 'status',
            'bio' => 'description',
        ];
    }
}
