<?php
    

    namespace App\Http\Controllers;

    use App\Services\HtmlScraperService;
    use Illuminate\Http\Request;
    
    class ScraperController extends Controller
    {
        protected $htmlScraperService;
    
        public function __construct(HtmlScraperService $htmlScraperService)
        {
            $this->htmlScraperService = $htmlScraperService;
        }
    
        public function scrape(Request $request)
        {
        
            $url = $request->input('url','https://wuzzuf.net/search/jobs/?q=back%20end%20laravel%20&a=hpb');
            $html = $this->htmlScraperService->fetchHtml($url);
    
            if ($html) {
                $parsedData = $this->htmlScraperService->parseHtml($html);
                return response()->json($parsedData);
            }
    
            return response()->json(['error' => 'Unable to fetch the URL.'], 500);
        }
    }
