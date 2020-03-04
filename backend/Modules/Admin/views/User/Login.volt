<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <link rel="shortcut icon" href="{{ url('/admin/images/panel.ico') }}" type="image/x-icon">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Control Panel | Авторизация </title>
    {{ assets.outputCss() }}
    {{ assets.outputCss('lastCss') }}
</head>
<body class="login">
<div>
    {{ partial('forms/login') }}
</div>
</body>
</html>