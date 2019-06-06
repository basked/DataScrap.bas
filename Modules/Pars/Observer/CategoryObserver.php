<?php

namespace Modules\Pars\Observer;

use Modules\Pars\Entities\Category;

/**
 * retrieved
 * creating
 * created
 * updating
 * updated
 * saving
 * saved
 * deleting
 * deleted
 * restoring
 * restored
 * Class ProductObserver
 * @package Modules\Crm\Observer
 */
class CategoryObserver
{
//перед созданием
    public function creating(Category $category)
    {
        $category->site_id = preg_match('~(\d+)~', $category->url, $out) ? $out[0] : 0;
        // долго парсится - поэтом лучше это сделать в отдельом цикле
        $data = Category::getParsCategory($category->site_id);
        $category->root_id = ($data['UF_IB_RELATED_ID'] > 0) ? $data['UF_IB_RELATED_ID'] : 0;
    }
}

/**
 * Created by PhpStorm.
 * User: baske
 * Date: 30.05.2019
 * Time: 1:53
 */
