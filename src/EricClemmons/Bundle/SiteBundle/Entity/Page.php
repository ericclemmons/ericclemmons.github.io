<?php

namespace EricClemmons\Bundle\SiteBundle\Entity;

use Knp\Bundle\MarkdownBundle\Parser\MarkdownParser;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class Page
{
    protected $file;

    protected $parser;

    protected $content;

    protected $meta;

    protected $rawContent;

    protected $source;

    public function __construct(SplFileInfo $file, MarkdownParser $parser)
    {
        $this->file     = $file;
        $this->parser   = $parser;
        $this->source   = file_get_contents($this->file->getRealPath());

        preg_match('/^(?:---(.+?)---)?(.+)$/sum', $this->source, $matches);

        if (empty($matches)) {
            return false;
        }

        list($original, $this->meta, $this->rawContent) = $matches;

        if ($this->meta) {
            $this->meta = Yaml::parse($this->meta);
        }

        $this->content = $this->parser->transform($this->rawContent);
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getDateModified()
    {
        $date = new \DateTime();
        $date->setTimestamp($this->file->getCTime());

        return $date;
    }

    public function getMeta($attribute = null)
    {
        if ($attribute) {
            return isset($this->meta[$attribute]) ? $this->meta[$attribute] : null;
        } else {
            return $this->meta;
        }
    }

    public function getRawContent()
    {
        return $this->rawContent;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getTitle()
    {
        return $this->getMeta('title');
    }
}

