<?php

namespace EricClemmons\Bundle\SiteBundle\Repository;

use EricClemmons\Bundle\SiteBundle\Entity\Article;
use Symfony\Component\Finder\SplFileInfo;

class ArticleRepository extends PageRepository
{
    protected function create(SplFileInfo $file)
    {
        return new Article($file, $this->parser);
    }
}
