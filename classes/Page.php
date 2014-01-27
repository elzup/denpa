<?php


class Page {

	public $location;
	public $name;
	public $dir_root;

	/* @var $me User */
	public $me;

	public function isLogin () {
		return empty($this->me);
	}

	public function __construct($name, $dir_root, $isNeedLogin = false) {
		$this->name = $name;
		$this->dir_root = $dir_root;
		$this->setupEncodeing();

		$this->checkLogin();
		if ($isNeedLogin && $this->isLogin()) {
			jump();
		}
	}

	/* --------------------------------------------------------- *
	 *     setup
	* --------------------------------------------------------- */

	public function setupEncodeing($charset = "utf8"){
		header('Content-type:text/html; charset=' . $charset);
		mb_regex_encoding('UTF-8');
		if(isset($_GET['pre'])) echo "<pre>";
		session_start();
	}

	public function checkLogin () {
		$this->me = User::getMe();
	}

	/* --------------------------------------------------------- *
	 *     wrap info methods
	* --------------------------------------------------------- */


	public function getUserImageUrl() {
		$debug_image_url = SITE_URL . "./img/user-icon/12fi091.gif";
		return $debug_image_url;
	}

	/* --------------------------------------------------------- *
	 *     html code methods
	* --------------------------------------------------------- */

	public function head ($optionLines = "") {
		//<link type="text/css" rel="stylesheet" href="$root././style.css">
		//    $lessType = $me->get_less;
		$lessType = ((isset($me) && (get_class($me) == 'User')) ?  DEF_LESS : DEF_LESS);
		$title = $this->name . TITLE_TILE;
		$main_less_dir = "main.less";
		echo <<<EOF
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$title}</title>
<link href='http://fonts.googleapis.com/css?family=Aldrich' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.0.0/build/cssreset/reset-min.css" />
<link rel="stylesheet" charset="UTF-8" href="../lib/bootstrap/css/bootstrap.min.css" media="screen" />
<link rel="stylesheet" charset="UTF-8" type="text/css" media="screen" href="../style/{$main_less_dir}"/>
<link rel="stylesheet/less" charset="UTF-8" type="text/css" href="../style/{$lessType}/style.less" media="screen">
<script src="../lib/less-1.3.3.min.js" type="text/javascript"></script>
<!-- script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery.js"></script>
<!-- script src="../lib/jquery.jstree.js"></script-->
<!--script src="../lib/jquery.waypoints.min.js"></script-->
<!--script src="../lib/jquery.waypoints-sticky.min.js"></script-->
<!--script src="../lib/footerFixed.js"></script-->
<!--script src="../js/header_script.js" charset="UTF-8" type="text/javascript"></script-->
<script src="./lib/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
{$optionLines}
</head>
EOF;
	}

	public function breadcrumb(Array $list) {
		echo '<div id="breadcrumb" class="col-sm-12"><ul class="breadcrumb">';
		foreach ($list as $text => $href)
			echo '<li><a href="' . (($href == ACTIVE) ? '#" class=="active' : $href) . '">' . $text . '</a></li>';
		echo '</ul></div>';
	}


	public function navbar() {
		$login_text = <<< EOF
        <li>
          <a href="login.php">ログイン</a>
        </li>
EOF;
		if ($this->isLogin) $login_text = <<<EOF
        <li>
          <a href="my.php">{$me->name}</a>
        </li>
        <li>
          <a href="logout.php">ログアウト</a>
        </li>
EOF;

		echo <<<EOF

  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="navbar-header">
      <button class="navbar-toggle" data-toggle="collapse" data-target=".target">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="" class="navbar-brand">DENPA</a>
    </div>
    <div class="collapse navbar-collapse target">
      <!-- ul class="nav navbar-nav">
        <li class="active">
          <a href="">Link1</a>
        </li>
        <li>
          <a href="">Link2</a>
        </li>
      </ul-->
      <ul class="nav navbar-nav navbar-right">
      {$login_text}
      </ul>
    </div>
  </nav>

EOF;
	}

	public function user_box() {
		if (!$this->isLogin) throw new Exception('user not login');
		echo <<<EOF

          <div id="user-box-sm" class="panel panel-primary">
            <div class="panel-heading">プロフィール</div>
            <div class="panel-body">
            {$me->id_str}:{$me->name}
            :{$me->nick_name}
            :{$me->point}PT
             <img class="icon" src="" />
            </div>
          </div>
EOF;

	}


	public function footer () {
		echo <<<EOF

EOF;
	}
}