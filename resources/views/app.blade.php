<!DOCTYPE html>
<html>
  <head>

      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

      {{-- Ziggy --}}
      @routes

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/js/app.js'])
    @inertiaHead
  </head>
  <body>
    @inertia
  </body>
</html>


