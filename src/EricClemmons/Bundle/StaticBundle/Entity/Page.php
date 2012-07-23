<?php

namespace EricClemmons\Bundle\StaticBundle\Entity;

use Knp\Bundle\MarkdownBundle\Parser\MarkdownParser;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class Page extends File
{
    public function getSlug()
    {
        return basename($this->getBasename(), '.md');
    }

    public function getUrl($absolute = false)
    {
        $url = $this->router->generate('ericclemmons_site_page_view', array(
            'slug'  => $this->getSlug(),
        ), $absolute);

        return $url;
    }
}

