<?php

namespace Modules\Crm\Observer;
use Modules\Crm\Entities\Shop;

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
class ShopObserver
{
//перед созданием
    public function creating(Shop $shop)
    {
      $shop->name='ОАО '.$shop->name;
    }
}
