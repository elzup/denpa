<?php

/* --------------------------------------------------------- *
 *     Converter functions
 * --------------------------------------------------------- */


function convertIdToStr($idN, $upper=false){
    $subN=strtoupper(substr($idN, 2,2));
    $subS="";
    switch($subN){
        case "11";$subS="ef";break;
        case "12":$subS="eh";break;
        case "13":$subS="ek";break;
        case "14":$subS="ef";break;
        case "15";$subS="es";break;
        case "16";$subS="ec";break;
        case "17";$subS="ee";break;
        case "18";$subS="em";break;

        case "21";$subS="fa";break;
        case "22";$subS="fi";break;
        case "23";$subS="fr";break;

        case "31";$subS="ne";break;
        case "32";$subS="nm";break;
        case "33";$subS="nc";break;

        default:return $idN;
    }
    if($upper)$subS=strtoupper($subS);
    return substr_replace($idN, $subS, 2, 2);
}


function convertIdToNum($idS){
    $subS=strtoupper(substr($idS, 2,2));
    $subN="";
    switch($subS){
        case "EJ":$subN=11;break;
        case "EH":$subN=12;break;
        case "EK":$subN=13;break;
        case "EF":$subN=14;break;
        case "ES";$subN=15;break;
        case "EC";$subN=16;break;
        case "EE";$subN=17;break;
        case "EM";$subN=18;break;

        case "FA";$subN=21;break;
        case "FI";$subN=22;break;
        case "FR";$subN=23;break;

        case "NE";$subN=31;break;
        case "NM";$subN=32;break;
        case "NC";$subN=33;break;

        default:return $idS;
    }
    return substr_replace($idS, $subN, 2, 2);
}

function convertServiceToString($code) {
    switch($code) {
        case 't':
            return "twitter";
        case 'f':
            return "facebook";
        case 's':
            return 'skype';
        case 'p':
            return 'pixiv';
        case 'l':
            return 'line';
        case 'u':
            return 'u';
        default:
            return false;
    }
}

function scope($data, $min, $max){
    return ($data>=$min && $data<=$max);
}
function isIdChars($str, $sign=false){
    if($sign)return preg_match("/^[a-zA-Z0-9_]+$/", $str);
    return preg_match("/^[a-zA-Z0-9]+$/", $str);
}
function convertCityToStr($city_lastN){
    $lastS='';
    if(!scope($city_lastN, 1, 4))return false;
    switch(city_lastN){
        case 1:$lastS='市';break;
        case 2:$lastS='町';break;
        case 3:$lastS='村';break;
        case 4:$lastS='区';break;
        default: return false;
    }
    return $lastS;
}

function convertStateToNum($state_str) {
    switch($state_str) {
        case 'p':
            return 1;
            break;
        case 'f':
            return 0;
            break;
        case 'b':
            return 2;
            break;
        default:
            break;
    }
    return -1;
}
function toDate($month, $day){
    return $month*100+$day;
}

function convertSchedulesToStr($num) {
    $date = substr($num, 0, 1);
    $time = substr($num, 1, 1);
    $pattern = array("/1/", "/2/", "/3/", "/4/", "/5/", "/6/");
    $replacement = array("月", "火", "水", "木", "金", "土");
    return $result = preg_replace($pattern, $replacement, $date)."曜日".$time."限目";
}

function convertDayToStr($num) {
    $pattern = array("/1/", "/2/", "/3/", "/4/", "/5/", "/6/");
    $replacement = array("月", "火", "水", "木", "金", "土");
    return $result = preg_replace($pattern, $replacement, $num);
}

function convertTermToStr($termC, $isNum = false){
    if($isNum) {
        $pattern = array("/1/", "/2/", "/0/");
        $replacement = array("前", "後", "");
    } else {
        $pattern = array("/a/", "/b/");
        $replacement = array("前", "後");
    }
    $termS = preg_replace($pattern, $replacement, $termC);
    return $termS;
}

function convertMessageToData($code) {
    $e1 = substr($code, 0, 1);
    $e2 = substr($code, 1, 1);
    $text = "";
    switch($e1) {
        case E1REGISTER:
            break;
        case E1CLASS:
            $text .= "クラス";
            break;
        case E1LECTURE:
            $text .= "講義";
            break;
        case E1KNOT:
            $text .= "ノット";
            break;
        default:
            break;
    }
    switch($e2) {
        case E2ATEND:
            $text .= "に参加しました";
            break;
        case E2CREATE:
            $text .= "を作成しました";
            break;
        case E2SETTING:
            $text .= "の設定を変更しました";
            break;
        default:
            break;
    }
    return $text;
}

function convertErrorToData($code) {
    $e1 = substr($code, 0, 1);
    $e2 = substr($code, 1, 1);
    $data = array();
    switch($e1) {
        case E1REGISTER:
            $data[1] = "登録";
            break;
        case E1CLASS:
            $data[1] = "クラス";
            break;
        case E1LECTURE:
            $data[1] = "講義";
            break;
        case E1KNOT:
            $data[1] = "ノット";
            break;
        default:
            break;
    }
    switch($e2) {
        case E2ATEND:
            $data[2] = "参加";
            break;
        case E2CREATE:
            $data[2] = "作成";
            break;
        case E2SETTING:
            $data[2] = "設定";
            break;
        default:
            break;
    }
    if($e3 = substr($code, 2, 2)) {
        switch($e3) {
            case 01:
                $data[3] = "参加済みのノットです";
                break;
            case 02:
                $data[3] = "POST値が不正です";
                break;
            case 03:
                $data[3] = "GET値が不正です";
                break;
            case 04:
                $data[3] = "存在しないIDです";
                break;
            case 05:
                $data[3] = "未参加のノット";
                break;
            case 08:
                $data[3] = "DBエラーです";
                break;
            case 10:
                $data[3] = "登録失敗";
                break;
            case 11:
                $data[3] = "クラス登録のみ失敗";
                break;
            default:
                break;
        }
    }
    return $data;
}

?>