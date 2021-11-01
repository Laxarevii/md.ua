<?php

Route::pattern('id', '[0-9]+');
Route::pattern('slug', '[a-z0-9-]+');

include app_path('Http/view_composers.php');
include app_path('Http/helpers.php');

/*route robots.txt*/
Route::get('robots.txt', function(){
    return Response::make(Setting::get("robots_txt"), 200, ['Content-type' => 'text/plain']);
});
/**/
// edited by LZRV t.me:/Lazarev_iLiya 24.06.21
// Route::get('main.php', function(){
//     return Route::get('/');
// });
// edited by LZRV t.me: @Lazarev_iLiya END

Route::group(
    ['prefix' => 'admin', 'middleware' => 'auth.admin'],
    function () {

        Route::any('/cache-clear', function () {

            Cache::flush();

            File::cleanDirectory(storage_path('framework/views'));
            File::cleanDirectory(storage_path('debugbar'));

            if (request()->ajax()) {

                return '
                    <script>jQuery.smallBox({
                        title : "Кэш сайта был успешно очищен",
                        content : "",
                        color : "#659265",
                        timeout : 4000
                    });
                    </script>
                ';
            } else {

                return redirect()->to('/admin/tree');
            }

        });

        Route::any('/import_products', 'ProductController@import');

        Route::any('/download-catalog-file', 'ExportController@exportCatalog');
        Route::any('/download-products-file', 'ExportController@exportProducts');
        Route::any('/download-characteristics-file', 'ExportController@exportCharacteristics');
    }
);

Route::group(
    ['prefix' => LaravelLocalization::setLocale()],
    function () {

        require_once 'shop.php';

        Route::get('/articles/{slug}-{id}', 'ArticleController@showPage')->name('article');

        Route::get('/news/{slug}-{id}', 'NewsController@showPage')->name('news');

        Route::get('/brands/{slug}-{id}', 'BrandController@showPage')->name('brand');

        Route::get('/receipts/{slug}-{id}', 'ReceiptController@showReceiptPage')->name('receipt');

        Route::get('/search', 'SearchController@showPage')->name('search');

        Route::post('/ajax/callback', 'FormController@handleCallback')->name('callback');
        Route::post('/ajax/feedback', 'FormController@handleFeedback')->name('feedback');
        Route::post('/ajax/live-search', 'SearchController@liveSearch')->name('live-search');
        Route::post('/ajax/show-more-brand-products/{id}', 'BrandController@getRelatedProducts')->name('show-more-brand-products');

    }
);
