<?php

namespace App\Enums;

enum MeterStatus: string
{
    case InStock = 'IN_STOCK';
    case Assigned = 'ASSIGNED';
    case Installed = 'INSTALLED';
    case Active = 'ACTIVE';
    case Retired = 'RETIRED';
}
