<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace Modules\Pars\Entities{
/**
 * Modules\Pars\Entities\Action
 *
 * @property int $id
 * @property int $action_id
 * @property string $name
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Pars\Entities\Action[] $products
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Action newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Action newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Action query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Action whereActionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Action whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Action whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Action whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Action whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Action whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Action extends \Eloquent {}
}

namespace Modules\Pars\Entities{
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
	class Category extends \Eloquent {}
}

namespace Modules\Pars\Entities{
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
 * @property int $patio_code
 * @property int $active
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Product whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Product wherePatioCode($value)
 */
	class Product extends \Eloquent {}
}

namespace Modules\Pars\Entities{
/**
 * Modules\Pars\Entities\Shop
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Pars\Entities\Category[] $categories
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Shop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Shop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Shop query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Shop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Shop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Shop whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Shop whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Shop whereUrl($value)
 * @mixin \Eloquent
 * @property int $active
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\Shop whereActive($value)
 */
	class Shop extends \Eloquent {}
}

namespace Modules\Pars\Entities{
/**
 * Modules\Pars\Entities\start
 *
 * @property int $id
 * @property int $shop_id Ссылка на магазин
 * @property int $status_id Ссылка на статус
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\start newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\start newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\start query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\start whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\start whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\start whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\start whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\start whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class start extends \Eloquent {}
}

namespace Modules\Pars\Entities{
/**
 * Modules\Pars\Entities\status
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\status newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\status newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\status query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\status whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\status whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\status whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Pars\Entities\status whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class status extends \Eloquent {}
}

