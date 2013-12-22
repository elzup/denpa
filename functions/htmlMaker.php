<?php


/* --------------------------------------------------------- *
 *     html builder functions
* --------------------------------------------------------- */


function htmlRadio($element){
    echo $rad=<<<rad
				<div class="radio-box" id="radio-$element">
					<input type="button" id="radio1" onclick="switchRadio('$element', 1)">
					<img id="radio-img1" src="./material/radio-on-r.gif">
					<input type="button" id="radio2" onclick="switchRadio('$element', 2)">
					<img id="radio-img2" src="./material/radio-on-g.gif">
					<input type="button" id="radio3" onclick="switchRadio('$element', 3)">
					<img id="radio-img3" src="./material/radio-on-b.gif">
					<input type="hidden" name="$element-private" value="">
				</div>
rad;
}

function htmlCalendarPulldown($yt = 2010, $mt = 1, $dt = 1){
    echo '<select name="year" id="year">';
    for ($y = 2010; $y <= 2013; $y++)
        echo '\t<option value="'.$y.'"'.(($y == $yt) ? 'selected="selected"':'').'>'.$y.'</option>';
    echo '</select><label for="year">年</label>\n';
    echo '<select name="month" id="month">\n';
    for ($m = 1; $m <= 12; $m++)
        echo '\t<option value="'.sprintf('%02d', $m).'"'.(($m == $mt)? ' selected="selected"': '').'>'.$m.'</option>';
    echo '</select><label for="month">月</label>\n';
    echo '<select name="day" id="day">\n';
    for ($d = 1; $d <= 31; $d++)
        echo '\t<option value="'.sprintf('%02d', $d).'"'.(($d == $dt)? ' selected="selected"':"").'>'.$d.'</option>';
    echo $e =<<<EOF
</select><label for="day">日</label>
</p>
<p><input type="submit" /></p>
EOF;
}

function htmlPrefecturesPulldwon($selected){
    if(scope($selecter, 1, 47))$selected=1;
    $prefLib=prefecturesLib();
    for($i=1;$i<48;$i++){
        $tag="<option value=\"$i\"";
        if($i==$selected)$tag.=" selected";
        $tag.=">$prefLib[$i]</option>\n";
        echo $tag;
    }
}

function prefecturesLib(){
    return array('null', '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県', '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県', '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県', '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県','和歌山県','鳥取県','島根県','岡山県','広島県','山口県','徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長脇件','熊本県','大分県','宮崎県','鹿児島県','沖縄県');
}


function htmlScheduleCheckBoxs($tableId = null, $checkeds = array()) {
    if(!empty($tableId))$tableId = "id=\"{$tableId}\" ";
    $html=<<<HSCB
        <table {$tableId}border=4 width=250 align=center>
          <caption>【時間割】</caption>
          <tr>
            <th>限＼曜</th>
            <th>月</th>
            <th>火</th>
            <th>水</th>
            <th>木</th>
            <th>金</th>
            <th>土</th>
          </tr>
HSCB;
    for($i = 1; $i <= 7; $i++) {
        $html .= "<tr>\n";
        $html .= "<td>$i</td>\n";
        for($j = 1; $j <=6; $j++) {
            $checked= "";
            if(!empty($checkeds) and in_array($j * 10 + $i, $checkeds))$checked = "checked=\"checked\" ";
            $html .= "<td><input type=\"checkbox\" name=\"schedule[$j][$i]\" {$checked}value=\"1\"></td>\n";
        }
        $html .= "<tr>\n";
    }
    $html .= "</table>\n";

    echo $html;
}


/* --------------------------------------------------------- *
 *     Main Contents Functions
 * --------------------------------------------------------- */

//include libs
function htmlHeader($me, $root, $title, $message = null){
    //<link type="text/css" rel="stylesheet" href="$root././style.css">
//    $lessType = $me->get_less;
    $lessType = ((isset($me) && get_class($me) )== 'User' ?  DEF_LESS : DEF_LESS);
    echo $head =<<<head
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>$title - DENPA</title>
<link href='http://fonts.googleapis.com/css?family=Aldrich' rel='stylesheet' type='text/css'>

<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.0.0/build/cssreset/reset-min.css" />
<link rel="stylesheet" charset="UTF-8" href="{$root}./lib/bootstrap/css/bootstrap.min.css" media="screen" />

<!--link rel="stylesheet/less" type="text/css" charset="UTF-8" href="{$root}/style/{$lessType}/style.less" media="screen" -->
<script src="{$root}./lib/less-1.3.3.min.js" type="text/javascript"></script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<script src="{$root}./lib/jquery.jstree.js"></script>
<script src="{$root}./lib/jquery.waypoints.min.js"></script>
<script src="{$root}./lib/jquery.waypoints-sticky.min.js"></script>
<script src="{$root}./lib/footerFixed.js"></script>
<script src="{$root}./js/header_script.js" charset="UTF-8" type="text/javascript"></script>

<script src="{$root}./lib/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>


</head>
head;
    htmlScripts($message);
}

//<script src="$root./lib/jquery-1.9.0.js"></script>

function htmlScripts($message = null) {


    // ----------------- error box action ----------------- //
    $postedError   = $_GET['err'];
    $postedMessage = $_GET['mes'];
    if(!empty($postedError) OR !empty($postedMessage) OR !empty($message)) {
        $html = "<div id=\"message-box\">";
        if(!empty($postedError)) {
            $mes  = convertErrorToData($postedError);
            $text = "Error-$postedError :{$mes[1]} , {$mes[2]}, {$mes[3]}";
            $html .= "<span class=\"message\">{$text}</span>";
        }
        if(!empty($postedMessage)) {
            $mes  = convertMessageToData($postedMessage);
            $text = "Succsess:{$mes[1]}";
            $html = "<span class=\"message\">{$text}</span>";
        }
        $html .= "<input type=\"button\" id=\"closeMessageBox\" onclick=\"closeMessageBox()\" class=\"switch\" title=\"close\" value=\"×\"/></div>";
        echo $scripts =<<<scripts
<script type="text/javascript">
	$(function() {
		var wrapper = $("div#wrapper");
        wrapper.prepend('{$html}')
	});
</script>
scripts;
        // ----------------- error box action end ----------------- //
    }
}

function htmlHeaderLink($me, $login=false){
    $mypage_link = "";
    $login_link = "";
    $tail = TAIL;
    if(!empty($login)){
//        $mypage_link = "<span class=\"string\"><a href=\"".SITE_URL."my"."\">".$me->nick_name."</a></span>";
//        $login_link = "<span class=\"string\"><a href=\"logout\">Logout</a></span>";
    echo $header =<<<header
<div id="header" class="sticky">
    <span><a id="header-logo" href="./">DENPA</a></span>
    <div id="header-menu">
    	<div title="マイページ" id="menu-home"><a href="my{$tail}">link</a></div>
    	<div title="カレンダー" id="menu-calender"></div>
    	<div title="作成" id="menu-create"></div>
    	<div title="設定" id="menu-setting"></div>
    	<div title="ログアウト" id="menu-logout"><a href="logout{$tail}">link</a></div>
    </div>
</div>
header;
    }
    else{
//        $login_link = "<span class=\"string\"><a href=\"login\">Login</a></span>";
    echo $header =<<<header2
<div id="header" class="sticky">
    <span><a id="header-logo" href="./">DENPA</a></span>
    <div id="header-menu" style="width:50px">
    	<div title="ログイン" id="menu-login"><a href="login{$tail}">link</a></div>
    </div>
</div>
header2;
    }

}


function htmlFooter(){
    $url = SITE_URL."js/";
    echo $footer =<<<footer

	<div id="footer">
		<ul id="footer-texts">
			<li>このサイトについて</li>
			<li>問い合わせ</li>
			<li>違反の法則</li>
		</ul>
	</div>

	<iframe src="" frameborder="0" id="php_window1" name="hidden_action1"></iframe>
	<iframe src="" frameborder="0" id="php_window2" name="hidden_action2"></iframe>
	<iframe src="" frameborder="0" id="php_window3" name="hidden_action3"></iframe>
	<script src="{$url}footer_script.js" charset="UTF-8" type="text/javascript"></script>
footer;
}


function htmlCategoryPulldown($category, $name= "category", $selected = null) {
    echo $selected;
    $html = "<select name=\"{$name}\" id=\"category_01\">";
    foreach($category as $key1 => $value1) {
        if(empty($value1->code)) {
            $html .= htmlTagOption($value1->id, $key1, ($value1->id == $selected));
        } else {
            $html .= "<optgroup label=\"{$key1}\">\n";
            $id_c1 = "";
            foreach($value1 as $key2 => $value2) {
                if($key2 == 'code')$id_c1 = $value2;
                elseif(empty($value2->code)) {
                    $html .= htmlTagOption($value2->id, $key2, ($value2->id == $selected));
                } else {
                    //                    $html .= "<optgroup label=\"{$key2}\">\n";
                    $html .= htmlTagOption("", $key2, false, " disabled");
                    $id_c2 = "";
                    foreach($value2 as $key3 => $value3) {
                        if($key3 == 'code')$id_c2 = $value3;
                        else{
                            $html .= htmlTagOption($value3->id, h(" -").$key3, ($value3->id == $selected));
                        }
                    }
                }
            }
            $html .= "</optgroup>\n";
        }
    }
    $html .= "</select>";
    //    echo h($html);
    echo $html;
}


function htmlContentsTree(User $user) {
    $user->set_knots();
    $html = "<ul><li><a href=\"#\">ノット</a><ul class=\"cal_tree_li\">";
    foreach($user->knots as $k) {
        $html .= "<li><a href=\"knot{$tail}?id={$k->id}\">{$k->name}</a></li>";
    }
    $html .= "</ul></li><li><a href=\"#\">クラス</a><ul class=\"cal_tree_li\">";

    $cal = $user->get_calendars();
    $cal = $cal->tables[$user->term];

    if(!empty($cal)) {
        foreach($cal as $key => $day_cal) {
            $html .= "<li>".convertDayToStr($key)."<ul>";
            foreach($day_cal as $c) {
                $html .= "<li><a href=\"class?id={$c->id}\">{$c->lecture->name}</a></li>";
            }
            $html .= "</ul></li>";
        }
   }

    $html .= "</ul></li></ul>";
    echo $html;
}

function htmlTagOption($value, $html, $is_selected = false, $addtype = "") {
    $addtype .= ($is_selected) ? " selected" : "";
    $tag = "<option value=\"{$value}\" {$addtype}>{$html}</option>";
    return $tag;
}

function htmlFormPsheet($values, $name_post = 'psheet', $id = 'form_psheet', $num = 8) {
    for($i = 1; $i <= $num; $i++)
        $html .= "<input type=\"text\" name=\"{$name_post}{$i}\" value=\"{$values[$i]}\" />";
    echo $html;
}

function htmlClassesBox($classes) {
    $html = "";
    if(empty($classes)) {
        $html = "クラスが登録されていません";
    } else {
        foreach ($classes as $c) {

        }
    }
}

?>