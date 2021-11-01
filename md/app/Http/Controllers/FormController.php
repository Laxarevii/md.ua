<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Setting;
use MailLog;
use Cart;
use ProductDimension;

// added by LZRV 09.06.21 t.me/Lazarev_iLiya START
class FormController extends Controller{

    protected $secret = "XXfh9B8UAXXXXXXG2PsqSEkGcKilTUUntCAUe5W0Mn";

    public function handleCallback(){

        parse_str(request()->input('data'), $data);

        if ($this->checkCaptcha()) {

            Mail::send('emails.callback', ['phone' => $data['callback_phone']], function($mail){
                $mail->to( explode(',', Setting::get('callback-email') ));
                $mail->subject('Заявка на обратный звонок');
                $mail->from('no-reply@md.ua', 'Морской Дом');
            });

            $mailLog = new MailLog();
            $mailLog->from = 'no-reply@md.ua';
            $mailLog->subject = 'Заявка на обратный звонок';
            $mailLog->to = Setting::get('callback-email');
            $mailLog->message = 'Запрос на обратный звонок: ' . $data['callback_phone'];

            $mailLog->save();

            return response()->json([
                'status' => 'success',
                'message' => __t('Callback: успех')
            ]);

        }

        return response()->json([
            'status' => 'error',
            'message' => __t('Callback: ошибка')
        ]);

    }

    public function handleFeedback(){

        parse_str(request()->input('data'), $data);

        if ($this->checkCaptcha()) {

            Mail::send('emails.feedback', ['feedback_mail' => $data['feedback_mail'], 'feedback_message' => $data['feedback_message']], function( $mail ){
                $mail->to( explode(',', Setting::get('feedback-email')) );
                $mail->subject('Сообщение из формы обратной связи');
                $mail->from('no-reply@md.ua', 'Морской Дом');
            });

            $mailLog = new MailLog();
            $mailLog->from = $data['feedback_mail'];
            $mailLog->subject = 'Сообщение из формы обратной связи';
            $mailLog->to = Setting::get('feedback-email');
            $mailLog->message = $data['feedback_message'];

            $mailLog->save();

            return response()->json([
                'status' => 'success',
                'message' => __t('Обратная связь: успех')
            ]);

        }

        return response()->json([
            'status' => 'error',
            'message' => __t('Обратная связь: ошибка')
        ]);

    }

    public function handleOrder(){
        
        parse_str(request()->input('data'), $data);

        if ($this->checkCaptcha()) {
            $cart = Cart::content();
            $order = app()->make('App\Http\Controllers\ShopController')->createOrder($data);

            if ($order) {

                if(Setting::get("order-email")){
                 
                    $toArray = explode(',', Setting::get("order-email"));

                    if($toArray){

                        Mail::send('emails.order', [
                            'order' => $order,
                            'products' => $cart,
                            'productDimensions' => ProductDimension::active()->orderBy('priority', 'asc')->get()
                        ], function( $mail ) use ($toArray){
                            $mail->to( $toArray );
                            $mail->subject( 'Новая заявка на сайте md.ua' );
                            $mail->from( 'no-reply@md.ua', 'Морской Дом' );
                        });
                    }
                    return response()->json([
                                            'status' => 'success',
                                            'message' => __t('Заказ: успех'),
                                            'hideCallback' => 'hideOrderPopup'
                                        ]);

                }

                $mailLog = new MailLog();
                $mailLog->from = 'order@md.ua';
                $mailLog->subject = 'Новая заявка на сайте';
                $mailLog->to = $order->customer_email;
                $mailLog->message = '
                    E-mail: '.$order->customer_email.'<br>
                    Коментарий: '.$order->comment.'<br>
                    Представительство: '.$order->residence->title.'<br>
                    
                    Заявка: <a href="'.request()->root().'/admin/orders?id=' . $order->id . '">' . $order->id .'</a>
                ';

                $mailLog->save();
                return response()->json([
                    'status' => 'success',
                    'message' => __t('Заказ: успех'),
                    'hideCallback' => 'hideOrderPopup'
                ]);
            }

        }

        return response()->json([
            'status' => 'error',
            'message' => __t('Заказ: ошибка'),
        ]);

    }

    private function checkCaptcha(){

        parse_str(request()->input('data'), $data);

        if( isset($data['g-recaptcha-response']) && $data['g-recaptcha-response']){
            $response = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='. $this->secret . '&response=' . $data['g-recaptcha-response']), true);

            if($response['success'] != false) {
                return true;
            }

        }

        return false;
    }

}
// added by LZRV 13.06.21 t.me/Lazarev_iLiya END