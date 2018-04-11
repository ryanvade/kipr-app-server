<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="client_id" content="{{ $client_id }}">
    <meta name="client_secret" content="{{ $client_secret }}">

    <title>Admin Panel</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Current User passed in from Controller -->
    <script>
      var user = {!! $user !!};
    </script>
</head>
<body class="adminpanelbody">
  <div id="app">
    <admin></admin>
  </div>
  <div id="notification">
    <notification></notification>
  </div>
    <!-- Scripts -->
    <script src="{{ asset('js/pdf.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/pdf.worker.min.js') }}" charset="utf-8"></script>
    <script src="//{{ Request::getHost() }}:3000/socket.io/socket.io.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/notification.js') }}"></script>
</body>
</html>
