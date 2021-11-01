@if($products)
<!--  added by LZRV 17.06.21 t.me/Lazarev_iLiya START -->
    <style>
        .table_products_in_kit{
            width: 100%;
        }
        .table_products_in_kit td,
        .table_products_in_kit th
        {
            padding: 5px;
            border: 1px solid #aaa;
        }
        .table_products_in_kit tbody tr:hover td{
            background-color: #F2F2F2;
        }
    </style>
    <table class="table_products_in_kit">
        <thead>
        <tr>
            <th>{{__t('Код')}}</th>
            <th style="width: 100%">{{__t('Название')}}</th>
            <th style="width: 100%">{{__t('Количество')}}</th>
            <th style="width: 100%">{{__t('Единица измерения')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{$product->code}}</td>
                <td><a href="{{$product->getUrl()}}" target="_blank">{{$product->title}}</a></td>
                <td>{{$orderProducts[$product->id]['qty']}}</td>
                <td>{{$dimensions[$orderProducts[$product->id]['dimension']]->title or '-'}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif