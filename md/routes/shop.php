<?php

/*handle product links*/
Route::any('/products/{slug}-{id}', 'ProductController@showPage')->name('product');
/**/

/*handle store links. use only for products catalog*/
Route::any('/store/filtered/{filtersStr}', 'ShopController@showFilteredStores');
Route::any('/store/{storesStr}/{filtersStr?}', 'ShopController@showStore')->name('store');
//Route::any('/store/{storesStr}/{filtersStr}', 'ShopController@showStore');
/**/

Route::any('/cart', 'ShopController@showCart')->name('shop.cart');

/*handle ajax in store */
Route::prefix('/shop/ajax')->group(function(){

    Route::post('/add-to-cart', 'ShopController@addToCart')->name('cart.add');
    Route::post('/remove-from-cart', 'ShopController@removeFromCart')->name('cart.remove');
    Route::post('/change-count-cart', 'ShopController@changeCountCart')->name('cart.count');
    Route::post('/change-dimension-cart', 'ShopController@changeDimensionCart')->name('cart.dimension');
    Route::post('/destroy-cart', 'ShopController@destroyCart')->name('cart.destroy');

});
/**/

/*handle order*/
Route::any('/order-create', 'FormController@handleOrder')->name('order.create');
/**/

/*handle catalog links. use only for catalog pages*/
Route::group(['prefix' => 'catalog'], function () {

    $pathArray = array_reverse(explode('/', Request::path()));

    $slug = $pathArray[0];

    /* TODO look for potential not unique node and compare their parents */
    $node = CategoryTree::where('slug', $slug)->first();

    if ($node && $node->is_active) {
        /* making route on a fly */

        Route::any('/' . $node->slug, function () use ($node) {

            $config = config('builder.categories_tree');

            if (isset($config['templates'][$node->template])) {
                $action = explode('@', $config['templates'][$node->template]['action']);
            } else {
                $action = explode('@', $config['default']['action']);
            }

            return app()->make("App\\Http\\Controllers\\" . $action[0])
                ->callAction('init', array($node, $action[1]));

        });
    }

});
/**/

