<?php

namespace Fleetbase\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class OrderDocumentPolicy
{
    public const MAX_BYTES = 10 * 1024 * 1024;

    public const TYPES = [
        'pdf' => ['application/pdf', 'application/x-pdf'],
        'jpg' => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png' => ['image/png'],
        'gif' => ['image/gif'],
        'webp' => ['image/webp'],
        'doc' => ['application/msword', 'application/x-ole-storage', 'application/CDFV2'],
        'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip'],
        'xls' => ['application/vnd.ms-excel', 'application/x-ole-storage', 'application/CDFV2'],
        'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip'],
        'ppt' => ['application/vnd.ms-powerpoint', 'application/x-ole-storage', 'application/CDFV2'],
        'pptx' => ['application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/zip'],
        'zip' => ['application/zip', 'application/x-zip-compressed'],
        'tar' => ['application/x-tar'],
    ];

    public static function assertMetadata(string $name, ?string $mime, $size): void
    {
        $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (!isset(self::TYPES[$extension]) || !in_array($mime, self::TYPES[$extension], true)
            || !is_numeric($size) || $size <= 0 || $size > self::MAX_BYTES) {
            throw ValidationException::withMessages(['file' => 'Use a PDF, JPEG/PNG/GIF/WebP image, Word, Excel, PowerPoint, ZIP or TAR file, no larger than 10 MiB.']);
        }
    }

    public static function assertUpload(UploadedFile $file): void
    {
        self::assertMetadata($file->getClientOriginalName(), $file->getMimeType(), $file->getSize());
    }
}
