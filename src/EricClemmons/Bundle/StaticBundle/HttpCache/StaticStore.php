<?php

namespace EricClemmons\Bundle\StaticBundle\HttpCache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpCache\StoreInterface;

class StaticStore implements StoreInterface
{
    private $root;

    /**
     * Constructor.
     *
     * @param string $root The path to the cache directory
     */
    public function __construct($root)
    {
        $this->root = $root;
        if (!is_dir($this->root)) {
            mkdir($this->root, 0777, true);
        }
    }

    public function cleanup()
    {
        // Nothing to cleanup
    }

    public function getPath($key)
    {
        return $this->getRoot().DIRECTORY_SEPARATOR.$key;
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function invalidate(Request $request)
    {
        // Nothing to invalidate
    }

    public function lock(Request $request)
    {
        // Nothing to lock
    }

    public function lookup(Request $request)
    {
        // Nothing to lookup
    }

    /**
     * Purges data for the given URL.
     *
     * @param string $url A URL
     *
     * @return Boolean true if the URL exists and has been purged, false otherwise
     */
    public function purge($url)
    {
        if (is_file($path = $this->getPath($this->getCacheKey(Request::create($url))))) {
            unlink($path);

            return true;
        }

        return false;
    }

    public function write(Request $request, Response $response)
    {
        $key = $this->getCacheKey($request);

        $this->save($key, $response->getContent());
    }

    public function unlock(Request $request)
    {
        // Nothing to unlock
    }


    /**
     * Returns a cache key for the given Request.
     *
     * @param Request $request A Request instance
     *
     * @return string A key for the given Request
     */
    private function getCacheKey(Request $request)
    {
        return substr($request->getPathInfo(), 1).'index.html';
    }

    /**
     * Save data for the given key.
     *
     * @param string $key  The store key
     * @param string $data The data to store
     */
    private function save($key, $data)
    {
        $path = $this->getPath($key);
        if (!is_dir(dirname($path)) && false === @mkdir(dirname($path), 0777, true)) {
            return false;
        }

        $tmpFile = tempnam(dirname($path), basename($path));
        if (false === $fp = @fopen($tmpFile, 'wb')) {
            return false;
        }
        @fwrite($fp, $data);
        @fclose($fp);

        if ($data != file_get_contents($tmpFile)) {
            return false;
        }

        if (false === @rename($tmpFile, $path)) {
            return false;
        }

        @chmod($path, 0666 & ~umask());
    }
}
