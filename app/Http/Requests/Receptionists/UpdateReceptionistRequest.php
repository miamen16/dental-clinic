<?php

namespace App\Http\Requests\Receptionists;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateReceptionistRequest extends StoreReceptionistRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $receptionistId = $this->route('receptionist');

        return array_merge(parent::rules(), [
            // Ensure the email is unique, but ignore the current receptionist's email
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($receptionistId)],
            // leave blank to keep the current password
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);
    }
}
