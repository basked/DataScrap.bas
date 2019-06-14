<?php

namespace Modules\Pars\Entities;

use Curl\MultiCurl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Modules\Pars\Entities\Product
 *
 * @property int $id
 * @property int $category_id
 * @property string $product_id
 * @property string $brand
 * @property string $name
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Pars\Entities\Action[] $actions
 * @property-read \Modules\Pars\Entities\Category $category
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Product whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Product whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
            if (env('USE_PROXY')){
                $mc->setProxy('172.16.15.33',3128,'gt-asup6','teksab');
            };
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
    static public function categoriesPars()
    {
        ini_set('max_execution_time', 1200);
        $categories = Category::where('active', '=', true)->where('products_cnt', '>', 0)->get();
        foreach ($categories as $category) {
            self::categoryPars($category->site_id);
        }
    }

    // парсим все  категории, которые не спарсились
    static public function categoriesNullPars()
    {
        ini_set('max_execution_time', 1200);
        $categories = DB::select('SELECT
                                        c.root_id, c.site_id, c.products_cnt
                                    FROM
                                        pars_categories c left join
                                            (
                                                SELECT
                                            category_id,
                                            COUNT(category_id) products_cnt
                                        FROM
                                           pars_products  
                                                    
                                            GROUP BY category_id ) p
                                    ON c.root_id=p.category_id
                                    where c.active=1 and c.products_cnt>0  and c.products_cnt>ifnull(p.products_cnt,0)
                                    order by products_cnt desc  ');
        foreach ($categories as $category) {
            self::categoryPars($category->site_id);
        }
    }

    // импорт продуктов в SAM DB
    static public function productsImportToSam()
    {
        //запись о новой вставке
        DB::connection('mysql_sam')->table('s_pars_main_5')->insert(['act' => 1, 'date' => now(), 'date_end' => now(),'thread'=>0]);
        $max_main_id = DB::connection('mysql_sam')->table('s_pars_main_5')->max('id');
        // добавляем продукты которых нет SAM
        DB::connection('mysql_sam')->insert('insert into user1111058_sam.s_pars_product_5(category_id,prodId,name,cod) SELECT distinct category_id,prodId,name,cod FROM user1111058_oc_db.s_pars_product_5 WHERE prodid NOT IN (SELECT DISTINCT prodid FROM user1111058_sam.s_pars_product_5)');
        // добавляем акции которых нет SAM
        DB::connection('mysql_sam')->insert('insert into user1111058_sam.s_pars_oplata_5(creditId,name) SELECT distinct careditId,name FROM user1111058_oc_db.s_pars_oplata_5 WHERE careditId NOT IN (SELECT DISTINCT creditId FROM user1111058_sam.s_pars_oplata_5)');
        // добавляем цены
        DB::connection('mysql_sam')->insert('insert into user1111058_sam.s_pars_cena_5(product_id,cena,oplata_id,main_id) SELECT DISTINCT p.id, oc_db.price, o.id,'.$max_main_id.' FROM user1111058_sam.s_pars_product_5 p, user1111058_sam.s_pars_oplata_5 o, (SELECT DISTINCT a.action_id AS act_action_id, p.product_id AS prod_product_id, p.price FROM user1111058_oc_db.pars_actions a, user1111058_oc_db.pars_products p, user1111058_oc_db.pars_action_product ap WHERE a.id = ap.action_id AND p.id = ap.product_id ORDER BY 2,1) oc_db WHERE p.prodId = oc_db.prod_product_id AND o.creditId = oc_db.act_action_id');
    }

}
