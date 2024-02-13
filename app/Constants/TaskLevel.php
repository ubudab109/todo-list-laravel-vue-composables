<?php

namespace App\Constants;

/**
 * @method static TaskLevel LOW()
 * @method static TaskLevel NORMAL()
 * @method static TaskLevel HIGH()
 * @method static TaskLevel URGENT()
 */
class TaskLevel extends Enum
{
    private const LOW = 0;
    private const NORMAL = 1;
    private const HIGH = 2;
    private const URGENT = 3;
}