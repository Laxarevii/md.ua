@if ($characteristics)
    @foreach($characteristics as $characteristic)
        <section>
            <label class="label">{{$characteristic->title}}</label>
            <label class="input">
                <select class="dblclick-edit-input form-control input-small unselectable" name="characteristics[{{$characteristic->id}}]">
                    <option value="">Выбрать</option>
                    <? $values = CharacteristicValue::where("id_characteristic", $characteristic->id)->orderBy("priority", "asc")->get(); ?>
                    @if(count($values))
                        @foreach($values as $value)
                            <option value="{{$value['id']}}"
                                    {{isset($characteristicsInProduct[$characteristic->id]) && $characteristicsInProduct[$characteristic->id] == $value['id'] ? "selected" : "" }}
                            >{{$value['title']}}</option>
                        @endforeach
                    @endif
                </select>
            </label>
        </section>
    @endforeach
@else
    <p style="text-align: center; font-weight:bold; padding: 30px 0">У категории товара пока нет доступных характеристик</p>
@endif