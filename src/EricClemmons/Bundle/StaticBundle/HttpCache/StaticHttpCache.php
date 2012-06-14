<?php

namespace EricClemmons\Bundle\StaticBundle\HttpCache;

use EricClemmons\Bundle\StaticBundle\HttpCache\StaticStore;

use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class StaticHttpCache extends HttpCache
{
    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        $response = $this->invalidate($request, $catch);

        $this->getStore()->write($request, $response);

        $response->prepare($request);

        return $response;
    }

    protected function createStore()
    {
        return new StaticStore($this->cacheDir ?: $this->kernel->getCacheDir().'/static_http_cache');
    }
}
