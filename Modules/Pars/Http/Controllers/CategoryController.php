<?php

namespace Modules\Pars\Http\Controllers;

use function Couchbase\defaultDecoder;
use Curl\MultiCurl;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Pars\Entities\Category;
use Modules\Pars\Entities\Product;
use Symfony\Component\DomCrawler\Crawler;

class CategoryController extends Controller
{


    public function categoriesProducts()
    {
        $categories = Category::where('active', 1)->orderBy('root_id', 'asc')->get();
        return view('pars::categories.products_categories', [
            'categories' => $categories,
            'countCat' => $categories->count()
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $actCategories = Category::where('products_cnt', '>', 0)->where('active', 1)->orderBy('name', 'asc')->get();
        $inactCategories = Category::where('products_cnt', '>', 0)->where('active', 0)->orderBy('name', 'asc')->get();
        return view('pars::categories.index', [
            'actCategories' => $actCategories,
            'inactCategories' => $inactCategories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('pars::categories.create');
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
        return view('pars::categories.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('pars::categories.edit');
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

    public function inactive($id)
    {
        Category::inactiveCategory($id);
        return redirect('pars/categories');
    }


    public function active($id)
    {
        Category::activeCategory($id);
        return redirect('pars/categories');
    }

    public function activeCheck($ids)
    {
        $ids = json_decode($ids);
        foreach ($ids as $id) {
            Category::activeCategory($id);
        }
        return redirect('pars/categories');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        Category::destroy($id);
        return redirect('pars/categories')->with('message_delete', 'Categories deleted');
    }

    public function parsCategories()
    {
        ini_set('max_execution_time', 360);
        $cnt = 0;
        $parsCategories = Category::parsCategories();
        foreach ($parsCategories as $parsCategory) {
            if (!Category::where('url', '=', $parsCategory['url'])->exists()) {
                $category = new Category();
                $category->shop_id = 1;
                $category->name = substr($parsCategory['name'], 0, 255);
                $category->root_id = 0;
                $category->site_id = preg_match('~(\d+)~', $parsCategory['url'], $out) ? $out[0] : 0;
                $category->url = $parsCategory['url'];
                $category->active = 0;
                $category->save();
                $cnt++;
            }
        }
        // обновляем кол-во в категориях
        Category::updateProductCnt();
        return redirect('pars/categories')->with('message_parse', 'Категории скопированы. Кол-во: ' . $cnt);
    }


    // обновляем кол-во по категорияи
    public function CategoriesUpdate()
    {
        Category::updateProductCnt();
        return redirect('pars/categories')->with('message_parse', 'Категории обновлены');

    }

    public function setPostData($categoryId)
    {
        return array('categoryId' => $categoryId,
            'currentPage' => 1,
            'itemsPerPage' => 150,
            'viewType' => 1,
            'sortName' => 'popular',
            'sortDest' => 'desc',
            'filterInStock' => 1,
            'filterInStore' => 0);
    }

    public function test()
    {
        //   dd(Category::find(1)->shop->name);

        // тестирование регулярки
//        $s='/catalog/1234-43--';
//        preg_match('~(\d+)~', $s , $m );
//       dd($m) ;
        // Category::getCategoryDesc(977);
        // multicurl обертка


        $postData = array('categoryId' => 24155,
            'currentPage' => 1,
            'itemsPerPage' => 150,
            'viewType' => 1,
            'sortName' => 'popular',
            'sortDest' => 'desc',
            'filterInStock' => 1,
            'filterInStore' => 0);


        echo date("H:i:s");
        $base_url = 'https://5element.by/ajax/catalog_category_list.php?SECTION_ID=';

        $mc = new MultiCurl();
        $mc->setConcurrency(20);
        $categories = Category::where('active', 1)->get();
        foreach ($categories as $category) {
            $mc->addPost($base_url . $category->site_id, $this->setPostData($category->root_id));
        }
        $mc->success(function ($instance) {
            $crawler = new Crawler($instance->response->listHtml);
            $products = $crawler->filter('.spec-product-left>a.product-link')->each(function (Crawler $node, $i) use ($instance) {

                return ['id' => trim($node->attr('data-id')),
                    'name' => $node->attr('data-name'),
                    'brand' => $node->attr('data-brand'),
                    'price' => $node->attr('data-price'),
                    'site_id' => $instance->response->updateSection->section->ID,
                    'root_id' => $instance->response->updateSection->section->UF_IB_RELATED_ID
                ];
            });
            foreach ($products as $product) {
                $np = new Product();
                $np->category_id = $product['root_id'];
                $np->product_id = $product['id'];
                $np->name = $product['name'];
                $np->brand = $product['brand'];
                $np->price = $product['price'];
                $np->save();
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
        echo date("H:i:s");

    }


  ///////////////////////////// 21 ВЕК ///////////////////

  /// // парсинг категорий 21 века
   public function categoriesPars_21(){
        dd('categories Pars');
       Category::categoriesPars_21();
   }

// обновляем количество товаров в категориях 21 век////
    public function updateProductCnt_21(){
        Category::updateProductCnt_21();
    }


// максимаольное кол-во в категории и в магазине
    public function maxProductCategory($shop_id=0, $category_id=0){
        //dd($shop_id,$category_id);
        Category::maxProductCategory($shop_id, $category_id );
    }
}
