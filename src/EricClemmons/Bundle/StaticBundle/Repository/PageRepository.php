<?php

namespace EricClemmons\Bundle\StaticBundle\Repository;

use Knp\Bundle\MarkdownBundle\Parser\MarkdownParser;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class PageRepository extends Repository
{
    public function getEntityClass()
    {
        return 'EricClemmons\\Bundle\\StaticBundle\\Entity\\Page';
    }
}
