<?php

namespace BCLib\AlmaPrinter;

use Psr\Http\Message\UploadedFileInterface;

class CSVReader
{
    public static function read(UploadedFileInterface $uploaded_file, string $upload_dir): array
    {
        $upload_error = $uploaded_file->getError();
        if ($upload_error !== UPLOAD_ERR_OK) {
            throw new \Exception("Upload error: {$uploaded_file->getError()}");
        }

        $filename = self::moveUploadedFile($upload_dir, $uploaded_file);
        $full_path = "$upload_dir/$filename";

        $rows = file($full_path);
        unlink($full_path);

        $rows = array_filter($rows);
        return array_map([self::class, 'extractBarcode'], $rows);
    }

    private static function moveUploadedFile(
        string $directory,
        UploadedFileInterface $uploaded_file
    ): string {
        $extension = pathinfo($uploaded_file->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);
        $uploaded_file->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
        return $filename;
    }

    private static function extractBarcode(string $csv_row)
    {
        return str_getcsv($csv_row)[0];
    }
}