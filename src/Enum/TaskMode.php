<?php
// src/Enum/TaskMode.php
namespace App\Enum;

enum TaskMode: string
{
    case USER  = 'user';
    case TEAMS = 'teams';
}
