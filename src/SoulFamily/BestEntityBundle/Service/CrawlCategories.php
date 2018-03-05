<?php

namespace SoulFamily\BestEntityBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;


class CrawlCategories
{
    /**
     * @param string $url
     * @param string $htmlIdentifier
     * @return array
     */
    public function crawl($url, $htmlIdentifier)
    {
        $client = new Client();
        $client->setHeader('Accept-Language', 'en');
        $crawler = $client->request('GET', $url);
        $data = [];
        $crawler->filter($htmlIdentifier)->each(function ($node) use (&$data) {
            $data[] = ['name' => $node->text(), 'link' => $node->link()->getUri()];
        });

        return $data;
    }
}