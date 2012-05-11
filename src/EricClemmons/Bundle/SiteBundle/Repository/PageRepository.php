<?php

namespace EricClemmons\Bundle\SiteBundle\Repository;

use EricClemmons\Bundle\SiteBundle\Entity\Page;
use Knp\Bundle\MarkdownBundle\Parser\MarkdownParser;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class PageRepository
{
    protected $path;
    protected $parser;

    public function __construct($path, MarkdownParser $parser)
    {
        if (! is_dir($path)) {
            throw new \InvalidArgumentException('Path does not exist: '.$path);
        }

        $this->path     = $path;
        $this->parser   = $parser;
    }

    public function find($slug)
    {
        $finder = new Finder;
        $files  = $finder->files()->name($slug.'*')->in($this->path);
        $file   = current(iterator_to_array($files));

        return $file ? $this->create($file) : null;
    }

    public function findAll()
    {
        $finder = new Finder;
        $files  = $finder->files()->in($this->path);
        $parser = $this->parser;
        $pages  = array();

        foreach ($files as $file) {
            $pages[] = $this->create($file);
        }

        return $pages;
    }

    protected function create(SplFileInfo $file)
    {
        return new Page($file, $this->parser);
    }
}
