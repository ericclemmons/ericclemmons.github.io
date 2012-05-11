<?php

namespace EricClemmons\Bundle\SiteBundle\Entity;

use Knp\Bundle\MarkdownBundle\Parser\MarkdownParser;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class Article extends Page
{
    public function getDateCreated()
    {
        $date = substr($this->file->getBasename(), 0, 10);

        return new \DateTime($date);
    }

    public function getSlug()
    {
        return substr($this->file->getBasename(), 11, -3);
    }
}
