<?php

namespace App\Http\Requests\MedicalRecords;

// Same rules as creating; the appointment unique check already ignores the record being edited
class UpdateMedicalRecordRequest extends StoreMedicalRecordRequest
{
}
