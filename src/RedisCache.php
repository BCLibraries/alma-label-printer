<?php

namespace BCLib\AlmaPrinter;

class RedisCache implements Cache
{
    private $redis;

    public function __construct($host = '127.0.0.1')
    {
        $this->redis = new \Redis();
        $this->redis->connect($host);
    }

    public function __destruct()
    {
        $this->redis->close();
    }

    public function add(Item $item): void
    {
        $key = $item->getBarcodeText();
        $expire_at = time() + 60 * 60;
        $serialized = serialize($item);
        $this->redis->set($key, $serialized);
        $this->redis->expireAt($key, $expire_at);
    }

    public function contains(string $key): bool
    {
        return $this->redis->exists($key);
    }

    public function get(string $key): ?Item
    {
        $serialized = $this->redis->get($key);
        $whitelist = [Item::class];
        return unserialize($serialized, ['allowed_classes' => $whitelist]);
    }
}