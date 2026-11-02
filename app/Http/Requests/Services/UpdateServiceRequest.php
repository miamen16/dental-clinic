<?php

namespace App\Http\Requests\Services;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends StoreServiceRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $serviceId = $this->route('service');

        return array_merge(parent::rules(), [
            // Ensure the code is unique, but ignore the current service's code
            'code' => ['nullable', 'string', 'max:50', Rule::unique('services', 'code')->ignore($serviceId)],
        ]);
    }
}
