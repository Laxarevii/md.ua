<?php
// added by LZRV 17.06.21 t.me/Lazarev_iLiya START
class Brand extends BaseModel
{
    protected $table = "brands";

    public function getUrl()
    {
         return route('brand', [$this->getSlug(), $this->id]);
    }

    public function related(){

        return $this->hasMany('Product', 'id_brand', 'id');

    }

}
