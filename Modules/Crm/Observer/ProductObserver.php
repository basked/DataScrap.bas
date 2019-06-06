<?php

namespace Modules\Crm\Observer;

use Modules\Crm\Entities\Product;

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
class ProductObserver
{
    // после создания
    public function created(Product $product)
    {
        if ($product->quantity < 10) {
            $product->description = 'basked';
            $product->update();
        }
    }
    // перед созданием
    public function creating(Product $product)
    {
        $tax = 5;
        if ($product->quantity < 10) {
            $product->price += $product->price * $tax;
        } else if ($product->quantity >= 10) {
            $product->price += $product->price * ($tax / 2);
        }
    }

}
