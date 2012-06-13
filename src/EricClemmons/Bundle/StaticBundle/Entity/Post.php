<?php

namespace EricClemmons\Bundle\StaticBundle\Entity;

use Knp\Bundle\MarkdownBundle\Parser\MarkdownParser;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class Post extends File
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
