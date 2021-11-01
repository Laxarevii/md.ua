<section class="section form-order-section">
    <div class="container">
        <div class="form-order-group">
            <div class="form-order-title">{{__t('Вы можете отправить заявку для получения подробной информации о продуктах')}}.</div>
            <div class="form-order-wrapper">
                {{--<form name="order-form" class="validate" action="{{route('order.create')}}" method="post">--}}


                    <div class="form-row">
                        <div class="row-inline">
                            <div class="col-inline-xs-6 top">
                                <label>{{__t('Введите Имя')}}:</label>
                                <div class="field-input">
                                    <input type="text" name="order_name" autocomplete="off" required placeholder="{{__t('Ваше Имя')}}"/>
                                </div>
                            </div>
                            <div class="col-inline-xs-6 top">
                                <label>{{__t('Введите номер телефона')}}:</label>
                                <div class="field-input">
                                    <input type="tel" name="order_phone" value="" autocomplete="off" required/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="row-inline">
                            <div class="col-inline-xs-6 top">
                                <label>{{__t('Введите E-mail')}}:</label>
                                <div class="field-input">
                                     <input type="text" name="order_mail" autocomplete="off" required placeholder="{{__t('Ваш E-mail')}}"/>
                                </div>
                            </div>
                            <div class="col-inline-xs-6 top">
                                <label>{{__t('Выберете представительство')}}:</label>
                                <div class="field-input">
                                    <select name="order_residence">
                                        @foreach($residences as $residence)
                                            @if($residence->email)
                                                <option value="{{$residence->id}}">{{$residence->t('title')}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <label>{{__t('Введите сообщение')}}:</label>
                        <div class="field-input">
                             <textarea name="order_comment" autocomplete="off" placeholder="{{__t('Ваше сообщение')}}"></textarea>
                        </div>
                    </div>
                    <div class="form-row is-submit">
                        <div class="row-inline">
                            <div class="col-inline-xs-6 middle">
                                <div class="recaptcha-wrapper">
                                    <div id="ordeRecaptcha" class="recaptcha"></div>
                                    <label class="error-label">{{__t('reCaptcha: ошибка валидации')}}</label>
                                </div>
                            </div>
                            <div class="col-inline-xs-6 middle">
                                <button type="submit" class="waves-effect active-button">{{__t('Отправить')}}</button>
                            </div>
                        </div>
                    </div>
                {{--</form>--}}
            </div>
        </div>
    </div>
</section>