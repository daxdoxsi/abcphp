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
        return $this->mc->quit();
    }

    /**
     * @inheritDoc
     */
    #[\Override] public function destroy(string $id): bool
    {
        return $this->mc->touch($id);
    }

    /**
     * @inheritDoc
     */
    #[\Override] public function gc(int $max_lifetime): int|false
    {
        $result = $this->mc->deleteMulti($this->mc->getAllKeys());
        return $this->mc->getResultCode() != Memcached::RES_NOTFOUND;
    }

    /**
     * @inheritDoc
     */
    #[\Override] public function open(string $path, string $name): bool
    {
        try {
            $this->mc = new Memcached;
            $result = $this->mc->addServer('127.0.0.1', '11211');
        }
        catch (\Exception $e) {
            die('ERROR: '.$e->getMessage());
        }
        return $result;
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