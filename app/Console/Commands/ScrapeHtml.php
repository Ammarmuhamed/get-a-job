<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\HtmlScraperService;

class ScrapeHtml extends Command
{
    protected $signature = 'scrape:html';
    protected $description = 'Scrape HTML from a specified URL daily';
    protected $scraper;

    public function __construct(HtmlScraperService $scraper)
    {
        parent::__construct();
        $this->scraper = $scraper;
    }

    public function handle()
    {
        $url = 'https://wuzzuf.net/search/jobs/?q=back%20end%20laravel%20&a=hpb'; // Default URL
        $html = $this->scraper->fetchHtml($url);

        if ($html) {
            $jobs = $this->scraper->parseHtml($html);
            // Process the jobs, e.g., save to database or log
            $this->info('Scraping completed successfully.');
        } else {
            $this->error('Failed to fetch HTML content.');
        }

        return 0;
    }
}
