<?php


namespace Core\Support;


class Meta
{
    private $title;
    private $description;
    private $keywords;

    public function getTitle()
    {
        return $this->title ?? '';
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description ?? '';
    }

    public function setKeywords(string $keywords)
    {
        $this->keywords = $keywords;
    }

    public function getKeywords()
    {
        return $this->keywords ?? '';
    }

    public function __construct(array $meta)
    {
        if (isset($meta['title'])) $this->setTitle($meta['title']);
        if (isset($meta['description'])) $this->setDescription($meta['description']);
        if (isset($meta['keywords'])) $this->setKeywords($meta['keywords']);
    }
}