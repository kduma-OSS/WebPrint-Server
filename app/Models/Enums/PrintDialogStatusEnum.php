<?php

namespace App\Models\Enums;

enum PrintDialogStatusEnum: string
{
    case New = 'new';
    case Cancelled = 'cancelled';
    case Sent = 'sent';
}
