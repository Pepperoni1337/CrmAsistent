<?php

namespace App\Entity\Task;

enum TaskStatus: string
{
    case Backlog = 'backlog';
    case InProgress = 'in_progress';
    case Done = 'done';
    case Deleted = 'deleted';
}
