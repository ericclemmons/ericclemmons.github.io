<?php

namespace EricClemmons\Bundle\SiteBundle\Repository;

use Knp\Bundle\MarkdownBundle\Parser\MarkdownParser;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class PageRepository extends Repository
{
    public function getEntityClass()
    {
        return 'EricClemmons\\Bundle\\SiteBundle\\Entity\\Page';
    }
}
