<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectChapterSeeder extends Seeder
{
    public function run(): void
    {
        $math = Subject::firstOrCreate(['name' => 'Mathematics']);
        $phy  = Subject::firstOrCreate(['name' => 'Physics']);

        foreach (['Algebra','Geometry','Calculus'] as $c) {
            Chapter::firstOrCreate(['subject_id' => $math->id, 'name' => $c]);
        }
        foreach (['Mechanics','Optics'] as $c) {
            Chapter::firstOrCreate(['subject_id' => $phy->id, 'name' => $c]);
        }
    }
}
