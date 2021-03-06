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

$id = $_GET['id'];
if(empty($id))
    jump(PAGE_ERROR, array('err' => E1REGISTER.E2CREATE.'03'));
$lecture = getLecture($id);
if(!$lecture)
    jump(PAGE_ERROR, array('err' => E1REGISTER.E2CREATE.'04'));



if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // CFRF
    $token=setToken();
} else {
    $token       = checkToken();
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

    if (empty($err)) {
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

        $result = registClass($parameter);
        //        $_SESSION['id_reg']=$id;
        //        exit;
        if($result !== 0 && empty($result)){
            jump("regist_class", array('err' => E1CLASS.E2CREATE."10"));
        }else{
            jump("class?id=".$result);
        }
    }

}


// checking

?>



<?php htmlHeader($me, $dir_root, $page_name);?>
<script type="text/javascript">



function crawlLecture(){

}

</script>
</head>
<body>
  <?php htmlHeaderLink($me, $login);?>
  <div id="wrapper">
    <?php if(!empty($err['box']))echo $err['box'];?>
    <div class="main">
      <!-- コンテンツスペース -->

      <form class="regist" action="" method="POST">
        <div class="class-form-div">
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
        </div>
        <input type="hidden" name="token" value="<?php echo h($token);?>">
        <p>
          <input type="submit" value="登録！">
          <a href="lecture?id=<?=$lecture->id?>">戻る</a>
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



