<?php
namespace app\models;

use Symfony\Component\DomCrawler\Form;
use yii\base\Model;
use Yii;

class OrderForm extends Model
{
    public $name;
    public $phone;
    public $email;
    public $comment;

    public $personal_data = 1;
    public $sms = 1;
    public $promotion_information = 0;
    public $for_advertising = 0;

    public function rules()
    {
        return [
            [['name', 'phone', 'email'], 'trim'],
            [['name', 'phone', 'personal_data'], 'required'],
            [['name'], 'string', 'max' => 255, 'min'=>10],//16
            ['email', 'email'],
            ['phone', 'match', 'pattern' => '/^\+[0-9]\ \([0-9]{3}\)\ [0-9]{3}-[0-9]{2}-[0-9]{2}$/', 'message' => 'Введите номер телефона' ],
            ['comment', 'string', 'max' => 1000],
            [['sms', 'promotion_information', 'for_advertising'], 'boolean'],
            ['personal_data', 'integer', 'min'=>1, 'max'=>1, 'tooSmall'=>'Пожалуйста, подтвердите согласие на обработку данных.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Фамилия, Имя, Отчество',
            'phone' => 'Контактный телефон',
            'email' => 'Email',
            'comment' => 'Комментарий к заказу',
            'personal_data' => '<span> Для оформления заказа даю согласие с <a href="/pages/privacypolicy">Правилами использования сайта</a> и даете <a href="/pages/personaldata">Согласие на обработку персональных данных</a></span>',
            'sms' => 'Согласен получить смс с уведомлением о статусе заказа',
            'promotion_information' => 'Согласен получить информацию об акциях и спецпредложениях',
            'for_advertising' => 'Согласен на использование фотографий заказанной продукции для рекламы',
        ];
    }

    /* Сохраняет форму и все данные о фото
     * которые хранились в сессии. Переносит фото в постоянную папку. */
    public function allSave(){
        if($this->validate()){
            $columns = $this->attributes; //форма
            $session = Yii::$app->session;
            $session->open();
            $session_images = [];
            if(isset($_SESSION['cart'])){
                $session_images = $_SESSION['cart'];
            }
            $formats = \app\models\PhotoForm::getFormats();
            $columns['sum'] = 0;
            $photos = [];
            foreach ($session_images AS $key=>$image){
                $photos[$key] = new PhotoForm();
                $photos[$key]->attributes = $image['model'];
                $columns['sum'] += (int)($formats['price'][$image['model']['format']]*$image['model']['count']*100);
            }
            $columns['date_create'] = time();
            $columns['status'] = 1;
            Yii::$app->db->createCommand()->insert('orders',$columns)->execute();
            $id_order = $columns['id_order'] = Yii::$app->db->getLastInsertId();
            $tempDIR = Yii::$app->params['full_path_to_session_images'];
            $DIR = Yii::$app->params['full_path_to_orders_images'];
            if(!file_exists ($DIR.$id_order)){
                mkdir($DIR.$id_order, 0755);
            }
            if(!file_exists ($DIR.$id_order.'/big')){
                mkdir($DIR.$id_order.'/big', 0755);
            }

            foreach ($photos AS $key=>$photo){
                if($photo->validate()){
                    rename($tempDIR.$session_images[$key]['name'], $DIR.$id_order.'/big/'.$session_images[$key]['name']);
                    $col_photo = $photo->attributes;
                    $col_photo['id_order'] = $id_order;
                    $col_photo['file_name'] = $session_images[$key]['name'];
                    $col_photo['price'] =  (int)($formats['price'][$col_photo['format']]*100);
                    Yii::$app->db->createCommand()->insert('order_photos',$col_photo)->execute();
                    unset($session_images[$key]);
                }
            }
            $_SESSION['cart'] = $session_images;
            $session->close();
            return $columns;
        }else{
            return false;
        }
    }

    public function sendEmail($email, $id)
    {
        if ($this->validate()) {
            $columns = $this->attributes;
            $columns['id'] = $id;
            $columns['to_email'] = $email;
            Yii::$app->mailer->compose('request', $columns)
                ->setTo($email)
                ->setSubject('Добавлена новая заявка')
                ->send();

            return true;
        }
        return false;
    }

}