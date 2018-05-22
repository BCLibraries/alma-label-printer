<?php

use BCLib\AlmaPrinter\OutputTemplate;
use Slim\Http\Request;
use Slim\Http\Response;

// Get upload form
$app->get(
    '/',
    function (Request $request, Response $response, array $args) {
        $this->logger->info("Slim-Skeleton '/' route");
        return $this->view->render($response, 'index.twig', $args);
    }
);

// Handle an upload
$app->post(
    '/upload',
    function (Request $request, Response $response) {
        $uploaded_file = $request->getUploadedFiles()['csv-file'];
        $upload_dir = $this->get('settings')['uploads_dir'];

        $barcodes = \BCLib\AlmaPrinter\CSVReader::read($uploaded_file, $upload_dir);

        foreach ($barcodes as $barcode) {
            $this->alma_client->add($barcode);
        }
        $result_set = $this->alma_client->fetch();

        $template = getTemplate($request->getParam('output-type'));
        $view_options = ['pages' => $result_set->getPages($template->items_per_page)];

        return $this->view->render($response, $template->name, $view_options);
    }
);

$app->get(
    '/barcode/{value}',
    function (Request $request, Response $response, $args) {
        $generator = new \Picqer\Barcode\BarcodeGeneratorSVG();
        echo $generator->getBarcode($args['value'], $generator::TYPE_CODE_39E_CHECKSUM);
    }
);

function getTemplate(string $template_input): OutputTemplate
{
    $spine_label = new OutputTemplate('spine-label.twig', 10);
    $slip_1 = new OutputTemplate('slip-1.twig', 4);
    $slip_2 = new OutputTemplate('slip-2.twig', 4);
    $map = [
        'spine-label' => $spine_label,
        'slip-1'      => $slip_1,
        'slip-2'      => $slip_2
    ];
    return $map[$template_input];
}