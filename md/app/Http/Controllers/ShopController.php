<?php 
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request,
    Vis\Builder\TreeController,
    CategoryTree,
    Product,
    DB,
    Cache,
    Brand,
    ProductCategories,
    CharacteristicValue,
    Characteristic,
    Excel,
    Jarboe,
    Tree,
    Cart,
    LaravelLocalization,
    Residence,
    Order,
    ProductDimension;

class ShopController extends TreeController
{
    public function showCatalog()
    {
        $page = $this->node;

        $catalog = CategoryTree::active()->where('depth', 1)->orderBy('lft', 'asc')->get();

        return view('pages.catalog', compact('page', 'catalog'));

    }

    public function showFilteredStores($filteredStr)
    {
        $shopData = [];
        $storesSlugs = [];
        $filtersSlugs = [];

        /*parsing url*/

        $shopData = $this->parseData(explode(';', $filteredStr));

        /* getting all stores for current category */
        $selectedStores = CategoryTree::active()->whereIn('slug', array_keys($shopData))->get();

        /* define first store in collection as page*/
        $page = $selectedStores[0]->parent;

        $stores = CategoryTree::active()->where('parent_id', $selectedStores[0]->parent_id)->where('template', 'store')->get();
        /**/

        /* collecting filters and characteristic values*/
        $scopeData = [];
        $valuesCount = [];
        foreach ($stores as &$store) {

            foreach ($store->characteristics as $characteristic) {

                foreach ($characteristic->characteristicValues as $value) {
                    if (isset($shopData[$store->slug][$characteristic->getSlug()])) {
                        if (in_array($value->getSlug(), $shopData[$store->slug][$characteristic->getSlug()])) {

                            $store->selectedFilters[] = $value->id;


                        }
                    }
                }
            }

        }
        /**/

        $storeIds = $selectedStores->pluck('id')->toArray();


        /*
         * filtered products by stores
         * stores only flatten array of ids
         *
         * todo make method in Store model
         */

        $productIds = [];

        foreach ($stores as &$store) {
            /*if store not selected - skip*/
            if (!in_array($store->id, $storeIds))
                continue;

            /* select product for current selected store */
            $store->filteredProductsIds = Product::active()
                ->select('products.id')
                ->join('products2categories', 'products2categories.id_product', 'products.id')
                ->where('products2categories.id_category', $store->id);
            /**/

            /* if exits selected filters for current store - prepare filtered query */
            if (isset($shopData[$store->slug]) && count($shopData[$store->slug])) {

                $store->filteredProductsIds->addSelect(DB::raw('count(`id_characteristic`) as c'));
                $store->filteredProductsIds->join('products2characteristics', 'products2characteristics.id_product', 'products.id');
                $store->filteredProductsIds->groupBy('products2characteristics.id_product');
                $store->filteredProductsIds->having('c', '=', count($shopData[$store->slug]));

                if ($store->selectedFilters) {

                    $store->filteredProductsIds = $store->filteredProductsIds->whereIn('products2characteristics.id_characteristic_value', $store->selectedFilters);
                }

            }
            /**/

            /* get product ids for current selected store*/
            $store->filteredProductsIds = $store->filteredProductsIds->get()->toArray();
            /**/

            /* collect product ids */
            if (count($store->filteredProductsIds)) {

                $productIds = array_merge($productIds, array_flatten($store->filteredProductsIds));
            }
            /**/

        }


        /**/

        /*preparing query for products with ids*/

        $products = Product::active()->whereIn('id', $productIds);

        /**/

        /*adding sort condition to query*/

        switch (request()->input('sort')) {
            case 'a-z':
                $products->orderBy(getWithLocalePostfix('title'), 'asc');
                break;
            case 'z-a':
                $products->orderBy(getWithLocalePostfix('title'), 'desc');
                break;
            default:
                $products->orderByRaw('FIELD(status, "1", "2", "3", "0")')->orderBy('priority', 'asc')->get();
                break;
        }
        /**/

        /* getting products */
        $products = $products->paginate(24);
        /**/

        $showAllUrl = $page->getUrl();

        if (request()->ajax()) {
            return response()->json([
                'html' => view('partials.store_products', compact('page', 'valuesCount', 'stores', 'filtersSlugs', 'storeIds', 'products', 'showAllUrl'))->render()
            ]);
        } else {
            return view('pages.store', compact('page', 'valuesCount', 'stores', 'storeIds', 'filtersSlugs', 'products', 'showAllUrl'));
        }

    }

    public function showStore($storeStr, $filteredStr = null)
    {


        $shopData = [];
        $storesSlugs = [];
        $filtersSlugs = [];

        /*parsing url*/
        $shopData = $this->parseData([$filteredStr ? $storeStr . ':' . $filteredStr : $storeStr]);

        /* getting all stores for current category */
        $selectedStores = CategoryTree::active()->whereIn('slug', array_keys($shopData))->get();

        /* define first store in collection as page*/
        $page = $selectedStores[0];

        /* getting all sibling stores if node hasn't depth 1. Don't use where condition DEPTH > 1, because current node wil be ignored*/
        if($page->depth != 1){
            $stores = CategoryTree::active()
                ->where('parent_id', $page->parent_id)
                ->where('template', 'store')
                ->get();
        }else{
            $stores = [$page];
        }

        /* collecting filters and characteristic values*/
        $scopeData = [];
        $valuesCount = [];
        foreach ($stores as &$store) {

            foreach ($store->characteristics as $characteristic) {

                foreach ($characteristic->characteristicValues as $value) {
                    if (isset($shopData[$store->slug][$characteristic->getSlug()])) {
                        if (in_array($value->getSlug(), $shopData[$store->slug][$characteristic->getSlug()])) {

                            $store->selectedFilters[] = $value->id;

                        }
                    }
                }
            }

        }
        /**/

        $storeIds = $selectedStores->pluck('id')->toArray();

        /*
         * filtered products by stores
         * stores only flatten array of ids
         *
         * todo make method in Store model
         */

        $productIds = [];

        foreach ($stores as &$store) {
            /*if store not selected - skip*/
            if (!in_array($store->id, $storeIds))
                continue;

            /* select product for current selected store */
            $store->filteredProductsIds = Product::active()
                ->select('products.id')
                ->join('products2categories', 'products2categories.id_product', 'products.id')
                ->where('products2categories.id_category', $store->id);
            /**/

            /* if exits selected filters for current store - prepare filtered query */
            if (isset($shopData[$store->slug]) && count($shopData[$store->slug])) {

                $store->filteredProductsIds->addSelect(DB::raw('count(`id_characteristic`) as count'));
                $store->filteredProductsIds->join('products2characteristics', 'products2characteristics.id_product', 'products.id');
                $store->filteredProductsIds->groupBy('products2characteristics.id_product');
                $store->filteredProductsIds->having('count', '=', count($shopData[$store->slug]));

                if ($store->selectedFilters) {

                    $store->filteredProductsIds = $store->filteredProductsIds->whereIn('products2characteristics.id_characteristic_value', $store->selectedFilters);

                }

            }
            /**/

            /* get product ids for current selected store and remove count property*/


            $store->filteredProductsIds = $store->filteredProductsIds->get()->map(function ($product) {
                unset($product->count);
                return $product;
            })->toArray();
            /**/

            /* collect product ids */
            if (count($store->filteredProductsIds)) {
                $store->filteredProductsIds = array_except($store->filteredProductsIds, ['c']);
                $productIds = array_merge($productIds, array_flatten($store->filteredProductsIds));
            }
            /**/
            //dd($productIds);
        }


        /**/

        /*preparing query for products with ids*/

        $products = Product::active()->whereIn('id', $productIds);

        /**/

        /*adding sort condition to query*/

        switch (request()->input('sort')) {
            case 'a-z':
                $products->orderBy(getWithLocalePostfix('title'), 'asc');
                break;
            case 'z-a':
                $products->orderBy(getWithLocalePostfix('title'), 'desc');
                break;
            default:
                $products->orderByRaw('FIELD(status, "1", "2", "3", "0")')->orderBy('priority', 'asc')->get();
                break;
        }
        /**/

        /* getting products */
        $products = $products->paginate(24);
        /**/

        $showAllUrl = $page->parent->getUrl();


        //dd($stores->first()->characteristics);
        if (request()->ajax()) {
            return response()->json([
                'html' => view('partials.store_products', compact('page', 'valuesCount', 'stores', 'filtersSlugs', 'storeIds', 'products', 'showAllUrl'))->render()
            ]);
        } else {
            return view('pages.store', compact('page', 'valuesCount', 'stores', 'storeIds', 'filtersSlugs', 'products', 'showAllUrl'));
        }

    }

    public function showCategory()
    {

        $page = $this->node;
        $stores = $page->stores;
        $storeIds = $stores->pluck('id')->toArray();

        $products = Product::active()
            ->select(DB::raw('products.*'))
            ->join('products2categories', 'products2categories.id_product', 'products.id')
            ->whereIn('products2categories.id_category', $storeIds)
            ->groupBy('products.id');

        switch (request()->input('sort')) {
            case 'a-z':
                $products->orderBy(getWithLocalePostfix('title'), 'asc');
                break;
            case 'z-a':
                $products->orderBy(getWithLocalePostfix('title'), 'desc');
                break;
            default:
                $products->orderByRaw('FIELD(status, "1", "2", "3", "0")')->orderBy('priority', 'asc')->get();
                break;
        }

        $products = $products->paginate(24);

        /*Prevent select stores*/
        $storeIds = [];

        $showAllUrl = $page->getUrl();

        return view('pages.store', compact('page', 'stores', 'storeIds', 'products', 'showAllUrl'));

    }

    public function showCart()
    {

        if (!count(Cart::content())) {
            return redirect()->to(LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), '/'));
        }

        $page = app()->make('Tree')->where('template', 'cart')->first();

        $productDimensions = ProductDimension::active()->orderBy('priority', 'asc')->get();

        return view('pages.cart_page', compact('page', 'productDimensions'));

    }

    public function addToCart()
    {
        $requestItem = request()->input('item');
        $itemCount = request()->input('count', 1);
        $cartItem = null;

        if (!isset($requestItem['id']) || !$requestItem['id'])
            return response()->json([
                'status' => 'error',
                'message' => 'SHOP: ..item id is not defined..'
            ]);

        if (!isset($requestItem['model']) || !$requestItem['model'])
            return response()->json([
                'status' => 'error',
                'message' => 'SHOP: ..item model is not defined..'
            ]);

        $item = $requestItem['model']::find($requestItem['id']);

        if (!$item)
            return response()->json([
                'status' => 'error',
                'message' => 'SHOP: ..item not found..'
            ]);

        if (!$item->inCart()) {
            Cart::add(['id' => $item->id, 'name' => $item->title, 'qty' => $itemCount, 'price' => 0])->associate($requestItem['model']);
        }

        return response()->json(
            [
                'status' => 'success',
                'cartAction' => 'add',
                'cartCount' => Cart::count(),
                'cartCountUnique' => count(Cart::content()),
                'cartTotal' => Cart::subTotal()
            ]
        );

    }

    public function changeCountCart()
    {

        $requestItem = request()->input('item');

        if ($requestItem['count']) {

            Cart::update($requestItem['rowId'], ['qty' => $requestItem['count']]);

            return response()->json(
                [
                    'status' => 'success',
                    'cartAction' => 'update',
                    'cartCount' => Cart::count(),
                    'cartCountUnique' => count(Cart::content()),
                    'cartTotal' => Cart::subTotal()
                ]
            );
        }

    }

    public function changeDimensionCart()
    {

        $requestItem = request()->input('item');

        if ($requestItem['dimension']) {

            $item = Cart::update($requestItem['rowId'], ['options' => ['dimension' => $requestItem['dimension']]]);

            return response()->json(
                [
                    'status' => 'success',
                    'cartAction' => 'update',
                    'rowId' => $item->rowId,
                    'cartCount' => Cart::count(),
                    'cartCountUnique' => count(Cart::content()),
                    'cartTotal' => Cart::subTotal()
                ]
            );
        }

    }

    public function removeFromCart()
    {

        $requestItem = request()->input('item');
        $itemCount = request()->input('count', 1);
        $cartItem = null;

        if (!isset($requestItem['rowId']) || !$requestItem['rowId'])
            return response()->json([
                'status' => 'error',
                'message' => 'SHOP: ..rowId is not defined..'
            ]);

        Cart::remove($requestItem['rowId']);

        return response()->json(
            [
                'status' => 'success',
                'cartAction' => 'remove',
                'cartCount' => Cart::count(),
                'cartCountUnique' => count(Cart::content()),
                'cartTotal' => Cart::subTotal(),
                'redirect' => LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), '/')
            ]
        );

    }

    public function destroyCart()
    {

        Cart::destroy();

        return response()->json(
            [
                'status' => 'success',
                'redirect' => LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), '/')
            ]
        );

    }
    // added by LZRV 01.07.21 t.me/Lazarev_iLiya START
    public function createOrder($orderData)
    {

        $products = Cart::content();

        if (!count($products))
            return false;

        $residence = Residence::find($orderData['order_residence']);

        if (!$residence)
            return false;

        $order = new Order;
        $order->customer_email = $orderData['order_mail'];
        $order->customer_phone = $orderData['order_phone'];
        $order->customer_name = $orderData['order_name'];
        $order->comment = $orderData['order_comment'];
        $order->residence_id = $residence->id;

        $order->save();

        $order2products = [];

        foreach ($products as $product) {

            $order2products[] = [
                'order_id' => $order->id,
                'product_id' => $product->id,
                'qty' => $product->qty,
                'dimension' => $product->options->dimension
            ];

        }

        if ($order2products) {
            DB::table('order2product')->insert($order2products);
        }

        Cart::destroy();

        return $order;

    }// added by LZRV 01.07.21 t.me/Lazarev_iLiya START

    private function parseData($data)
    {

        foreach ($data as &$storeBlock) {
            $storeBlock = explode(':', $storeBlock);

            $storesSlugs[] = $storeBlock[0];
            $shopData[$storeBlock[0]] = [];

            if (isset($storeBlock[1])) {
                $storeBlock[1] = explode(',', $storeBlock[1]);

                foreach ($storeBlock[1] as &$filtersBlock) {

                    $filtersBlock = explode('=', $filtersBlock);

                    if (isset($filtersBlock[1])) {
                        $filtersBlock[1] = explode('_', $filtersBlock[1]);

                        $shopData[$storeBlock[0]][$filtersBlock[0]] = $filtersBlock[1];

                        foreach ($filtersBlock[1] as $filterSlug) {

                            $filtersSlugs[$storeBlock[0]][] = $filtersBlock[0] . "=" . $filterSlug;
                        }
                    }
                }
            }
        };

        return $shopData;
        /**/
    }

}