<?php

namespace App\Http\Requests\Taxes;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Tax;
class StoreTaxRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'rate' => ['required', 'numeric', 'between:0,100'],
            'type' => ['required', Rule::in(Tax::TYPES)],
            'is_active' => ['required', 'boolean'],
            'description' => ['nullable', 'string'],
            'service_id' => ['required', 'exists:services,id'],
            //
        ];
    }
}
