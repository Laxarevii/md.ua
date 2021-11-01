<section class="section form-feedback-section">
    <div class="container">
        <div class="form-feedback-group">
            <div class="form-feedback-wrapper">
                <div class="form-feedback-title">{{__t('Напишите нам')}}</div>
                <form class="validate" action="{{route('feedback')}}" method="post">
                    <div class="row-inline">
                        <div class="col-inline-xs-6 bottom form-feedback-column">
                            <div class="form-row">
                                <label>{{__t('Введите E-mail')}}:</label>
                                <div class="field-input">
                                    <input type="text" name="feedback_mail" autocomplete="off" required placeholder="{{__t('Ваш E-mail')}}"/>
                                </div>
                            </div>
                            <div class="form-row">
                                <label>{{__t('Введите сообщение')}}:</label>
                                <div class="field-input">
                                    <textarea name="feedback_message" autocomplete="off" placeholder="{{__t('Ваше сообщение')}}"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-inline-xs-6 bottom form-feedback-column">
                            <div class="form-row is-recaptcha">
                                <div class="recaptcha-wrapper">
                                    <div id="feedbackRecaptcha" class="recaptcha"></div>
                                    <label class="error-label">{{__t('reCaptcha: ошибка валидации')}}</label>
                                </div>
                            </div>
                            <div class="form-row is-submit">
                                <button type="submit" class="waves-effect active-button">{{__t('Отправить')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>




