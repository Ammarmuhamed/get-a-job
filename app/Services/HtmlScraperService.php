<?php

namespace App\Services;

use GuzzleHttp\Client;
// use Symfony\Component\DomCrawler\Crawler;
use DiDom\Document;

class HtmlScraperService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetchHtml($url)
    {
        $response = $this->client->request('GET', $url);

        if ($response->getStatusCode() == 200) {
            return $response->getBody()->getContents();
        }

        return null;
    }

    public function parseHtml($html)
    {
        // $crawler = new Crawler($html);
        // $jobs = $crawler->filter("a[href^='https://wuzzuf.net/jobs/p/']");
        // $data = [];

        // foreach ($jobs as $jobElement) {
        //     $jobCrawler = new Crawler($jobElement);

        //     $job_data = [
        //         'name' => $jobCrawler->text(),
        //         'url' => $jobCrawler->attr('href')
        //     ];

        //     // Get parent and then the company element
        //     $parent = $jobCrawler->filterXPath("parent");
        //     if ($parent) {
        //         $company_element = $parent->nextAll()->filter('div')->first(); // Adjust selector as needed

        //         if ($company_element->count()) {
        //             $company_name = $company_element->filter("a")->first()->text();
        //             $company_location = $company_element->filter("span")->first()->text();
        //             $job_data['company'] = [
        //                 'name' => $company_name,
        //                 'location' => $company_location
        //             ];
        //         }
        //     }

        //     $data[] = $job_data;
        // }

        // return $data;

        $document = new Document($html);
        $jobs = $document->find('a[href^="https://wuzzuf.net/jobs/p/"]');
        $data = [];
        foreach ($jobs as $job) {
            $job_data = [
                'name' => $job->text(),
                'url' => $job->href
            ];
            $job_parent = $job->parent()->parent();
            // var_dump($job_parent);   
            if ($job_parent) {
                    $company_name = $job_parent->first('div > a')->text();
                    $company_location = $job_parent->first('div > span')->text();
                    $job_data['company'] = [
                        'name' => $company_name,
                        'location' => $company_location
                    ];
                    $tags = [];
                    $tag_elements = $job_parent->parent()->find("div:nth-child(2)  a");
                    foreach ($tag_elements as $tag) {
                        if($tag->has('span')) {
                            $name = $tag->first('span')->text();
                        } else {

                            $name = $tag->text();
                        }
                        $link = $tag->href;
                        $tags[] = [
                            'name' => $name,
                            'link' => $link
                        ];
                    }
                    $job_data['tags'] = $tags;
            }
            $data[] = $job_data;
        }
        return $data;
    }
}
