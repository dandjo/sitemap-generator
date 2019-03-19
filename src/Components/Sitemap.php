<?php

namespace Dandjo\SitemapGenerator\Components;


class Sitemap
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
     * UrlItem constructor.
     * @param string $loc
     */
    public function __construct(string $loc)
    {
        $this->loc = $loc;
    }
}