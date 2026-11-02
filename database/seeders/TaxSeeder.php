<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Tax;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    /**
     * Each tax belongs to a service (taxes.service_id), and the service points back at it (services.tax_id).
     * Runs after ServiceSeeder.
     */
    public function run(): void
    {
        $taxes = [
            // service code => [name, rate, type, description]
            'CROWN' => ['VAT 14%', 14, 'Percentage', 'Standard VAT on lab-made prosthetics.'],
            'WHITE' => ['VAT 14%', 14, 'Percentage', 'Standard VAT on cosmetic treatment.'],
            'XRAY' => ['Radiology Fee', 50, 'Fixed', 'Fixed radiology handling fee.'],
        ];

        foreach ($taxes as $code => [$name, $rate, $type, $description]) {
            $service = Service::where('code', $code)->firstOrFail();

            $tax = Tax::updateOrCreate(['service_id' => $service->id, 'name' => $name], [
                'rate' => $rate,
                'type' => $type,
                'is_active' => true,
                'description' => $description,
            ]);

            $service->update(['tax_id' => $tax->id]);
        }
    }
}
