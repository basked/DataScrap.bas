<?php

namespace Modules\Pars\Entities;

use Curl\Curl;
use Curl\MultiCurl;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\DomCrawler;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Modules\Pars\Entities\Category
 *
 * @property int $id
 * @property int $shop_id
 * @property string $name
 * @property int $root_id
 * @property int $site_id
 * @property string $url
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $products_cnt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Pars\Entities\Product[] $products
 * @property-read \Modules\Pars\Entities\Shop $shop
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Category whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Category whereProductsCnt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Category whereRootId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Category whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Category whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Category whereUrl($value)
 * @mixin \Eloquent
 */
class Category extends Model
{
    protected $table = 'pars_categories';
    protected $fillable = [];
    // protected $hidden =['site_id', 'root_id'];
//    protected $visible = ['*'];


    // саязь с продуктами
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'root_id');
    }

    // связь с магазином
    public function shop()
    {
        return $this->hasOne(Shop::class, 'id', 'shop_id');
    }

    // установка POST параметров
    static public function setPostData($categoryId, $currentPage, $itemsPerPage)
    {
        return ['categoryId' => (int)$categoryId,
            'currentPage' => (int)$currentPage,
            'itemsPerPage' => (int)$itemsPerPage,
            'viewType' => 1,
            'sortName' => 'popular',
            'sortDest' => 'desc',
            'filterInStock' => 1,
            'filterInStore' => 0,
            //       'fastFilterId' =>  1315,
            'updateUrl' => true

        ];
    }

    static public function setProxy(Curl $curl)
    {
        $curl->setProxy('172.16.15.33', '3128', 'gt-asup6', 'teksab');
    }

    // Парсим первый раз только ид категорий на сайте
    static public function getParsData()
    {
        $curl = new Curl();
        if (env('USE_PROXY')) {
            self::setProxy($curl);
        };
        $curl->get('https://5element.by/catalog');
        if ($curl->error) {
            echo 'Ошибка при парсинге в функции parsFirsLoop: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        } else {
            $crawler = new Crawler($curl->response);
            $data = $crawler->filter('li.catalog-prod-col-item')->each(function (Crawler $node, $i) {
                return ['shop_id' => 1,
                    'name' => trim($node->text()),
                    'root_id' => 0,
                    'site_id' => preg_match('~(\d+)~', $node->filter('a')->attr('href'), $out) ? $out[0] : 0,
                    'url' => $node->filter('a')->attr('href'),
                    'active' => 0
                ];
            });
            return $data;
        }
    }


    static public function getParsCategory($categoryId)
    {
        ini_set('max_execution_time', 720);
        $postData = array('categoryId' => (int)$categoryId,
            'currentPage' => 1,
            'itemsPerPage' => 10,
            'viewType' => 1,
            'sortName' => 'popular',
            'sortDest' => 'desc',
            'filterInStock' => 1,
            'filterInStore' => 0);
        $url = 'https://5element.by/ajax/catalog_category_list.php?SECTION_ID=' . $categoryId;
        $ch = curl_init();
        if (env('USE_PROXY')) {
            curl_setopt($ch, CURLOPT_PROXYAUTH, true);
            curl_setopt($ch, CURLOPT_PROXY, '172.16.15.33');
            curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
            curl_setopt($ch, CURLOPT_PROXYUSERNAME, 'gt-asup6');
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'teksab');
        };
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // возвращает результат в переменную а не в буфер
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //использовать редиректы
        //   curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36'); //выставляем настройки браузера
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // работа с https
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // работа с https
        $html = curl_exec($ch);
        $html = json_decode($html);

        if ($html->updateSection != '') {
            try {
                $ds['ID_INPUT'] = $categoryId;
                $ds['ID'] = $html->updateSection->section->ID;
                $ds['UF_IB_RELATED_ID'] = $html->updateSection->section->UF_IB_RELATED_ID;
                $ds['NAME'] = $html->updateSection->section->NAME;
                $ds['SEO_NAME'] = $html->updateSection->section->SEO_NAME;
                $ds['DETAIL_URL'] = $html->updateSection->section->DETAIL_URL;
                $ds['DETAIL_PICTURE'] = $html->updateSection->section->DETAIL_PICTURE;
                $ds['DATE_CREATE'] = $html->updateSection->section->DATE_CREATE;
                $ds['COUNT'] = $html->count;
            } catch (Exception $e) {
                echo 'Ошибка при парсинге категорий: category_id=' . $categoryId . '  ' . $e->getMessage();
            } finally {
                return $ds;
            }
        }

    }

    //Парсим данные с учетом внутреннего ID
    static public function parsData()
    {
        ini_set('max_execution_time', 360);
        $cnt = 0;
        $categoties = self::getParsData();
        foreach ($categoties as $category) {
            if (!Category::where('site_id', (int)$category['site_id'])->exists()) {
                $parsCategory = self::getParsCategory($category['site_id']);
                $category = new Category();
                $category->shop_id = 1;
                $category->name = substr($category['name'], 0, 255);
                $category->root_id = (int)$parsCategory['UF_IB_RELATED_ID'];
                $category->url = $category['url'];
                $category->site_id = $category['site_id'];
                $category->active = 0;
                $category->save();
                $cnt++;
            }
        }
        return $cnt;
    }


    // проверка на сучествование категории с таким же url
    static public function existCategory($site_id)
    {
        return Category::where('site_id', $site_id)->exists();
//        (!$category) ? false : true;
    }

    static public function parsCategories()
    {
        $url = 'https://5element.by/catalog';
        $ch = curl_init($url);
        if (env('USE_PROXY')) {

            curl_setopt($ch, CURLOPT_PROXYAUTH, true);
            curl_setopt($ch, CURLOPT_PROXY, '172.16.15.33');
            curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
            curl_setopt($ch, CURLOPT_PROXYUSERNAME, 'gt-asup6');
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'teksab');
        };

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // возвращает результат в переменную а не в буфер
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //использовать редиректы
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36'); //выставляем настройки браузера
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // работа с https
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // работа с https
        curl_setopt($ch, CURLOPT_POST, 1);
        $html = curl_exec($ch);

        $crawler = new DomCrawler\Crawler($html);

        $res = $crawler->filter('li.catalog-prod-col-item')->each(function (Crawler $node, $i) {
            return ['name' => trim($node->text()),
                'url' => $node->filter('a')->attr('href')];
        });
        return $res;
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

    static public function parsProducts()
    {
        $categoryId = 24505;
        $currentPage = 1;
        $itemsPerPage = 20;
        $postData = array('categoryId' => (int)$categoryId,
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
        $html = curl_exec($ch);
        curl_close($ch);
        return json_decode($html);
    }

    static public function inactiveCategory($id)
    {
        $category = Category::find($id);
        Product::where('category_id', $category->root_id)->delete();
        $category->active = 0;
        $category->save();
    }

    static public function activeCategory($id)
    {
        $category = Category::find($id);
        $category->active = 1;
        $category->save();
    }

    static public function updateProductsCntToZero()
    {
        Category::whereActive(true)->update(['products_cnt' => 0]);
    }

    static public function updateProductCnt()
    {
        self::updateProductsCntToZero();
        $url = 'https://5element.by/ajax/catalog_category_list.php?SECTION_ID=';
        $categories = Category::where('root_id', '>', 0)->get();
        $mc = new MultiCurl();
        if (env('USE_PROXY')) {
            $mc->setProxy('172.16.15.33', 3128, 'gt-asup6', 'teksab');
        };
        $mc->setConcurrency(10);
        foreach ($categories as $category) {
            $mc->addPost($url . $category->site_id, self::setPostData($category->root_id, 1, 10));
        }
        $mc->success(function ($instance) {
            try {
                $site_id = $instance->response->updateSection->section->ID;
                $category = Category::where('site_id', '=', $site_id)->first();
                $category->products_cnt = $instance->response->count;
                $category->save();
            } catch (Exception $e) {
                echo $e->getMessage();
            }

            // echo 'call to "' . $instance->url . '" was successful.' . "\n";

            // echo 'response: ' .  dd($instance->response) . "\n";
        });

        $mc->error(function ($instance) {
            echo 'call to "' . $instance->url . '" was unsuccessful.' . "<br>";
            echo 'error code: ' . $instance->errorCode . "<br>";
            echo 'error message: ' . $instance->errorMessage . "<br>";
        });
        $mc->complete(function ($instance) {
            echo 'call to "' . $instance->url . '" completed.' . "<br>";
        });
        $mc->start();


    }

//////////////////////////////////21 век /////////////////////////////////////
////////////////////// Функции для работы с 21 веком//////////////////////////

// парсинг категорий 21 века
    public static function categoriesPars_21()
    {
        $base_url = 'https://www.21vek.by';
        $curl = new Curl();
        $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
        if (env('USE_PROXY')) {
            $curl->setProxy('172.16.15.33', 3128, 'gt-asup6', 'teksab');
        };
        $data = $curl->post($base_url);
        $crawler = new Crawler($data);
        $categories = [];

        Category::whereShopId(2)->delete();
        $crawler->filter('#j-nav')->filter('.nav-sub__item.g-grouplinks__item ')->each(function (Crawler $node, $i) use ($categories) {


            $categories['data-ga_action'] = trim($node->filter('a')->attr('data-ga_action'));
            $categories['site_id'] = trim(preg_replace("/[^0-9]/", '', $categories['data-ga_action']));
            $categories['url'] = str_replace('//', '/', str_replace('https://www.21vek.by/', '', trim($node->filter('a')->attr('href'))));
            $categories['root1'] = trim($node->filter('a')->attr('data-ga_label'));
            $categories['root2'] = explode('/', $categories['data-ga_action'])[0];
            $categories['root3'] = trim($node->text());
            echo '<pre>';
            print_r($categories);
            echo '</pre>';
            // проверка первого уровня категорий
            if (!Category::where('name', '=', $categories['root1'])->exists()) {
                $category = new Category();
                $category->shop_id = 2;
                $category->name = $categories['root1'];
                $category->root_id = 0;
                $category->site_id = 0;
                $category->url = '';
                $category->active = true;
                $category->products_cnt = 0;
                $category->save();
            }
            // проверка второго уровня категорий
            if (!Category::where('name', '=', $categories['root2'])->exists()) {
                $category1 = new Category();
                $category1->shop_id = 2;
                $category1->name = $categories['root2'];
                $root_id = Category::where('name', '=', $categories['root1'])->get('id')[0]->id;

                $category1->root_id = $root_id;
                $category1->site_id = 0;
                $category1->url = '';
                $category1->active = true;
                $category1->products_cnt = 0;
                $category1->save();
            }
            // проверка третего уровня категорий
            if (!Category::where('name', '=', $categories['root3'])->exists()) {
                $category1 = new Category();
                $category1->shop_id = 2;
                $category1->name = $categories['root3'];
                $category1->root_id = Category::where('name', '=', $categories['root2'])->get('id')[0]->id;
                $category1->site_id = (int)$categories['site_id'];
                $category1->url = $categories['url'];
                $category1->active = true;
                $category1->products_cnt = 0;
                $category1->save();
            }
        });
    }


// обновление кол-ва товаров в катгории
    public static function updateProductCnt_21()
    {
        echo date("H:i:s");
        $base_url = 'https://www.21vek.by/';
        ini_set('max_execution_time', 720);
//        $categories= Category::whereShopId(2)->whereActive(true)->where('url','!=','')->get(['id','name','url']);
        $categories = Category::whereShopId(2)->whereActive(true)->where('url', '!=', '')->get(['id', 'name', 'url']);

        $mc = new MultiCurl();
        if (env('USE_PROXY')) {
            $mc->setProxy('172.16.15.33', 3128, 'gt-asup6', 'teksab');
        };
        $mc->setConcurrency(60);
        $mc->setTimeout(160);
        foreach ($categories as $category) {
            $mc->addGet($base_url . $category->url);
        }
        $mc->success(function ($instance) {
            try {
                $html = $instance->response;
                $crawler = new DomCrawler\Crawler($html);
                $cnt = (int)trim(explode('из', $crawler->filter('.cr-paginator_page_list')->text())[1]);
                $cat = Category::where('url', str_replace('https://www.21vek.by/', '', $instance->url))->first();
                $cat->products_cnt = $cnt;
                $cat->save();
            } catch (Exception $e) {
                echo 'Выброшено исключение: ', $e->getMessage(), "\n";
                echo $instance->url;
            };
        });
        $mc->error(function ($instance) {
            echo 'call to "' . $instance->url . '" was unsuccessful.' . "<br>";
            echo 'error code: ' . $instance->errorCode . "<br>";
            echo 'error message: ' . $instance->errorMessage . "<br>";
        });
        $mc->complete(function ($instance) {
            echo 'call to "' . $instance->url . '" completed.' . "<br>";
        });
        $mc->start();
        echo date("H:i:s");

    }

// ОБЩИЕ ФУНКЦИИ ДЛЯ ВСЕХ МАГАЗИНОВ
// максимаольное кол-во в категории и в магазине
    public static function maxProductCategory($shop_id = 0, $category_id = 0)
    {
        if ($category_id == 0) {
            // echo $shop_id.' '.$category_id;
            echo Category::whereShopId($shop_id)->whereActive(true)->sum('products_cnt');
        } else {
            echo Category::whereShopId($shop_id)->whereActive(true)->whereSiteId($category_id)->value('products_cnt');
        }
    }

}
