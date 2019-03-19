<?php

namespace Dandjo\SitemapGenerator;


use Dandjo\SitemapGenerator\Components\Index;
use Dandjo\SitemapGenerator\Components\Url;

interface WriterInterface
{
    /**
     * @param Url $url
     * @return mixed
     */
    public function write(Url $url);

    /**
     * @param Index $index
     * @return mixed
     */
    public function writeIndex(Index $index);
}