<?php

namespace Dandjo\SitemapGenerator;


use Dandjo\SitemapGenerator\Components\Url;

interface WriterInterface
{
    /**
     * @param Url $url
     * @return mixed
     */
    public function write(Url $url);
}