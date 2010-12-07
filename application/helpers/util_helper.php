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
            $val = substr($str,  $pos1+strlen($left), $pos2-$pos1-strlen($right)+1);
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

?>
