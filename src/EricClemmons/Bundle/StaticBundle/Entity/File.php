<?php

namespace EricClemmons\Bundle\StaticBundle\Entity;

use Knp\Bundle\MarkdownBundle\Parser\MarkdownParser;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Finder\SplFileInfo;

abstract class File extends SplFileInfo
{
    protected $content;

    protected $meta;

    protected $rawContent;

    protected $router;

    protected $source;

    public function getContent()
    {
        return $this->content;
    }

    public function getDateCreated()
    {
        $date = new \DateTime();
        $date->setTimestamp($this->getCTime());

        return $date;
    }

    public function getDateModified()
    {
        $date = new \DateTime();
        $date->setTimestamp($this->getMTime());

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

    public function getRouter()
    {
        return $this->router;
    }

    abstract function getSlug();

    public function getSource()
    {
        return $this->source;
    }

    public function getTitle()
    {
        return $this->getMeta('title') ?: $this->parseTitle() ?: ucwords(str_replace('-', ' ', $this->getSlug()));
    }

    abstract public function getUrl($absolute = false);

    /**
     * Init hook for any post-processing needed by the entity when instantiated
     */
    public function init()
    {}

    public function parseTitle()
    {
        if (preg_match('/^#\s(.*)$/m', $this->getRawContent(), $titles)) {
            return end($titles);
        }
    }

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

    public function setRouter(Router $router)
    {
        $this->router = $router;
    }

    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }
}

