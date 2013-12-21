<?php

require_once('require.php');
setupEncodeing();
session_start();
$dir_root = "./";
$page_name = "regst_lecture";
connectDb();

$me = get_me();

$login = checkLogin($me, ture);
//print_r($login);
//print_r($me);



if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $token=setToken();
} else {

    //make lecture chacker
    $token     = checkToken();
    $makeclass = $_POST['make-class'];
    $name      = $_POST['name'];
    $detail    = $_POST['detail'];

    if($name == ''){
        $err['name'] = '入力されていません';
    }
    if($detail == ''){
        $err['detail'] = '入力されていません';
    }

    if($makeclass ==1) { //make class checker

        $teacher     = $_POST['teacher'];
        $room        = $_POST['room'];
        $limit       = $_POST['limit'];
        $textbook    = $_POST['textbook'];
        $measurement = $_POST['measurement'];
        $prepare     = $_POST['prepare'];
        $credit      = $_POST['credit'];
        $term_y      = $_POST['term_y'];
        $term_t      = $_POST['term_t'];


        $schedules = array();
        if($scheduleP = $_POST["schedule"]) {
            for($i = 1; $i <= 7; $i++ ) {
                for ($j = 1; $j <= 6; $j++) {
                    if($scheduleP[$j][$i])
                        $schedules[count($schedules)] = $j * 10 + $i;
                }
            }
        }

        if($teacher == ''){
            $err['teacher'] = '入力されていません';
        }
        if(!preg_match("/^\d+$/", $room)) {
            $err['room'] = '入力値が不正です';
        }
        if(!preg_match("/^\d$/", $credit)) {
            $err['credit'] = '入力値が不正です';
        }
        if(!preg_match("/\d{2}/", $term_y) || !preg_match("/\d{2}/", $term_t)) {
            $err['term'] = '入力値が不正です'."y: $term_y"." t: $term_t";
        }
        $c = count($schedules);
        if($c == 0 || $c > 4) {
            $err['schedule'] = "コマ数が不正です";
        }
    }

    if (empty($err)) {
        $parameter = array(
                'name' => $name,
                'detail' => $detail,
        );
        $resultLecture = registLecture($parameter);
        if($resultLecture !== 0 && empty($resultLecture)){
            jump("regist_lecture", array("er" => E1LECTURE.E2CREATE."08"));
        } elseif ($makeclass == 0) {
            jump("lecture", array("id" => $resultLecture));
        }

        $term = $term_y . $term_t;

        $parameter = array(
                'id_root'     => $id,
                'teacher'     => $teacher,
                'room'        => $room,
                'schedules'   => implode(",", $schedules),
                'limit'       => $limit,
                'term'        => $term,
                'textbook'    => $textbook,
                'measurement' => $measurement,
                'prepare'     => $prepare,
                'credit'      => $credit,
        );

        $resultClass = registClass($parameter);
        if($resultClass !== 0 && empty($resultClass)){
            jump("lecture", array("id" => $resultLecture, 'er' => E1CLASS.E2CREATE."11"));
        }else{
            jump("class", array("id" => $resultClass));
        }
    }
}


// checking

?>



<?php htmlHeader($me, $dir_root, $page_name);?>
<script type="text/javascript">



</script>
</head>
<body>
  <?php htmlHeaderLink($me, $login);?>
  <div id="wrapper">
    <div class="main">
      <!-- コンテンツスペース -->

      <form class="regist" action="" method="POST">
        <div id="lecture-form-div">
          <!-- lecture forms -->
          <div class="box-simple">
            <div class="top">講義登録フォーム</div>
            <div class="middle">

              <p>
                講義名：
                <input type="text" name="name" value="<?php echo h($name);?>">
                <?php echo h($err['name']); ?>
              </p>
              目的概要：
              <textarea name="detail">
            <?php echo h($detail);?>
            </textarea>
              <?php echo h($err['detail']); ?>
            </div>
            <div class="bottom"></div>
          </div>
        </div>
        <!-- lecture forms end -->

        <input class="switch" type="button" name="make-class-switch" onclick="switchMakeClass()" value="クラスも同時に作成する" />
        <div id="class-form-div" style="display: none;">
          <!-- class form -->
          <div class="box-simple">
            <div class="top">クラス登録フォーム</div>
            <div class="middle">
              <p>
                講師名 ：
                <input type="text" name="teacher" value="<?php echo h($teacher);?>">
                <?php echo h($err['teacher']); ?>
              </p>
              <p>
                開講年度学期： <select name="term_y">
                  <option value="13" <?php if($term_y=='13')echo "selected";?>>2013</option>
                  <option value="14" <?php if($term_y=='14')echo "selected";?>>2014</option>
                </select> <select name="term_t">
                  <option value="10" <?php if($term_t=='10')echo "selected";?>>前期</option>
                  <option value="20" <?php if($term_t=='20')echo "selected";?>>後期</option>
                  <option value="11" <?php if($term_t=='11')echo "selected";?>>前前期</option>
                  <option value="12" <?php if($term_t=='12')echo "selected";?>>前後期</option>
                  <option value="21" <?php if($term_t=='21')echo "selected";?>>後前期</option>
                  <option value="22" <?php if($term_t=='22')echo "selected";?>>後後期</option>
                </select>
                <?php echo h($err['term']); ?>
              </p>
              <p>時間割 :</p>
              <?php htmlScheduleCheckBoxs("scheduleBox", $schedules); ?>
              <p>
                <?php echo h($err['schedule']);print_r($schedules); ?>
              </p>
              <p>
                講義室：
                <input type="text" name="room" value="<?php echo h($room);?>">
                <?php echo h($err['room']); ?>
              </p>

              <p>
                履修条件 ：
                <input type="text" name="limit" value="<?php echo h($limit);?>">
                <?php echo h($err['limit']); ?>
              </p>
              <p>
                教科書名 ：
                <input type="text" name="textbook" value="<?php echo h($textbook);?>">
                <?php echo h($err['textbook']); ?>
              </p>
              <p>
                評価方法 ：
                <input type="text" name="measurement" value="<?php echo h($measurement);?>">
                <?php echo h($err['measurement']); ?>
              </p>
              <p>
                準備学習 ：
                <input type="text" name="prepare" value="<?php echo h($prepare);?>">
                <?php echo h($err['prepare']); ?>
              </p>
              <p>
                単位数：
                <input type="text" name="credit" value="<?php echo h($credit);?>">
                <?php echo h($err['credit']); ?>
              </p>
              <div class="bottom"></div>
            </div>
          </div>
        </div>
        <!--  class form end -->
        <p>
          <input type="hidden" name="make-class" value="0" />
          <input type="hidden" name="token" value="<?php echo h($token);?>">
          <input type="submit" value="登録！">
          <a href="index.php">戻る</a>
        </p>
      </form>


    </div>
    <div class="paste-box">
      <textarea id="crawl">

    </textarea>
      <input type="button" id="crawlButton">
    </div>
  </div>
  <?php htmlFooter();?>
</body>
</html>



