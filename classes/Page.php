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
		DenpaDB::start();
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
		$html = '';
		//<link type="text/css" rel="stylesheet" href="$root././style.css">
		//    $lessType = $me->get_less;
		$lessType = ((isset($me) && (get_class($me) == 'User')) ?  DEF_LESS : DEF_LESS);
		$title = $this->name . TITLE_TILE;
		$main_less_dir = "main.less";
		$html = <<<EOF
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$title}</title>
<link href='http://fonts.googleapis.com/css?family=Aldrich' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.0.0/build/cssreset/reset-min.css" />

<link rel="stylesheet" charset="UTF-8" href="{$this->dir_root}lib/bootstrap/css/bootstrap.min.css" media="screen" />
<link rel="stylesheet" charset="UTF-8" type="text/css" href="{$this->dir_root}style/{$main_less_dir}" media="screen" />
<link rel="stylesheet/less" charset="UTF-8" type="text/css" href="{$this->dir_root}style/{$lessType}/style.less" media="screen">
<!--script src="{$this->dir_root}lib/bootstrap/less/less-1.3.3.min.js" type="text/javascript"></script-->
<script src="https://code.jquery.com/jquery.js"></script>
<script src="{$this->dir_root}lib/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

{$optionLines}
</head>
EOF;
return $html;
	}

	public function breadcrumb(Array $list) {
		$html = "";
		$html .= '<div id="breadcrumb"><ul class="breadcrumb">';
		foreach ($list as $text => $href)
			$html .= '<li><a href="' . (($href == ACTIVE) ? '#" class=="active' : $href) . '">' . $text . '</a></li>';
		$html .= '</ul></div>';
		return $html;
	}


	public function navbar($nonActiveLoginParts = false) {
		$html = '';
		$login_text = "";
		if ($this->isLogin) $login_text = <<<EOF
        <li>
          <a href="my.php">{$me->name}</a>
        </li>
        <li>
          <a href="logout.php">ログアウト</a>
        </li>
EOF;
		else
			$login_text = '<li><a href="login.php"'.(($nonActiveLoginParts) ? ' class="active"':"").'>ログイン</a></li>';

		$html = <<<EOF

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
      return $html;
	}

	public function user_box() {
		$html = '';
		if (!$this->isLogin) throw new Exception('user not login');
		$html = <<<EOF

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
            return $html;
	}


	public function footer () {
		$html = '';
		$html = <<<EOF

EOF;
		return $html;
	}

	public function createTag ($tagName, array $attribute, $text) {

	}

	/* --------------------------------------------------------- *
	 *     mypage
	* --------------------------------------------------------- */

	public function mypage_col_left () {
		$html = '';
		$html =<<<EOF
        <div id="col-left">
		  {$this->user_box()}
		</div>
EOF;
		return $html;
	}
	public function mypage_col_right () {
		$html = '';
		$html =<<<EOF
        <div id="col-right">
		</div>
EOF;
		return $html;
	}

	public function mypage_row_attention($param) {
		$html = '';
		$html =<<<EOF
          <div class="row-listbox row-my" id="row-attention">
            <div class="panel panel-primary">
              <div class="panel-heading">お知らせ</div>
              <div class="panel-body">
                <ul>
                  <li>通知</li>
                  <li>通知</li>
                  <li>通知</li>
                  <li>通知</li>
                </ul>
              </div>
            </div>
EOF;
		return $html;
	}

}