# Sitemap Generator

Memory friendly sitemap generator.

## Installation

    composer require dandjo/sitemap-generator

## Usage

```php
use Dandjo\SitemapGenerator\Components\Index;
use Dandjo\SitemapGenerator\Components\Url;
use Dandjo\SitemapGenerator\FileWriter;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;


$writer = new FileWriter(new Filesystem(new Local('/tmp/')));
$writer->filename = 'sitemap.xml';  // optional
$writer->filenameIndex = 'sitemap.index.xml';  // optional
$writer->indexSize = 50000;  // optional
$writer->urlSetSize = 5000;  // optional
$writer->gzipLevel = 0;  // optional
for ($i = 0; $i < 6000000; $i++) {
    $url = new Url('http://localhost/foo/bar/' . $i);
    $url->changeFrequency = 'always';  // optional
    $url->lastModified = date_create();  // optional
    $url->priority = '0.5';  // optional
    $writer->write($url);
}
$writer->writeIndex(new Index('http://localhost/sitemap/'));
```

If you're using gzip compression, consider using the appropriate extension for
the filenames, although it is not mandatory.