<?php

namespace App\Models\Enums;

enum PrintJobPromiseStatusEnum: string
{
    case Draft = 'draft';
    case New = 'new';
    case Canceled = 'canceled';
    case Ready = 'ready';
    case Failed = 'failed';
    case SentToPrinter = 'sent_to_printer';
}
