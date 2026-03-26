<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamCategory;
use Illuminate\Support\Str;

class ExamCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // বাংলাদেশের প্রেক্ষাপটে কিছু কমন ক্যাটাগরি
        $categories = [
            'BCS Preliminary',
            'Primary Teacher Exam',
            'Bank Job',
            'University Admission',
            'Medical Admission',
            'HSC (Science)',
            'HSC (Arts & Commerce)',
            'SSC',
            'Class 10',
            'Class 9',
            'Class 8'
        ];

        foreach ($categories as $category) {
            // firstOrCreate ব্যবহার করা হলো যাতে একাধিকবার Seeder রান করলেও ডুপ্লিকেট ডাটা তৈরি না হয়
            ExamCategory::firstOrCreate(
                ['slug' => Str::slug($category)], // চেক করবে এই স্লাগটি আছে কি না
                ['name' => $category]             // না থাকলে এই ডাটা সেভ করবে
            );
        }

        $this->command->info('Exam Categories seeded successfully!');
    }
}
