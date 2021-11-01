<?php
// added by LZRV 18.06.21 t.me/Lazarev_iLiya START
class Receipt extends BaseModel
{

    use  Vis\Builder\Helpers\Traits\GroupsFieldTrait;

    protected $table = "receipts";

    public function getUrl()
    {

        return route('receipt', [$this->getSlug(), $this->id]);
    }

    public function getCrumb(){

        return Tree::where('template', 'receipts_catalog')->first();
    }

    public function receipts(){

        return $this->belongsToMany('Receipt', 'receipt2receipt', 'id_page', 'id_related');
    }

    public function getIngridients(){

        if (!$this->ingridients) {
            return [];
        }

        $ingridients = $this->getArrayGroup("ingridients");

        return $ingridients;

    }
}
