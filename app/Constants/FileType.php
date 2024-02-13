<?php

namespace App\Constants;

/**
 * @method static FileType IMAGE()
 * @method static FileType VIDEO()
 * @method static FileType FILE()
 */
class FileType extends Enum
{
    private const IMAGE = 'image';
    private const VIDEO = 'video';
    private const FILE = 'file';
}