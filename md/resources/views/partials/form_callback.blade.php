<form class="validate" action="{{route('callback')}}" method="post">
    <div class="form-title">{{__t('Обратный звонок')}}</div>
    <div class="form-subtitle">
        {{__t('Пожалуйста, заполните поле. Наш менеджер перезвонит Вам в ближайшее время.')}}
    </div>
    <div class="form-row">
        {{--<label>{{__('Введите номер телефона')}}:</label>--}}
        <input type="tel" name="callback_phone" autocomplete="off" required/>
    </div>
    <div class="form-row">
        <div class="recaptcha-wrapper">
            <div id="callbackRecaptcha" class="recaptcha"></div>
            <label class="error-label">{{__t('reCaptcha: ошибка валидации')}}</label>
        </div>
    </div>
    <div class="form-row">
        <button type="submit" class="waves-effect active-button">{{__t('Отправить')}}</button>
    </div>
</form>