<?php

use App\Constants\FileType;
use Carbon\Carbon;

/**
 * Generate file name
 * @param mixed $extension
 * @return string random name
 */
function generateFileName(mixed $extension): string
{
    return preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', microtime()) . '.' . $extension;
}

/**
 * Storing file to strage
 * @param string $path - path to store
 * @param File $file
 * @return array file name
 */
function storeFile(string $path, $file): array
{
    $extension = $file->getClientOriginalExtension();
    $imageName = generateFileName($extension);
    $file->storeAs(
        $path,
        $imageName
    );
    return [
        'name' => $imageName,
        'extension' => $extension,
    ];
}

/**
 * The getFileType function returns the file type based on the given file extension.
 * 
 * @param string $extension The parameter `` is a string that represents the file extension. It is
 * optional and can be set to `null` if not provided.
 * 
 * @return mixed The function getFileType() returns the file type value associated with the given file
 * extension. If no extension is provided, it returns an array of all file extensions and their
 * corresponding file types.
 */
function getFileType(string $extension = null): mixed
{
    $extensionArr = [
        'svg' => FileType::IMAGE()->getValue(),
        'png' => FileType::IMAGE()->getValue(),
        'jpg' => FileType::IMAGE()->getValue(),
        'mp4' => FileType::VIDEO()->getValue(),
        'csv' => FileType::FILE()->getValue(),
        'txt' => FileType::FILE()->getValue(),
        'doc' => FileType::FILE()->getValue(),
        'docx' => FileType::FILE()->getValue(),
    ];

    if (empty($extension)) {
        return $extensionArr;
    }

    return $extensionArr[$extension];
}

/**
 * The function removes a file if it exists.
 * 
 * @param string $path The path parameter is the file path of the file that you want to remove.
 */
function removeFile(string $path): void
{
    if (file_exists($path)) {
        unlink($path);
    }
}

/**
 * The function carbonTimestampFormatter takes a string date and an optional format parameter, and
 * returns the formatted date using the Carbon library.
 * 
 * @param string $date The  parameter is a string representing a date or datetime value that you
 * want to format.
 * @param string $format The  parameter is a string that specifies the desired format of the
 * outputted timestamp. It uses the same format as the PHP date() function. Some common format options
 * include:
 * 
 * @return string formatted timestamp string based on the given date and format.
 */
function carbonTimestampFormatter(string $date, string $format = 'Y-m-d'): string
{
    return Carbon::parse(strtotime($date))->format($format);
}