<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as Controller;
use Receipt;
// added by LZRV 18.06.21 t.me/Lazarev_iLiya START
class ReceiptController extends Controller
{
    public function showReceiptPage($slug, $id)
    {
        $page = Receipt::find($id);

        if( !$page )
            App::abord(404);

        return view('pages.receipt_page', compact('page'));
    }
}
