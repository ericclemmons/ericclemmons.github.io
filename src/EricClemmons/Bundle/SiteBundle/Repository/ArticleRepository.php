<?php

namespace EricClemmons\Bundle\SiteBundle\Repository;

use Symfony\Component\Finder\SplFileInfo;

class ArticleRepository extends Repository
{
    public function getEntityClass()
    {
        return 'EricClemmons\\Bundle\\SiteBundle\\Entity\\Article';
    }

    protected function sort(SplFileInfo $a, SplFileInfo $b)
    {
        return $a->getDateCreated() < $b->getDateCreated();
    }
}
