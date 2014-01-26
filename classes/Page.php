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

	public function top () {

	}

	public function header () {
		$login_text = <<< EOF

EOF;
		if ($this->isLogin) $login_text = <<<EOF
      <ul class="nav navbar-nav navbar-right">
        <li>
          <a href="my.php">{$me->name}</a>
        </li>
        <li>
          <a href="logout.php">ログアウト</a>
        </li>
      </ul>
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
      {$login_text}
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