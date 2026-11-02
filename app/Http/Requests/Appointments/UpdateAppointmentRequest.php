<?php

namespace App\Http\Requests\Appointments;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateAppointmentRequest extends StoreAppointmentRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // get the appointment being updated to allow keeping the current doctor/service even if deactivated since booking
        $appointment = Appointment::find($this->route('appointment'));

        return array_merge(parent::rules(), [
            // past appointments can still be edited (e.g. marked completed)
            'date' => 'required|date',
            // allow keeping the current status even if not in the list of valid statuses since booking
            'status' => ['required', Rule::in(array_keys(Appointment::STATUSES))],
            // allow keeping the current doctor/service even if deactivated since booking
            'dentist_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', User::ROLE_DOCTOR)
                    ->where(fn ($q) => $q->where('is_active', true)->orWhere('id', $appointment?->dentist_id)),
            ],
            'service_id' => [
                'required',
                Rule::exists('services', 'id')->whereNull('deleted_at')
                    ->where(fn ($q) => $q->where('is_active', true)->orWhere('id', $appointment?->service_id)),
            ],
        ]);
    }
}
