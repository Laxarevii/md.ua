<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Новая заявка на сайте</title>
</head>
<body>
    <h2>Новая заявка на сайте №{{$order->id}}</h2>
    <p>Имя : {{$order->customer_name}}</p>
    <p>Отправитель : {{$order->customer_email}}</p>
    <p>Телефон : {{$order->customer_phone}}</p>
    @if(isset($order->comment))
        <p>Комментарий: {{$order->comment}}</p>
    @endif
    {{--edited by LZRV 03.07.2021 t.me: @Lazarev_iLiya--}}
    <p>Продукты:</p>
    
    @foreach($products as $product)
        <p>{{$product->name}} К-во: {{$product->qty}}
            @foreach($productDimensions as $option)
                @if($option->id == $product->options->dimension)
                    {{$option->t('title')}}
                @endif
            @endforeach
        </p>
    @endforeach
    {{--edited by LZRV t.me: @Lazarev_iLiya--}}

    <p>Заказ <a href="{{request()->root()}}/admin/orders?id={{$order->id}}">№{{$order->id}}</a></p>
</body>
</html>