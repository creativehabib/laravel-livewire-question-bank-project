<?php

namespace App\Support;

class Fonts
{
    /**
     * Get the list of available font families mapped to their human readable labels.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            'Bangla' => 'বাংলা (ডিফল্ট)',
            'HindSiliguri' => 'হিন্দ শিলিগুড়ি',
            'SolaimanLipi' => 'সোলাইমান লিপি',
            'Kalpurush' => 'কালপুরুষ',
            'Shurjo' => 'শূর্য (Shurjo)',
            'roman' => 'Times New Roman',
        ];
    }

    /**
     * Get the list of keys for the supported font families.
     *
     * @return array<int, string>
     */
    public static function keys(): array
    {
        return array_keys(self::options());
    }
}
