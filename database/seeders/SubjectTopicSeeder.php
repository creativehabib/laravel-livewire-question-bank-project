<?php

namespace Database\Seeders;

use App\Models\Topic;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectTopicSeeder extends Seeder
{
    public function run(): void
    {
        $math = Subject::firstOrCreate(['name' => 'Mathematics']);
        $phy  = Subject::firstOrCreate(['name' => 'Physics']);

        foreach (['Algebra','Geometry','Calculus'] as $c) {
            Topic::firstOrCreate(['subject_id' => $math->id, 'name' => $c]);
        }
        foreach (['Mechanics','Optics'] as $c) {
            Topic::firstOrCreate(['subject_id' => $phy->id, 'name' => $c]);
        }
    }
}
