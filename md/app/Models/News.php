<?php

use Carbon\Carbon;

class News extends BaseModel
{
    protected $table = 'news';

    public function getDate($format = '%d %B %Y')
    {
        $locale = LaravelLocalization::getCurrentLocaleRegional();

        setlocale(LC_TIME,"{$locale}.UTF8");
        return(strftime($format, strtotime($this->created_at)));
    }

    public function getCrumb(){

        return Tree::where('template', 'news')->first();
    }

    public function getUrl(){

        return route('news', [$this->getSlug(), $this->id]);
    }

    public function related()
    {
        return $this->belongsToMany('News', 'news2news', 'id_page', 'id_related');
    }
}
