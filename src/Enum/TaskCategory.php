<?php
namespace App\Enum;

enum TaskCategory: string
{
    case DEFAULT  = 'default';
    case TIMED = 'timed';
    case RECURRING = 'recurring';
}
