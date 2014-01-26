<?php
require_once('require.php');
$dir_root = "./";
$name = "mypage";
/* @var $page Page */
$page = new Page($name, $dir_root);
DB::connectDb();

$login = checkLogin($me, true);





echo <<<EOF
<!DOCTYPE html>
<html lang="ja">
{$page->head()}
<body>
  <div id="wrapper">
    <div class="container">
      <div class="raw">
        {$page->breadcrumb(array('TOP' => './', 'マイページ' => ACTIVE))}
      </div>
    </div>
  </div>

</body>
EOF;

?>


<script>
$(function() {            //Startup Action
    switchSearch('a');
    switchNote('s');
    // -- js Tree running -- //
//    $("#contents-tree").jstree();
//    $("ins").click();
});

    </script>

<body>
  <?php htmlHeaderLink($me, $login);?>
  <div id="wrapper">
    <div class="main">
      <div id="column-left">
        <div id="logo">
          <a id="block" href="./"></a>
        </div>
        <div id="profile">
          <div class="top">Profile</div>
          <div class="middle">
            <span class="name">
              <?php echo $me->id_str.":".$me->name;?>
            </span>
            <br>
            <span class="nickname">
              <?php echo $me->nick_name;?>
            </span>
            <span class="point">
              <?php echo $me->point."PT";?>
            </span>
            <img class="icon" src="./img/user-icon/12fi091.gif" />
          </div>
          <div class="bottom"></div>
        </div>
        <div id="calendar">
          <div class="top">Calendar</div>
          <div class="middle"></div>
          <div class="bottom"></div>
        </div>

        <div id="contents-list">
          <div class="top">Knot and Class</div>
          <div class="middle">
            <div id="contents-tree">
              <?php htmlContentsTree($me);?>
            </div>
          </div>
          <div class="bottom"></div>
        </div>
      </div>

      <div id="bar-search">
        <form>
          <!-- sb- search button -->
          <input class="sb-a" type="button" onclick="switchSearch('a')" title="全検索" value="All" />
          <div class="sb-a" title="全検索">All</div>
          <input class="sb-s" type="button" onclick="switchSearch('s')" title="生徒検索" value="生徒" />
          <div class="sb-s" title="生徒検索">生徒</div>
          <input class="sb-l" type="button" onclick="switchSearch('l')" title="講義検索" value="講義" />
          <div class="sb-l" title="講義検索">講義</div>
          <input class="sb-k" type="button" onclick="switchSearch('k')" title="ノット検索" value="ノット" />
          <div class="sb-k" title="ノット検索">ノット</div>
          <br>
          <input type="text" />
          <input id="search-type" type="hidden" />
          <input class="submit" type="submit" value="" />
        </form>
      </div>

      <div id="column-center">
        <div id="notification-box" class="box-single">
          <div class="top">新着情報</div>
          <div class="middle">
            <!-- nw notification switch-->
            <div class='switchs-line'>
              <form>
                <input type="button" class="nw-s" onclick="switchNote('s')" value="ユーザー" />
                <input type="button" class="nw-c" onclick="switchNote('c')" value="講義" />
                <input type="button" class="nw-k" onclick="switchNote('k')" value="ノット" />
              </form>
            </div>
            <div class="notification-box-s">
              <div class="c">システム</div>
              <div class="b">更新通知</div>
              <div class="a">重要なお知らせ</div>
            </div>
            <div class="notification-box-c">
              <div class="c">クラス</div>
              <div class="b">更新通知</div>
              <div class="a">重要なお知らせ</div>
            </div>
            <div class="notification-box-k">
              <div class="c">ノット</div>
              <div class="b">更新通知</div>
              <div class="a">重要なお知らせ</div>
            </div>
          </div>
          <div class="bottom"></div>
        </div>

        <div class="box-single">
          <div id="none">
            <div class="top"></div>
            <div class="middle"></div>
            <div class="bottom"></div>
          </div>
        </div>
      </div>
      <div id="column-right">
        <div class="column-cms">
          <div class="cm-a"></div>
          <div class="cm-u"></div>
          <div class="cm-u"></div>
          <div class="cm-l"></div>
          <div class="cm-l"></div>
        </div>
      </div>
    </div>
    <!--       <div id="cm-special">scm</div> -->
  </div>
  <?php htmlFooter();?>
</body>
</html>
