<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="stripe-public-key" content="{{ trim(config('services.stripe.key')) }}">
    <meta name="app-url" content="{{ trim(config('app.url'), '/') }}">
    
    <title>SAAS</title>
    <meta content="SAAS" name="description" />
    
    <link rel="stylesheet" href="{{ mix('/res/css/app.css') }}">
</head>

<body class="">
  <div id="app"></div>
  
  <script type="text/javascript" src="{{ mix('/res/js/app.js') }}"></script>
</body>
</html>