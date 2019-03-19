<?php

namespace Dandjo\SitemapGenerator\Components;


class UrlSet
{
    /**
     * @var string
     */
    public static $xmlHeader = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>';

    /**
     * @var \SimpleXMLElement
     */
    protected $xmlElement;

    /**
     * UrlSet constructor.
     */
    public function __construct()
    {
        $this->xmlElement = new \SimpleXMLElement(static::$xmlHeader);
    }

    /**
     * @param Url $url
     * @return UrlSet
     */
    public function addUrl(Url $url)
    {
        $row = $this->xmlElement->addChild('url');
        $row->addChild(
            'loc',
            htmlspecialchars($url->loc, ENT_QUOTES, 'UTF-8')
        );
        if ($url->lastModified) {
            $row->addChild('lastmod', $url->lastModified->format('c'));
        }
        if ($url->changeFrequency) {
            $row->addChild('changefreq', $url->changeFrequency);
        }
        if ($url->priority) {
            $row->addChild('priority', $url->priority);
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