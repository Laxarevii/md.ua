<?php

class Order extends BaseModel
{
    protected $table = 'orders';

    public function products(){
        return $this->belongsToMany('Product', 'order2product');
    }

    public function residence(){
        return $this->hasOne('Residence', 'id', 'residence_id');
    }

    public function toXml(SimpleXMLElement $object, array $data)
    {
        foreach ($data as $key => $value) {
            if(is_numeric($key)){
                $key = 'item';
            }
            if (is_array($value)) {
                $new_object = $object->addChild($key);
                $this->toXml($new_object, $value);
            } else {
                $object->addChild($key, htmlspecialchars($value));
            }
        }
    }

}