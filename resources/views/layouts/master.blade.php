<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Accounting - @yield('title')</title>

    <!-- Bootstrap -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">

    <!-- Global Styles -->
    <link href="{{ asset('css/global.css') }}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">Accounting</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Transactions <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="/transactions/?account=simple">Simple</a></li>
              <li><a href="/transactions/?account=lan-pass">Credit Card</a></li>
              <li><a href="/transactions/?account=lan-pass">Business</a></li>
              <li><a href="/transactions">Show All</a></li>
              <li role="separator" class="divider"></li>
              <li><a href="/transactions/create/?expense"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span> New Expense</a></li>
              <li><a href="/transactions/create/?transfer"><span class="glyphicon glyphicon-resize-horizontal" aria-hidden="true"></span> New Transfer</a></li>
              <li><a href="/transactions/create/?income"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> New Income</a></li>
              <li role="separator" class="divider"></li>
                <li><a href="/accounts">Accounts</a></li>
                <li><a href="/vendors">Vendors</a></li>
              <li><a href="/categories">Categories</a></li>
            </ul>
          </li>
          <li><a href="/accounts">Accounts</a></li>
          <li><a href="/categories">Categories</a></li>
          <li><a href="/vendors">Vendors</a></li>
          <li><a href="/taxes">Taxes</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
      <div class="page-header">
        <h1>@yield('title') <small>@yield('subtitle')</small></h1>
      </div>

      @include('partials.alerts')

      @yield('content')
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
  </body>
</html>