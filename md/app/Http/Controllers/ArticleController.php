<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Article;
// added by LZRV 09.06.21 t.me/Lazarev_iLiya START
class ArticleController extends Controller
{
    public function showPage($slug, $id)
    {
        $page = Article::find($id);

        if(!$page)
            App::abort(404);

        $page->increment('rating');

        return view('pages.article_page', compact('page'));
    }
}
