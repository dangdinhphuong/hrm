<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Title -->
    <title>EDUCA HRM</title>

    <!-- Thẻ favicon -->
    <link rel="icon" href="{{ asset('assets/images/logo/faviconV2.ico') }}" type="image/x-icon">

    <!-- Thẻ meta mô tả -->
    <meta name="description" content="Hệ thống quản lý nhân sự thuộc công ty Educa">

    <!-- Open Graph (OG) tags -->
    <meta property="og:title" content="EDUCA HRM">
    <meta property="og:description" content="Hệ thống quản lý nhân sự thuộc công ty Educa">
    <meta property="og:image" content="{{ Vite::asset('resources/images/logo/edupia.png') }}">
    <meta property="og:url" content="{{ config('app.url') }}">

    <!-- Bao gồm CSS và JS từ Vite -->
    @vite(['resources/js/app.js','resources/css/app.css','resources/css/variable.css'])
</head>
<body>
<div id="app"></div>
</body>
</html>
