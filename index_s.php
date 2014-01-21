<?php
?>
<!DOCTYPE html>
<html lang="ja">
<meta charset="utf-8" />
<head>
<title>DENPA</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]> <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script> <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script> <![endif]-->
</head>
<body>

  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="navbar-header">
      <button class="navbar-toggle" data-toggle="collapse" data-target=".target">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="" class="navbar-brand">TestPage</a>
    </div>
    <div class="collapse navbar-collapse target">
      <ul class="nav navbar-nav">
        <li class="active">
          <a href="">Link1</a>
        </li>
        <li>
          <a href="">Link2</a>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li>
          <a href="">Link4</a>
        </li>
      </ul>
    </div>
  </nav>



  <div id="wrapper" style="padding: 100px 0px;">
    <div class="container" style="padding: 20px 0">
      <ul class="nav nav-tabs" style="margin-bottom: 20px;">
        <li class="active">
          <a href="#tab-table" data-toggle="tab">Table</a>
        </li>
        <li>
          <a href="#tab-form" data-toggle="tab">Form</a>
        </li>
        <li>
          <a href="#tab-icon_btn" data-toggle="tab">Icon/Button</a>
        </li>
        <li>
          <a href="#tab-progress" data-toggle="tab">ProgressBar</a>
        </li>
        <li>
          <a href="#tab-list" data-toggle="tab">List</a>
        </li>
        <li>
          <a href="#tab-notice" data-toggle="tab">Notice</a>
        </li>
        <li>
          <a href="#tab-tooltip" data-toggle="tab">Tooltip</a>
        </li>
      </ul>

      <div class="tab-content">
        <div class="tab-pane active" id="tab-table">
          <div class="container" style="padding: 20px 0">
            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Score</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="warning">@taguchi</td>
                  <td>100</td>
                </tr>
                <tr>
                  <td>@taguchi</td>
                  <td>100</td>
                </tr>
                <tr>
                  <td>@taguchi</td>
                  <td>100</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="tab-pane" id="tab-form">
          <div class="container">
            <form class="form-horizontal" action="">
              <div class="form-group">
                <label for="email" class="control-label col-sm-2">Email</label>
                <div class="col-sm-4">
                  <input type="text" id="email" class="form-control:text" placeholder="email" />
                  <!-- span class="help-block">Error!</span-->
                </div>
              </div>
              <div class="form-group">
                <label for="email" class="control-label col-sm-2">Email</label>
                <div class="col-sm-4">
                  <input type="text" id="email" class="form-control:text" placeholder="email" />
                  <!-- span class="help-block">Error!</span-->
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-4">
                  <input type="text" class="btn btn-primary has-error" value="submit" />
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="tab-pane" id="tab-icon_btn">
          <div class="container" style="padding: 20px 0">
            <p>
              <i class="glyphicon glyphicon-book"></i>Book
            </p>
            <span class="glyphicon glyphicon-search"></span>
            <button class="btn btn-primary">
              <i class="glyphicon glyphicon-book"></i>Push
            </button>
            <div class="btn-group">
              <button class="btn btn-primary"></button>
              <button class="btn btn-primary"></button>
              <button class="btn btn-primary"></button>
            </div>
          </div>

          <div class="container" style="padding: 20px 0">
            <div class="btn-group">
              <button class="btn btn-primary">Action</button>
              <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">
                <li>
                  <a href="">Action</a>
                </li>
                <li>
                  <a href="">Bction</a>
                </li>
                <li class="divider"></li>
                <li>
                  <a href="">Cction</a>
                </li>
              </ul>
            </div>
            <div class="container" style="padding: 20px 0">
              <a href="#myModal" data-toggle="modal" class="btn btn-primary">Show me!</a>
              <div class="modal fade" id="myModal">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-titlte">My Modal</h4>
                    </div>
                    <div class="modal-body">こんにちは！</div>
                    <div class="modal-footer">
                      <button class="btn btn-primary" data-dismiss="modal">OK!</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="tab-pane" id="tab-progress">
          <div class="container" style="padding: 20px 0">
            <div class="progress">
              <div class="progress-bar progress-bar-primary" style="width: 60%"></div>
            </div>
            <div class="progress progress-striped">
              <div class="progress-bar progress-bar-info" style="width: 60%"></div>
            </div>
            <div class="progress progress-striped active">
              <div class="progress-bar progress-bar-info" style="width: 60%"></div>
            </div>
            <div class="progress progress-striped active">
              <div class="progress-bar progress-bar-primary" style="width: 60%"></div>
              <div class="progress-bar progress-bar-warning" style="width: 10%"></div>
              <div class="progress-bar progress-bar-info" style="width: 30%"></div>
            </div>
          </div>
        </div>

        <div class="tab-pane" id="tab-list">
          <div class="container" style="padding: 20px 0">
            <ul class="breadcrumb">
              <li>
                <a href="">Home</a>
              </li>
              <li>
                <a href="">Users</a>
              </li>
              <li>
                <a href="" class="active">@taguchi</a>
              </li>
            </ul>
            <ul class="pagination">
              <li>
                <a href="">&laquo;</a>
              </li>
              <li>
                <a href="">1</a>
              </li>
              <li>
                <a href="">2</a>
              </li>
              <li>
                <a href="">3</a>
              </li>
              <li>
                <a href="">4</a>
              </li>
              <li>
                <a href="">&raquo;</a>
              </li>
            </ul>
            <ul class="pager">
              <li class="previous">
                <a href="">前へ</a>
              </li>
              <li class="next">
                <a href="">次へ</a>
              </li>
            </ul>
          </div>
        </div>

        <div class="tab-pane" id="tab-notice">
          <div class="container" style="padding: 20px 0">
            <p>
              Product A
              <span class="label label-primary">New!</span>
            </p>
            <p>
              Inbox
              <span class="badge">10001</span>
            </p>
            <p>
              Inbox
              <span class="badge"></span>
            </p>
            <div class="alert alert-info">
              <button class="close" data-dismiss="alert">&times;</button>
              お知らせ
            </div>
            <div class="panel panel-primary">
              <div class="panel-heading">お知らせ！</div>
              <div class="panel-body">こんにちは！</div>
            </div>
          </div>
        </div>

        <div class="tab-pane" id="tab-tooltip">
          <div class="container" style="padding: 20px 0">
            <p>
              <a href="#" data-toggle="tooltip" title="説明">this</a> and <a href="#" title="説明" data-content="更に説明" data-toggle="popover">that</a>.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://code.jquery.com/jquery.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="lib/bootstrap/js/bootstrap.min.js"></script>

  <script>
  $(function () {
	  $("[data-toggle=tooltip]").tooltip({
		  placement: 'bottom'
	  });
	  $("[data-toggle=popover]").popover();
  });
  </script>

</body>
</html>
