<?php
// src/Enum/TaskMode.php
namespace App\Enum;

enum TaskInterval: string
{
    case NONE = '';
    case DAILY  = 'daily';
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';
}
