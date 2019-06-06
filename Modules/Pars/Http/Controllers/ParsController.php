<?php

namespace Modules\Pars\Http\Controllers;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp;
use GuzzleHttp\Client;
use Clue\React\Buzz\Browser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Psr\Http\Message\ResponseInterface as RespInterface;
use React\EventLoop\Factory as LoopFactory;
use Symfony\Component\DomCrawler;

class ScraperBuzz
{
    /**
     * @var Browser
     */
    private $client;

    /**
     * @var array
     */
    private $scraped = [];

    public function __construct(Browser $client)
    {
        $this->client = $client;
    }

    public function scrape(array $urls = [])
    {
//        $categoryId=24505;
//        $currentPage=1;
//        $itemsPerPage=150;
//        $postData= array('categoryId' => (int)$categoryId,
//            'currentPage' => (int)$currentPage,
//            'itemsPerPage' => (int)$itemsPerPage,
//            'viewType' => 1,
//            'sortName' => 'popular',
//            'sortDest' => 'desc',
//            'filterInStock' => 1,
//            'filterInStore' => 0);

//        $pData1 = array('categoryId' => 24505,
//            'currentPage' => 1,
//            'itemsPerPage' => 10,
//            'viewType' => 1,
//            'sortName' => 'popular',
//            'sortDest' => 'desc',
//            'fastFilterId' => 559,
//            'smartFilter[688023][1614371234]' => 'Ультрабук',
//            'filterInStock' => 1,
//            'filterInStore' => 0,
//            'updateUrl' => true);
//

        $pData = array('categoryId' => 24505,
            'currentPage' => 1,
            'itemsPerPage' => 150,
            'viewType' => 1,
            'sortName' => 'popular',
            'sortDest' => 'desc',
            'filterInStock' => 1,
            'filterInStore' => 0);

        $headers = array(
            'Content-Encoding' => 'gzip',
            'Vary' => 'Accept-Encoding,User-Agent',
            'content-type' => 'application/json; charset=utf-8',
            'x-content-type-options' => 'nosniff',
            'access-control-allow-origin' => '*'
           );

        $this->scraped = [];
        foreach ($urls as $url) {
            $this->client->post($url, $headers, json_encode($pData))->then(
                function (RespInterface $response) {
                 $this->scraped[] = $this->extractFromHtml((string)$response->getBody());
                });
        }
    }

    public function extractFromHtml($html)
    {
        $crawler = new DomCrawler\Crawler($html);

        $id = trim($crawler->filter('.product-link')->eq(2)->attr('data-id'));
        $brand = trim($crawler->filter('.product-link')->eq(2)->attr('data-brand'));
        $category = trim($crawler->filter('.product-link')->eq(2)->attr('data-category'));
        $name = trim($crawler->filter('.product-link')->eq(2)->attr('data-name'));
        $price = trim($crawler->filter('.product-link')->eq(2)->attr('data-price'));

        return [
            'brand' => $brand,
            'category' => $category,
            'id' => $id,
            'name' => $name,
            'price' => $price
        ];
    }

    public function getMovieData()
    {
        return $this->scraped;
    }
}


class ParsController extends Controller
{

    public function scrapCurl(){
        $categoryId=24505;
        $currentPage=1;
        $itemsPerPage=20;
        $postData= array('categoryId' => (int)$categoryId,
            'currentPage' => (int)$currentPage,
            'itemsPerPage' => (int)$itemsPerPage,
            'viewType' => 1,
            'sortName' => 'popular',
            'sortDest' => 'desc',
            'filterInStock' => 1,
            'filterInStore' => 0);
        $url = 'https://5element.by/ajax/catalog_category_list.php?SECTION_ID=0';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // возвращает результат в переменную а не в буфер
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //использовать редиректы
     //   curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36'); //выставляем настройки браузера
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // работа с https
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // работа с https
        $html = curl_exec ($ch);
        $t= json_decode($html);
         var_dump($t);
        curl_close ($ch);
    }


    public function scrapGuzzle(){
        $client = new Client([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]); //GuzzleHttp\Client
        $response = $client->post('https://5element.by/ajax/catalog_category_list.php?SECTION_ID=273',[
        GuzzleHttp\RequestOptions::JSON => ['foo' => 'bar']
        ]);

            echo $response->getBody();

    }

    /**
     *
     * @return Response
     */
    public function scrapBuzz()
    {
        $loop = LoopFactory::create();
        $client = new Browser($loop);
        $scraper = new ScraperBuzz($client);
        $urls = ['https://5element.by/ajax/catalog_category_list.php?SECTION_ID=273'];
        $scraper->scrape($urls);
        $loop->run();
        $d = $scraper->getMovieData();
        dd($d);

    }

    public function index()
    {
        return view('pars::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('pars::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('pars::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('pars::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
