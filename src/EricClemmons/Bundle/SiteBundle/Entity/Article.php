<?php

namespace EricClemmons\Bundle\SiteBundle\Entity;

use Knp\Bundle\MarkdownBundle\Parser\MarkdownParser;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class Article extends File
{
    public function getDateCreated()
    {
        $date = substr($this->getBasename(), 0, 10);

        return new \DateTime($date);
    }

    public function getSlug()
    {
        return substr($this->getBasename(), 11, -3);
    }
}
