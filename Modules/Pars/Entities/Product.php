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
        ini_set('max_execution_time', 360);
        echo date("H:i:s") . '<br>';
        $base_url = 'https://5element.by/ajax/catalog_category_list.php?SECTION_ID=';
        $mc = new MultiCurl();
        $mc->setTimeout(60);
        $mc->setConcurrency(40);
        $categories = Category::where('active', 1)->where('products_cnt', '!=', 0)->get();
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
                $data_actions = $node->filter('a.product-item-sticker')->each(function (Crawler $node, $i) {
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
                        $np->actions()->attach(Action::where('action_id','=',$act['action_id'])->get('id'));
                    }

                }
                //  exit();
                // echo 'call to "' . $instance->url . '" was successful.' . "\n";

                // echo 'response: ' .  dd($instance->response) . "\n";
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
        echo date("H:i:s");
    }
}
