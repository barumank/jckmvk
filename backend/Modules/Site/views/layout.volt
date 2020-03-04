<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{ assets.outputCss() }}
</head>
<body>
{{ content() }}
{{ assets.outputJs() }}
</body>
</html>
