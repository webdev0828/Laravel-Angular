<!DOCTYPE html>
<html>
    <head>
        <!-- Title -->
        <title>STM | Login - Sign in</title>
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <!-- Styles -->
        <link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700' rel='stylesheet' type='text/css'>

        {!! HTML::style('css/artist.css') !!}
        
        <script type="text/javascript">
            var base_url = '{!! URL::to("/") !!}';
        </script>
        
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
    </head>
	<body class="page-forgot" ng-app="artistApp" ng-controller="AuthController">
   
   	@yield('content')
    
    {!! HTML::script('js/artist.js') !!}
    <!-- Javascripts -->
    {!! HTML::script('js/angular/angular.js') !!}
    {!! HTML::script('js/angular/angular-messages.min.js') !!}
    {!! HTML::script('js/angular/controllers/AuthController.js') !!}
    {!! HTML::script('js/angular/checklist-model.js') !!}
    {!! HTML::script('js/angular/angular-messages.min.js') !!}
    {!! HTML::script('js/angular/ui-bootstrap-tpls-0.6.0.js') !!}
    {!! HTML::script('js/angular/directive/directives.js') !!}
    {!! HTML::script('js/angular/services/service.js') !!}
    {!! HTML::script('js/angular/model/app.js') !!}
    </body>
</html>
