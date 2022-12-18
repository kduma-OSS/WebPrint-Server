<?php

namespace App\Models\Enums;

enum PrintJobStatusEnum: string
{
    case New = 'new';
    case Printing = 'printing';
    case Finished = 'finished';
    case Failed = 'failed';
}
