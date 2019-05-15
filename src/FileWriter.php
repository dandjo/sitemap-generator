<?php

namespace Dandjo\SitemapGenerator;


use Dandjo\SitemapGenerator\Components\Index;
use Dandjo\SitemapGenerator\Components\Sitemap;
use Dandjo\SitemapGenerator\Components\Url;
use Dandjo\SitemapGenerator\Components\UrlSet;
use League\Flysystem\Filesystem;
use OverflowException;

class FileWriter implements WriterInterface
{
    /**
     * @var Filesystem
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
    public $filename = 'sitemap.xml';

    /**
     * @var string
     */
    public $filenameIndex = 'sitemap.index.xml';

    /**
     * @var int
     */
    public $indexSize = 50000;

    /**
     * @var int
     */
    public $urlSetSize = 5000;

    /**
     * @var bool
     * @see https://www.php.net/manual/en/function.gzencode.php
     */
    public $gzipLevel = 0;

    /**
     * FileWriter constructor.
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
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
        if ($this->urlIter >= $this->urlSetSize) {
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
        if ($this->fileIter > $this->indexSize) {
            throw new OverflowException('Maximum allowed urlsets exceeded');
        }
        $now = date_create();
        for ($i = 0; $i <= $this->fileIter; $i++) {
            $sitemap = new Sitemap($this->buildFilename($i));
            $sitemap->lastModified = $now;
            $index->addSitemap($sitemap);
        }
        $content = $index;
        if ($this->gzipLevel !== 0) {
            $content = gzencode($content, $this->gzipLevel);
        }
        $this->filesystem->put($this->filenameIndex, $content);
    }

    /**
     * Write current position.
     */
    protected function writeUrlSet()
    {
        $content = $this->urlSet;
        if ($this->gzipLevel !== 0) {
            $content = gzencode($content, $this->gzipLevel);
        }
        $this->filesystem->put($this->buildFilename($this->fileIter), $content);
    }

    /**
     * @param int $iter
     * @return string
     */
    protected function buildFilename(int $iter): string
    {
        $parts = pathinfo($this->filename);
        return implode('.', [
            $parts['filename'],
            $iter,
            $parts['extension'],
        ]);
    }
}
