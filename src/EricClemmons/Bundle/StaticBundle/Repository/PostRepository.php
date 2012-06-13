<?php

namespace EricClemmons\Bundle\StaticBundle\Repository;

use Symfony\Component\Finder\SplFileInfo;

class PostRepository extends Repository
{
    public function find($slug)
    {
        return parent::find('*'.$slug);
    }

    public function getEntityClass()
    {
        return 'EricClemmons\\Bundle\\StaticBundle\\Entity\\Post';
    }

    protected function sort(SplFileInfo $a, SplFileInfo $b)
    {
        return $a->getDateCreated() < $b->getDateCreated();
    }
}
