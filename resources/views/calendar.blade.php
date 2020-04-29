<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Calendar</title>
    {{-- STYLES --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  </head>
  <body>
    <div class="c-app-container d-flex align-items-center justify-content-center" id="app">
<events></events>
    </div>
    <script src="{{ asset('js/app.js') }}" defer></script>
  </body>
</html>
