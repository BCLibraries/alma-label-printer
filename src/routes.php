<?php

use BCLib\AlmaPrinter\AlmaClient;
use BCLib\AlmaPrinter\RedisCache;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;

// Routes

$app->get(
    '/[{name}]',
    function (Request $request, Response $response, array $args) {
        // Sample log message
        $this->logger->info("Slim-Skeleton '/' route");

        // Render index view
        return $this->renderer->render($response, 'index.phtml', $args);
    }
);

$app->post(
    '/upload',
    function (Request $request, Response $response) {
        $upload_dir = $this->get('settings')['uploads_dir'];

        $cache = $_ENV['CACHE_ENGINE'] === 'redis' ? new RedisCache($_ENV['REDIS_HOST']) : null;
        $alma_client = new AlmaClient($_ENV['ALMA_API_KEY'], $cache);

        print_r($cache);

        $uploaded_files = $request->getUploadedFiles();

        $uploaded_file = $uploaded_files['csv_file'];
        if ($uploaded_file->getError() === UPLOAD_ERR_OK) {
            $filename = moveUploadedFile($upload_dir, $uploaded_file);
        }

        $full_path = "$upload_dir/$filename";

        $csv = new SplFileObject($full_path);
        while (!$csv->eof()) {
            $row = $csv->fgetcsv();
            $alma_client->add($row[0]);
        }

        $results = $alma_client->fetch();
        foreach ($results as $result) {
            echo "{$result->getCallNumber()}<br>";
        }

        unlink($full_path);
    }
);

function moveUploadedFile(string $directory, UploadedFile $uploaded_file): string
{
    $extension = pathinfo($uploaded_file->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8));
    $filename = sprintf('%s.%0.8s', $basename, $extension);
    $uploaded_file->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
    return $filename;
}
