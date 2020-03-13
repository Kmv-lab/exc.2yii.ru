<?php

namespace app\commands;
use Yii;
use yii\web\Controller;

class helpers
{

    /**
     * @param array $model
     * @param string $defTitle
     * @param string $defH1
     * @param string $defDescription
     * @param string $defKeywords
     * @return boolean
     */
    public static function createSEO($model=[],$defTitle='',$defH1='',$defDescription='',$defKeywords='')
    {
        //  d::dump($defH1);
        //общее название сайта из параметров
        $CommonTitle = Yii::$app->params['common_title'];

        if ( !empty($model)  )
        {
            $seo_title         = empty ($model['seo_title'])       ? $defTitle.$CommonTitle    :   $model['seo_title'].$CommonTitle ;
            $seo_description   = empty ($model['seo_description']) ? $defDescription           :   $model['seo_description'];
            $seo_keywords      = empty ($model['seo_keywords'])    ? $defKeywords              :   $model['seo_keywords'];
            $seo_h1            = empty ($model['seo_h1'])          ? $defH1                    :   $model['seo_h1'];
            Yii::$app->params['seo_h1_span'] = empty ($model['seo_h1_span']) ? '' : $model['seo_h1_span'];
        }
        else
        {
            $seo_title         = $defTitle.$CommonTitle ;
            $seo_description   = $defDescription;
            $seo_keywords      = $defKeywords;
            $seo_h1            = !empty($defH1)   ?   $defH1   : '';
        }
        $seo_h1 = str_replace('««','«',$seo_h1);
        $seo_h1 = str_replace('»»','»',$seo_h1);
        $seo_title = str_replace('««','«',$seo_title);
        $seo_title = str_replace('»»','»',$seo_title);

        $seo_description   = htmlspecialchars($seo_description);
        $seo_keywords      = htmlspecialchars($seo_keywords);
        Yii::$app->view->title = $seo_title;
        Yii::$app->params['seo_h1'] = $seo_h1;
        Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => $seo_keywords]);
        Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $seo_description]);

        return true;
    }

    public static function sendEmail($subject, $message = '', $to = '', $from = '', $replyTo = '' )
    {
        $from       = !empty($from)     ? $from     : Yii::$app->params['from_email'];
        $to         = !empty($to)       ? $to       : Yii::$app->params['adminEmail'];
        // Если не задано кому ответить, тогда по умолчанию это поле от кого
        $replyTo    = !empty($replyTo)  ? $replyTo  : $from;

        $headers        = "From: $from\r\n";
        $headers       .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers       .= "Reply-To: $replyTo\r\n" ;
        $headers       .= "Return-Path: $from\r\n";

        if ( mail($to,$subject,$message,$headers) ) return true; else return false;
        /*
        Yii::$app->mailer->compose()
            ->setFrom('вставить сюда мыло из smtp')
            ->setTo($to)
            ->setSubject($subject)
            ->setHtmlBody($message)
            ->send(); */
    }

    public static function translit($str)
    {
        $str    =   trim($str);
        $str    =   mb_strtolower($str,mb_detect_encoding($str));
        $tr = [
            "А"=>"a","Б"=>"b","В"=>"v","Г"=>"g",
            "Д"=>"d","Е"=>"e","Ё"=>"o","Ж"=>"j","З"=>"z","И"=>"i",
            "Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
            "О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
            "У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch",
            "Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"yi","Ь"=>"",
            "Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ё"=>"o","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
            "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
            " "=> "-", "»"=> "-", "«"=> "-",// "."=> "-", "/"=> "-", "{" => "-", "}"=>"-",
            //  "–"=>"-", "|"=>"-", "�"=>"-", "~"=>"-"
        ];

        $str = strtr($str,$tr);
        $str =   preg_replace('/[^a-zа-я0-9\_\-]/', '-', $str);
        $str =   preg_replace('/(\-){1,}/', '-', $str);
        $str = str_replace('----','-',$str);
        $str = str_replace('---','-',$str);
        $str = str_replace('--','-',$str);

        if ($str[strlen($str)-1] == '-')
        {
            $str = substr($str,0,strlen($str)-1);
        }
        return $str;
    }

    public static function format_price($number, $decimals, $dec_point='.', $thousands_sep = ","){
        $price = number_format((float)$number,$decimals,$dec_point,$thousands_sep);
        $price = str_replace(',00', '', $price);
        $price = str_replace('.00', '', $price);
        $price = str_replace(',0', '', $price);
        $price = str_replace('.0', '', $price);
        return $price;
    }

    /**
     * @param string $content
     * @param bool $dont_call в случае включения вернет параметры для вызова первого виджета
     * @return mixed
     * Функция получает текстовую переменню, провряет содержимое на наличие сниппетов и возвращает результат их работы
     * */
    public static function checkForWidgets($content, $dont_call = false)
    {
        $content = self::search_url($content);
        //============ Обработка вызовов виджетов в тексте
        // регулярка для поиска виджетов вида <p>[*WidgetName**|{param:val,param:val}]]</p> или в div
      /*  $matches = [];
        $reg = "(<p>\[\*(.*)\*\*\]</p>)";
        preg_match_all($reg,$content,$matches);
        // Находим все совпадения в тексте текущей страницы

        $matches2 = [];
        $reg = "(<div>\[\*(.*)\*\*\]</div>)";
        preg_match_all($reg,$content,$matches2); */

        $matches = [];
        $reg = "(\[\*(.*)\*\*\])";
        preg_match_all($reg,$content,$matches);
      //  $matches[0] = array_merge($matches[0],$matches2[0], $matches3[0]);
       // $matches[1] = array_merge($matches[1],$matches2[1], $matches3[1]);
      //  vd($matches);

        //============ Обработка вызовов виджетов в тексте
        // регулярка для поиска виджетов вида [*WidgetName**|{param:val,param:val}]]
        /*  $reg = "(\[\*(.*)\*\*\])";
          // Находим все совпадения в тексте текущей страницы
          $matches = array();
          preg_match_all($reg,$content,$matches); */
        // Если совпадения найдены
        if ( !empty($matches) )
        {
            //перебираем все виджеты, упоминаемые на странице
            for($i=0;$i<count($matches[1]);$i++)
            {
                // заранее берём полный текст виджета вместе со служебными символами [* ]]
                $replacement    =   $matches[0][$i];
                //разбиваем вызов виджета на элементы массива,- отдельно имя виджета, отдельно строка его параметров
                $tempArr        =   explode('|',$matches[1][$i]);

                $widgetName     =   $tempArr[0];
                $widgetParams = [];
                if(isset($tempArr[1])){
                    $widgetParams   =   $tempArr[1];//Dump::d($widgetParams);die;
                    // заменяем &quot; на " из-за БД
                    $widgetParams   =   str_replace('&quot;','"',$widgetParams);
                    // преобразуем текстовую переменную с параметрами в массив
                    $widgetParams   =   json_decode($widgetParams, true); //Dump::d($widgetParams);die;
                    //Проверяем , если параметров нет, тогда делаем пустым массивом
                    $widgetParams   =   empty($widgetParams)    ?   [] :   $widgetParams;
                }
                // отдаём всё накопленное богатство в виджет
                $widgetName = '\app\widgets\\'.$widgetName;

                if($dont_call){
                    return ['name'=>$widgetName, 'params'=>$widgetParams];
                }
                $new_content = $widgetName::widget($widgetParams);
                // заменяем в контенте $replacement на результат работы виджета
                $content = str_replace( $replacement , $new_content ,$content );
            }
        }
        return $content;
    }

    public static function search_url($content){
        // регулярка для поиска  вида [*page:1**] или в div
        $matches = [];
        $reg = "(\[\*(page:.*)\*\*\])";
        preg_match_all($reg,$content,$matches);

        // Если совпадения найдены
        if ( !empty($matches) )
        {
            //перебираем все виджеты, упоминаемые на странице
            for($i=0;$i<count($matches[1]);$i++)
            {
                // заранее берём полный текст виджета вместе со служебными символами [* ]]
                $replacement    =   $matches[0][$i];
                $tempArr        =   explode(':',$matches[1][$i]);
                $id_page   =   $tempArr[1];
                $new_content = PagesHelper::getUrlById($id_page);
                // заменяем в контенте $replacement на результат работы виджета
                $content = str_replace( $replacement , $new_content ,$content );
            }
        }
        return $content;
    }
    function russian_date($timestamp = '', $year = true, $format = 'j.m.Y')
    {
        //echo $timestamp;
        $date   =   empty($timestamp)    ?   date($format)   :   date($format,$timestamp);
        $date   =   explode(".", $date);
        switch ($date[1]){
            case 1: $m='января'; break;
            case 2: $m='февраля'; break;
            case 3: $m='марта'; break;
            case 4: $m='апреля'; break;
            case 5: $m='мая'; break;
            case 6: $m='июня'; break;
            case 7: $m='июля'; break;
            case 8: $m='августа'; break;
            case 9: $m='сентября'; break;
            case 10: $m='октября'; break;
            case 11: $m='ноября'; break;
            case 12: $m='декабря'; break;
        }
        if($year)
            return $date[0].'&nbsp;'.$m.'&nbsp;'.$date[2];
        else
            return $date[0].'&nbsp;'.$m;
    }

}