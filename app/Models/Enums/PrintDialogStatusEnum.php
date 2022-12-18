<?php

namespace App\Models\Enums;

enum PrintDialogStatusEnum: string
{
    case New = 'new';
    case Canceled = 'canceled';
    case Sent = 'sent';
}
