<?php

namespace Daxdoxsi\Abcphp\Libs;



use Memcached;

class SessionHandler implements \SessionHandlerInterface
{
    private Memcached $mc;
    /**
     * @inheritDoc
     */
    #[\Override] public function close(): bool
    {
        $this->mc->quit();
    }

    /**
     * @inheritDoc
     */
    #[\Override] public function destroy(string $id): bool
    {
        $this->mc->touchByKey($id);
    }

    /**
     * @inheritDoc
     */
    #[\Override] public function gc(int $max_lifetime): int|false
    {
        $this->mc->deleteMulti($this->mc->getAllKeys());
    }

    /**
     * @inheritDoc
     */
    #[\Override] public function open(string $path, string $name): bool
    {
        try {
            $this->mc = new Memcached;
            $this->mc->addServer('127.0.0.1', '11211');
        }
        catch (\Exception $e) {
            die('ERROR: '.$e->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    #[\Override] public function read(string $id): string|false
    {
        return $this->mc->get($id);
    }

    /**
     * @inheritDoc
     */
    #[\Override] public function write(string $id, string $data): bool
    {

        return $this->mc->set($id,$data,0,MEMCACHE_COMPRESSED);

    }

}