<?php

namespace Modules\Pars\Entities;

use Curl\MultiCurl;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\DomCrawler\Crawler;

class Product extends Model
{
    protected $fillable = [];
    protected $table = 'pars_products';

    // связка для категорий
    public function category()
    {
        return $this->hasOne(Category::class, 'root_id', 'category_id');
    }

    // связка с акциями
    public function actions()
    {
        return $this->belongsToMany(Action::class, 'pars_action_product', 'product_id', 'action_id')->withTimestamps();;
    }

    static public function setPostData($categoryId, $currentPage, $itemsPerPage)
    {
        return array('categoryId' => $categoryId,
            'currentPage' => $currentPage,
            'itemsPerPage' => $itemsPerPage,
            'viewType' => 1,
            'sortName' => 'popular',
            'sortDest' => 'desc',
            'filterInStock' => 1,
            'filterInStore' => 0);
    }

    public function existProduct($product_id)
    {
        return Product::where('product_id', $product_id)->get();
    }

    /**
     * Парсинг продуктов
     */
    static public function productsPars()
    {
        ini_set('max_execution_time', 1200);
        echo date("H:i:s") . '<br>';


        // $categories = Category::where('active', 1)->where('products_cnt', '>', 0)->paginate(100)->get();
        //    Category::where('active', 1)->where('products_cnt', '>', 0)->where('site_id','=', 1191)->chunk(100, function ($categories) {
        Category::where('active', 1)->where('products_cnt', '>', 0)->orderBy('products_cnt', 'desc')->chunk(10, function ($categories) {
            $base_url = 'https://5element.by/ajax/catalog_category_list.php?SECTION_ID=';
            $mc = new MultiCurl();
            $mc->setTimeout(1200);
            $mc->setConcurrency(20);
            foreach ($categories as $category) {
                //    if ($category->site_id==1141) { dd($category);};
                for ($i = 0; $i <= ceil($category->products_cnt / 150); $i++) {
                    //   echo 'i='.$i. 'site_id='.$category->site_id . ',products_cnt '.$category->products_cnt.'<br>';
                    $mc->addPost($base_url . $category->site_id,
                        self::setPostData($category->root_id, $i, 150));
                }
            }
            //  exit();
            try {

                $mc->success(function ($instance) {
                    $crawler = new Crawler($instance->response->listHtml);
                    $products = $crawler->filter('.spec-product.js-product-item')->each(function (Crawler $node, $i) use ($instance) {
                        $data_product = $node->filter('.spec-product-left>a.product-link');
                        // берем только левое акционное предложение (Михалыч сказал)
                        $data_actions = $node->filter('.spec-product-left-middle-footer-right-first>a.product-item-sticker')->each(function (Crawler $node, $i) {
                            return [
                                'action_id' => $node->attr('data-action-id'),
                                'type' => $node->attr('data-action-type'),
                                'name' => $node->filter('a>img')->attr('alt'),
                            ];
                        });
                        /* $data_actions = $node->filter('a.product-item-sticker')->each(function (Crawler $node, $i) {
                             return [
                                 'action_id' => $node->attr('data-action-id'),
                                 'type' => $node->attr('data-action-type'),
                                 'name' => $node->filter('a>img')->attr('alt'),
                             ];
                         });*/
                        return ['product_id' => trim($data_product->attr('data-id')),
                            'name' => $data_product->attr('data-name'),
                            'brand' => $data_product->attr('data-brand'),
                            'price' => $data_product->attr('data-price'),
                            'site_id' => $instance->response->updateSection->section->ID,
                            'root_id' => $instance->response->updateSection->section->UF_IB_RELATED_ID,
                            'actions' => $data_actions
                        ];
                    });
                    foreach ($products as $product) {
                        // если нет товара добавляем
                        if (!Product::where('product_id', $product['product_id'])->exists()) {
                            $np = new Product();
                            $np->category_id = $product['root_id'];
                            $np->product_id = $product['product_id'];
                            $np->name = $product['name'];
                            $np->brand = $product['brand'];
                            $np->price = $product['price'];
                            $np->save();
                            // если нет акции добавляем
                            foreach ($product['actions'] as $act) {
                                if (!Action::where('action_id', '=', $act['action_id'])->exists()) {
                                    $action = new Action();
                                    $action->action_id = $act['action_id'];
                                    $action->name = $act['name'];
                                    $action->type = $act['type'];
                                    $action->save();

                                    //!!bas сделать проверку на присутствие
                                }
                                $np->actions()->attach(Action::where('action_id', '=', $act['action_id'])->get('id'));
                            }

                        }
                        //  exit();
                        //  echo 'call to "' . $instance->url . '" was successful.' . "\n";

                        //  echo 'response: ' . $instance->response . "\n";
                    }
                });
            } catch (Exception $e) {
                //  Statu
                echo 'Поймано исключение: ', $e->getMessage(), "\n";
            };


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
        });
    }
    // парсим конкрутную категорию
    static public function categoryPars($site_id)
    {
     //   Product::truncate();
        if (Category::where('site_id', '=', $site_id)->where('active', '=', true)->exists()) {
            $base_url = 'https://5element.by/ajax/catalog_category_list.php?SECTION_ID=';
            $mc = new MultiCurl();
            $mc->setTimeout(1200);
            $mc->setConcurrency(20);
            $categories = Category::where('site_id', '=', $site_id)->where('active', '=', true)->where('products_cnt', '>', 0)->get();
            foreach ($categories as $category) {
                for ($i = 0; $i <= ceil($category->products_cnt / 150); $i++) {
                    $mc->addPost($base_url . $category->site_id,
                        self::setPostData($category->root_id, $i, 150));
                }
            }

            $mc->success(function ($instance) {
                $crawler = new Crawler($instance->response->listHtml);
                $products = $crawler->filter('.spec-product.js-product-item')->each(function (Crawler $node, $i) use ($instance) {
                    $data_product = $node->filter('.spec-product-left>a.product-link');
                    // берем только левое акционное предложение (Михалыч сказал)
                    $data_actions = $node->filter('.spec-product-left-middle-footer-right-first>a.product-item-sticker')->each(function (Crawler $node, $i) {
                        return [
                            'action_id' => $node->attr('data-action-id'),
                            'type' => $node->attr('data-action-type'),
                            'name' => $node->filter('a>img')->attr('alt'),
                        ];
                    });
                    return ['product_id' => trim($data_product->attr('data-id')),
                        'name' => $data_product->attr('data-name'),
                        'brand' => $data_product->attr('data-brand'),
                        'price' => $data_product->attr('data-price'),
                        'site_id' => $instance->response->updateSection->section->ID,
                        'root_id' => $instance->response->updateSection->section->UF_IB_RELATED_ID,
                        'actions' => $data_actions
                    ];
                });
                foreach ($products as $product) {
                    // если нет товара добавляем
                    if (!Product::where('product_id', $product['product_id'])->exists()) {
                        $np = new Product();
                        $np->category_id = $product['root_id'];
                        $np->product_id = $product['product_id'];
                        $np->name = $product['name'];
                        $np->brand = $product['brand'];
                        $np->price = $product['price'];
                        $np->save();
                        // если нет акции добавляем
                        foreach ($product['actions'] as $act) {
                            if (!Action::where('action_id', '=', $act['action_id'])->exists()) {
                                $action = new Action();
                                $action->action_id = $act['action_id'];
                                $action->name = $act['name'];
                                $action->type = $act['type'];
                                $action->save();

                                //!!bas сделать проверку на присутствие
                            }
                            $np->actions()->attach(Action::where('action_id', '=', $act['action_id'])->get('id'));
                        }
                    }
                    //  echo 'call to "' . $instance->url . '" was successful.' . "\n";
                    echo 'call to "' . $instance->url . '" was successful.' . "<br>";
                }
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

    }

    // парсим все активные категории
    static public function categoriesPars(){
        ini_set('max_execution_time', 1200);
        $categories=Category::where('active','=',true)->where('products_cnt','>',0)->get();
        foreach ($categories as $category){
            self::categoryPars($category->site_id);
        }
    }
}
