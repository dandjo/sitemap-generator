<?php

namespace Dandjo\SitemapGenerator\Components;


class Index
{
    /**
     * @var string
     */
    public static $xmlHeader = '<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></sitemapindex>';

    /**
     * @var \SimpleXMLElement
     */
    protected $xmlElement;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * UrlSet constructor.
     * @param string $baseUrl
     */
    public function __construct(string $baseUrl)
    {
        $this->xmlElement = new \SimpleXMLElement(static::$xmlHeader);
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param Sitemap $sitemap
     * @return Index
     */
    public function addSitemap(Sitemap $sitemap)
    {
        $row = $this->xmlElement->addChild('sitemap');
        $row->addChild(
            'loc',
            htmlspecialchars(rtrim($this->baseUrl, '/') . '/' . $sitemap->loc, ENT_QUOTES, 'UTF-8')
        );
        if ($sitemap->lastModified) {
            $row->addChild('lastmod', $sitemap->lastModified->format('c'));
        }
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->xmlElement->asXML() ?: '';
    }
}