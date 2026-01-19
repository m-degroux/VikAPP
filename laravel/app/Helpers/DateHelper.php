<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function diffForHumansShort(Carbon $date): ?string
    {
        return Carbon::now()->diffForHumans(
            $date,
            [
                'parts' => 2,
                'short' => true,
                'syntax' => Carbon::DIFF_ABSOLUTE,
            ]
        );
    }
}
