<head>
<!-- Title -->
<title>Sore Thumb Media | Admin Dashboard</title>

<meta content="width=device-width, initial-scale=1" name="viewport"/>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style type="text/css">
	table .table-striped > tr {
		width: 100%;
		display: inline-table;
		}

	table .table-striped{
		 height:300px; 
		}
	table .table-striped tbody{
		  overflow-y: scroll;
		  height: 200px;
		  width: 100%;
		  position: absolute;
		}
</style>
{!! HTML::style('css/all.css') !!}
{!! HTML::style('css/bootstrap-switch.css') !!}

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
