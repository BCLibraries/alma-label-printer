<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;

// Get upload form
$app->get(
    '/[{name}]',
    function (Request $request, Response $response, array $args) {
        $this->logger->info("Slim-Skeleton '/' route");
        return $this->view->render($response, 'index.twig', $args);
    }
);

// Handle an upload
$app->post(
    '/upload',
    function (Request $request, Response $response) {
        $upload_dir = $this->get('settings')['uploads_dir'];
        $uploaded_files = $request->getUploadedFiles();

        $uploaded_file = $uploaded_files['csv_file'];
        if ($uploaded_file->getError() === UPLOAD_ERR_OK) {
            $filename = moveUploadedFile($upload_dir, $uploaded_file);
        }

        $full_path = "$upload_dir/$filename";

        $csv = new SplFileObject($full_path);
        while (!$csv->eof()) {
            $row = $csv->fgetcsv();
            $this->alma_client->add($row[0]);
        }
        unlink($full_path);

        $result_set = $this->alma_client->fetch();

        return $this->view->render($response, 'result.twig', ['pages' => $result_set->getPages(30)]);
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
