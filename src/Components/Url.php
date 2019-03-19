<?php

namespace Dandjo\SitemapGenerator\Components;


class Url
{
    /**
     * @var string
     */
    public $loc;

    /**
     * @var \DateTime
     */
    public $lastModified;

    /**
     * @var string
     */
    public $changeFrequency;

    /**
     * @var string
     */
    public $priority;

    /**
     * UrlItem constructor.
     * @param string $loc
     */
    public function __construct(string $loc)
    {
        $this->loc = $loc;
    }
}