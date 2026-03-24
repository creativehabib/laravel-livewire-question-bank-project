<?php

namespace App\Livewire\Traits;

use Illuminate\Support\Facades\DB;

trait SlugValidationTrait
{
    // গ্লোবাল লাইভ স্লাগ চেকার মেথড
    public function validateSlug($slugToCheck, $table, $ignoreId = null)
    {
        if (empty(trim($slugToCheck))) return null;

        $query = DB::table($table)->where('slug', $slugToCheck);

        // যদি Edit মোডে থাকে, তবে নিজের আইডি ইগনোর করবে
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        if ($query->exists()) {
            return 'এই স্লাগটি আগে থেকেই ব্যবহৃত হচ্ছে। দয়া করে একটু পরিবর্তন করুন।';
        }

        return true; // Available
    }
}
