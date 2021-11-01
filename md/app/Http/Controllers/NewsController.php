<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use News;

class NewsController extends Controller
{
    public function showPage($slug, $id){

        $page = News::find($id);

        if(!$page)
            App::abort(404);

        $page->update([
            'rating' => $page->rating + 1
        ]);

        return view('pages.news_page', compact('page'));
    }
}
