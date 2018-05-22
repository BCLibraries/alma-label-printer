<?php

namespace BCLib\AlmaPrinter;

use GuzzleHttp\Client;
use function GuzzleHttp\Promise\unwrap;

/**
 * Fetches Item information from Alma
 **
 * @package BCLib\AlmaPrinter
 */
class AlmaClient
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $key;

    /**
     * @var array
     */
    private $barcodes = [];

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @var ItemResponseParser
     */
    private $parser;


    private const UPLOAD_BATCH_SIZE = 8;

    private const MAX_CALLS_PER_SECOND = 10;

    public function __construct(string $key, Cache $cache = null, ItemResponseParser $parser)
    {
        $this->parser = $parser;
        $this->cache = $cache ?? new NullCache();
        $this->key = $key;
        $this->client = new Client();
    }

    public function add(string $barcode): void
    {
        $this->barcodes[] = $barcode;
    }

    /**
     * @return ResultSet
     * @throws \Throwable
     */
    public function fetch(): ResultSet
    {
        $results = [[]];
        $batches = array_chunk($this->barcodes, $this::UPLOAD_BATCH_SIZE);
        $wait_time = self::UPLOAD_BATCH_SIZE / self::MAX_CALLS_PER_SECOND;
        $throttle = new Throttle($wait_time);
        foreach ($batches as $batch) {
            $results[] = $this->sendBatch($batch);
            $throttle->throttle();
        }
        return new ResultSet(array_merge(...$results));
    }

    private function getURL(string $barcode): string
    {
        $url_base = 'https://api-na.hosted.exlibrisgroup.com/almaws/v1/items';
        $query_string = http_build_query(
            [
                'apikey'       => $this->key,
                'item_barcode' => $barcode,
                'format'       => 'json'
            ]
        );
        return "$url_base?$query_string";
    }

    /**
     * @param array $barcode_batch
     * @return Item[]
     * @throws \Throwable
     */
    private function sendBatch(array $barcode_batch): array
    {
        $responses = [];
        $promises = [];
        foreach ($barcode_batch as $barcode) {
            $url = $this->getURL($barcode);
            if ($this->cache->contains($barcode)) {
                $responses[] = $this->cache->get($barcode);
            } else {
                $promises[] = $this->client->getAsync($url);
            }
        }
        $results = unwrap($promises);

        foreach ($results as $result) {
            $json = $result->getBody();
            $item = $this->parser->parse($json);
            $responses[] = $item;
            $this->cache->add($item);
        }

        return $responses;
    }
}