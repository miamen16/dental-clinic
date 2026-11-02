<?php

namespace App\Http\Requests\Taxes;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Tax;
class UpdateTaxRequest extends FormRequest
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
            'service_id' => ['required', 'exists:services,id'],
            'name' => ['required', 'string', 'max:255'],
            'rate' => ['required', 'numeric', 'between:0,100'],
             'type' => ['required', Rule::in(Tax::TYPES)],         
            'is_active' => ['required', 'boolean'],
            'description' => ['nullable', 'string'],
            //
        ];

    }
}
