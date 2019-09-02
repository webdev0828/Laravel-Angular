<!DOCTYPE html>
<html>
    <head>
        <!-- Title -->
        <title>STM | Profile</title>
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <meta charset="UTF-8">
        <!-- Styles -->
        <link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700' rel='stylesheet' type='text/css'>

        {!! HTML::style('css/all.css') !!}
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
	<body>
        @yield('content')


        {!! HTML::script('js/all.js') !!}
        {!! HTML::script('js/cropper/cropper.min.js') !!}
        {!! HTML::script('js/cropper/main.js') !!}
        <script type="text/javascript">
            $(document).on('change',  "#avatarInput", function(){
                readURL(this, '#photoAvatar');
            }).on('change',  "#form #inputCover", function(){
                readURL(this, '.photoCover');            
            });
            function readURL(input, destination) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $(destination).attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }
    </script>
    </body>
</html>
