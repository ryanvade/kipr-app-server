<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style media="screen">
    .header-image {
        background-image: url("/images/2017-gcer.png");
        background-position: center center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: contain;
        /*background-color: #999;*/
      }
    </style>
</head>
<body>
  <section class="hero is-fullheight header-image">
    <div class="hero-head">
      @include('layouts.navigation')
    </div>

    <div class="hero-body">
    </div>

    <div class="hero-foot">
    </div>
  </section>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
