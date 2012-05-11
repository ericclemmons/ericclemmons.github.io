<?php

namespace EricClemmons\Bundle\StaticBundle\Repository;

use Knp\Bundle\MarkdownBundle\Parser\MarkdownParser;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

abstract class Repository
{
    /**
     * @var Path to repository of files
     */
    protected $path;

    /**
     * @var MarkDown parser to transform file contents
     */
    protected $parser;

    abstract protected function getEntityClass();

    public function __construct($path, MarkdownParser $parser)
    {
        if (! is_dir($path)) {
            throw new \InvalidArgumentException('Path does not exist: '.$path);
        }

        $this->path     = $path;
        $this->parser   = $parser;
    }

    /**
     * Find file by {slug}.md
     *
     * @var slug URL-friendly file name
     *
     * @return EricClemmons\Bundle\SiteBundle\Entity\File instance
     */
    public function find($slug)
    {
        $finder = new Finder;
        $files  = $finder->files()->name($slug.'.md')->in($this->path);
        $file   = current(iterator_to_array($files));

        return $file ? $this->create($file) : null;
    }

    /**
     * Find all files in repository path, sorted & turned into entities
     *
     * @see sort
     * @see create
     * @see createEntity
     *
     * @return array
     */
    public function findAll()
    {
        $finder     = new Finder;
        $files      = $finder->files()->in($this->path)->sortByName();
        $parser     = $this->parser;
        $entities   = array();

        foreach ($files as $file) {
            $entities[] = $this->create($file);
        }

        usort($entities, array($this, 'sort'));

        return $entities;
    }

    /**
     * Sort array of files
     *
     * @var SplFileInfo first file
     * @var SPlFileInfo second file
     */
    protected function sort(SplFileInfo $a, SplFileInfo $b)
    {}

    /**
     * Create populated entity from a given file
     * @see createEntity
     *
     * @var SplFileInfo file
     * @return EricClemmons\Bundle\SiteBundle\Entity\File instance
     */
    private function create(SplFileInfo $file)
    {
        $class      = $this->getEntityClass();
        $entity     = new $class($file, $file->getRelativePath(), $file->getRelativePathName());
        $source     = file_get_contents($file->getRealPath());

        preg_match('/^(?:---(.+?)---)?(.+)$/sum', $source, $matches);

        if ($matches) {
            list($source, $meta, $rawContent) = $matches;

            $meta       = $meta ? Yaml::parse($meta) : null;
            $content    = $this->parser->transform($rawContent);

            $entity->setSource($source);
            $entity->setMeta($meta);
            $entity->setContent($content);
            $entity->setRawContent($content);
        }

        $entity->init();

        return $entity;
    }
}
