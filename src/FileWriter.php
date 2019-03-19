<?php

namespace Dandjo\SitemapGenerator;


use Dandjo\SitemapGenerator\Components\Index;
use Dandjo\SitemapGenerator\Components\Sitemap;
use Dandjo\SitemapGenerator\Components\Url;
use Dandjo\SitemapGenerator\Components\UrlSet;

class FileWriter implements WriterInterface
{
    /**
     * @var \League\Flysystem\Filesystem
     */
    protected $filesystem;

    /**
     * @var UrlSet
     */
    protected $urlSet;

    /**
     * @var int
     */
    protected $fileIter = 0;

    /**
     * @var int
     */
    protected $urlIter = 0;

    /**
     * @var string
     */
    public $filename = 'sitemap';

    /**
     * @var string
     */
    public $filenameIndex = 'sitemap-index';

    /**
     * FileWriter constructor.
     * @param \League\Flysystem\Filesystem $filesystem
     */
    public function __construct(\League\Flysystem\Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * FileWriter destructor.
     */
    public function __destruct()
    {
        $this->writeUrlSet();
    }

    /**
     * @param Url $url
     */
    public function write(Url $url)
    {
        if ($this->urlIter === 0) {
            $this->urlSet = new UrlSet();
        }
        $this->urlSet->addUrl($url);
        $this->urlIter++;
        if ($this->urlIter >= 5000) {
            $this->writeUrlSet();
            $this->urlIter = 0;
            $this->fileIter++;
        }
    }

    /**
     * Write the index.
     * @param Index $index
     */
    public function writeIndex(Index $index)
    {
        if ($this->fileIter > 50000) {
            throw new \OverflowException('Maximum allowed urlsets exceeded');
        }
        $now = date_create();
        for ($i = 0; $i <= $this->fileIter; $i++) {
            $sitemap = new Sitemap($this->filename . '-' . $i . '.xml');
            $sitemap->lastModified = $now;
            $index->addSitemap($sitemap);
        }
        $this->filesystem->put($this->filenameIndex . '.xml', $index);
    }

    /**
     * Write current position.
     */
    protected function writeUrlSet()
    {
        $filename = $this->filename . '-' . $this->fileIter . '.xml';
        $this->filesystem->put($filename, $this->urlSet);
    }
}