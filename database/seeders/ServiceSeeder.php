<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['CONS', 'Consultation', 'Initial examination and treatment planning.', 300],
            ['CLEAN', 'Scaling & Polishing', 'Professional cleaning and plaque removal.', 600],
            ['XRAY', 'Panoramic X-Ray', 'Full-mouth panoramic radiograph.', 400],
            ['FILL', 'Composite Filling', 'Tooth-coloured filling, per tooth.', 800],
            ['RCT', 'Root Canal Treatment', 'Endodontic treatment, per canal.', 2500],
            ['EXT', 'Simple Extraction', 'Non-surgical tooth extraction.', 700],
            ['CROWN', 'Porcelain Crown', 'Porcelain-fused-to-metal crown.', 5000],
            ['WHITE', 'Teeth Whitening', 'In-office whitening session.', 3500],
        ];

        foreach ($services as [$code, $name, $description, $price]) {
            Service::withTrashed()->updateOrCreate(['code' => $code], [
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'is_active' => true,
            ]);
        }
    }
}
