<?php

namespace EricClemmons\Bundle\SiteBundle\Entity;

use Knp\Bundle\MarkdownBundle\Parser\MarkdownParser;
use Symfony\Component\Finder\SplFileInfo;

abstract class File extends SplFileInfo
{
    protected $content;

    protected $meta;

    protected $rawContent;

    protected $source;

    public function getContent()
    {
        return $this->content;
    }

    public function getDateModified()
    {
        $date = new \DateTime();
        $date->setTimestamp($this->getCTime());

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

    /**
     * Init hook for any post-processing needed by the entity when instantiated
     */
    public function init()
    {}

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function setMeta(array $meta = array())
    {
        $this->meta = $meta;

        return $this;
    }

    public function setRawContent($rawContent)
    {
        $this->rawContent = $rawContent;

        return $this;
    }

    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }
}

