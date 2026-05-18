<?php

declare(strict_types=1);

namespace App\Enums;

enum EmployeeEmploymentType: string
{
    case FULL_TIME = 'full_time';
    case PART_TIME = 'part_time';
    case CONTRACT = 'contract';
    case INTERN = 'intern';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
