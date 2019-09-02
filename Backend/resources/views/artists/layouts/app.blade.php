<!DOCTYPE html>
<html>

<head>
    <!-- Title -->
    <title>Sore Thumb Media | Artist Dashboard</title>

    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta charset="UTF-8">

    <!-- Styles -->
    {{--
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'> --}}
    <!-- Theme Styles -->
    <style type="text/css">
        .btn-file {
            position: relative;
            overflow: hidden;
        }
        
        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }
    </style>
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

<body class="page-header-fixed" ng-app="artistApp">

    @section('navigation')
        @include('artists.partials.nav') 
    @show 
    @section('breadcrumb') 
        @include('artists.partials.breadcrumb') 
    @show
    <div id="main-wrapper">
        @section('left') 
            @include('artists.partials.left') 
        @show
        <div class="container-fluid" id="crop-avatar">
            @yield('content')
        </div>
    </div>

    @section('footer') 
        @include('artists.partials.footer') 
    @show 
    {!! HTML::script('js/artist.js') !!}
    <!-- Javascripts -->
    

    <script type="text/javascript">
        $(document).on('change', "#avatarInput", function() {
            readURL(this, '#photoAvatar');
        }).on('change', "#form #inputCover", function() {
            readURL(this, '.photoCover');
        });
        function readURL(input, destination) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $(destination).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>