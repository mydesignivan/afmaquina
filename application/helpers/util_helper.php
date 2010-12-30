<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function print_array($arr, $die=FALSE){
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
    if( $die ) die();
}

function normalize($text, $separator = "-"){
    $isUTF8 = (mb_detect_encoding($text." ",'UTF-8,ISO-8859-1') == 'UTF-8');

    $text = ($isUTF8) ? utf8_decode($text) : $text;
    $text = trim($text);

    $_a = utf8_decode("ÁÀãâàá");
    $_e = utf8_decode("ÉÈéè");
    $_i = utf8_decode("ÍÌíì");
    $_o = utf8_decode("ÓÒóò");
    $_u = utf8_decode("ÚÙúù");
    $_n = utf8_decode("Ññ");
    $_c = utf8_decode("Çç");
    $_b = utf8_decode("ß");
    $_dash = "\.,_ ";

    $text = preg_replace("/[$_a]/", "a", $text );
    $text = preg_replace("/[$_e]/", "e", $text );
    $text = preg_replace("/[$_i]/", "i", $text );
    $text = preg_replace("/[$_o]/", "o", $text );
    $text = preg_replace("/[$_u]/", "u", $text );
    $text = preg_replace("/[$_n]/", "n", $text );
    $text = preg_replace("/[$_c]/", "c", $text );
    $text = preg_replace("/[$_b]/", "ss", $text );

    $text = preg_replace("/[$_dash]/", $separator, $text );
    $text = preg_replace("/[^a-zA-Z0-9\-]/", "", $text );

    $text = strtolower($text);

    return ($isUTF8) ? utf8_encode($text) : $text;
}

function get_filename($text){
    $separator = "_";

    $isUTF8 = (mb_detect_encoding($text." ",'UTF-8,ISO-8859-1') == 'UTF-8');

    $text = ($isUTF8) ? utf8_decode($text) : $text;
    $text = trim($text);

    $_a = utf8_decode("ÁÀãâàá");
    $_e = utf8_decode("ÉÈéè");
    $_i = utf8_decode("ÍÌíì");
    $_o = utf8_decode("ÓÒóò");
    $_u = utf8_decode("ÚÙúù");
    $_n = utf8_decode("Ññ");
    $_c = utf8_decode("Çç");
    $_b = utf8_decode("ß");
    $_dash = "\,_ ";

    $text = preg_replace("/[$_a]/", "a", $text );
    $text = preg_replace("/[$_e]/", "e", $text );
    $text = preg_replace("/[$_i]/", "i", $text );
    $text = preg_replace("/[$_o]/", "o", $text );
    $text = preg_replace("/[$_u]/", "u", $text );
    $text = preg_replace("/[$_n]/", "n", $text );
    $text = preg_replace("/[$_c]/", "c", $text );
    $text = preg_replace("/[$_b]/", "ss", $text );

    $text = preg_replace("/[$_dash]/", $separator, $text );
    $text = preg_replace("/[^a-zA-Z0-9\-]!=./", "", $text );

    $text = strtolower($text);

    $text = ($isUTF8) ? utf8_encode($text) : $text;

    return uniqid(time()) ."__". $text;
}

/*function extract_var(&$TheStr, $sLeft, $sRight){
    $out = array();
    do{
        $pleft = strpos($TheStr, $sLeft, 0);
        if ($pleft !== false){
            $pright = strpos($TheStr, $sRight, $pleft + strlen($sLeft));
            If ($pright !== false) {
                $var = (substr($TheStr, $pleft + strlen($sLeft), ($pright - ($pleft + strlen($sLeft)))));
                $TheStr = str_replace('{'.$var.'}', '', $TheStr);
                $out[] = $var;
            }
        }
    }while($pleft !== false);

    return $out;
}*/

function extract_var(&$str, $left, $right, $del=false){
    $out = array();
    $pos2=0;
    $tmpstr=$str;

    while( ($pos1=strpos($str, $left, $pos2))!== false ){
        if( ($pos2=strpos($str, $right, $pos1))!== false ){
            $val = trim(substr($str,  $pos1+strlen($left), $pos2-$pos1-strlen($left)));
            $tag = $left . $val . $right;
            $out[] = array(
                'val' => $val,
                'tag' => $tag
            );
            if( $del ) $tmpstr = str_replace($tag, '', $tmpstr);
        }
    }
    $str = $tmpstr;
    return $out;
}

function set_message($body, $_config=null){
    $config = array();
    $config['nl2br'] = !isset($_config['nl2br']) ? null : $_config['nl2br'];
    $config['default'] = !isset($_config['default']) ? null : $_config['default'];
    $config['data'] = !isset($_config['data']) ? null : $_config['data'];

    $out = '';
    $ci =& get_instance();

    foreach( $body as $line ){
        if( preg_match("/\{.*\}/", $line)!==FALSE ){
            $arrFields = extract_var($line, '{', '}');
            $j=0;
            foreach( $arrFields as $var ){
                //echo $var['val'].'<br>';

                if( is_array($config['data']) && isset($config['data'][$var['val']]) ){
                    $val = $config['data'][$var['val']];
                }else{
                    $val = $ci->input->post($var['val']);
                }

                if( $val!=false ){
                    $j++;
                    if( $config['nl2br']!=null ){
                        if( is_string($config['nl2br']) ) $config['nl2br'] = array($config['nl2br']);
                        if( array_search($var['val'], $config['nl2br'])!==FALSE ) $val = nl2br($val);
                    }
                    $line = str_replace($var['tag'], $val, $line);
                }else{
                    if( is_string($config['default']) ) {
                        $j=1;
                        $line = str_replace($var['tag'], $config['default'], $line);
                    }
                }
            }
            if( $j!=0 ) $out.=$line;
        }
    }
    return $out;
}

function get_object_movie($url, $opt="panel"){
    if( empty($url) ) return '';
    require(APPPATH . 'config/config_movie.php');
    $url.="?fs=1&amp;hl=es_ES&amp;color1=".$config[$opt]['color1']."&amp;color2=".$config[$opt]['color2'];
    return '<object width="'.$config[$opt]['width'].'" height="'.$config[$opt]['height'].'"><param name="movie" value="'.$url.'"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="'.$url.'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="'.$config[$opt]['width'].'" height="'.$config[$opt]['height'].'"></embed></object>';
}
?>
