<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Control Panel</title>
    <link rel="shortcut icon" href="{{ url('/admin/images/panel.ico') }}" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{ assets.outputCss() }}
    {{ assets.outputCss('lastCss') }}
    {{ assets.outputJs() }}
    <style>
        .ymaps-2-1-55-ground-pane {
            filter: hue-rotate(155deg);
        }
    </style>
</head>
{% set sidebarViewType = cookies.get('sidebar').getValue('int',0) %}
<body class="{{ sidebarViewType ==0 ? 'nav-md': 'nav-sm'}}">
<div class="container body">
    <div class="main_container">

        {{ partial('sidebar') }}

        <!-- top navigation -->
        {{ partial('navigation') }}
        <!-- /top navigation -->
        <!-- page content -->
        <div class="right_col" role="main" style="min-height: calc(100vh - 54px)">
            {{ content() }}
        </div>
        <!-- /page content -->
        <!-- footer content -->
        {{ partial('footer') }}
        <!-- /footer content -->
    </div>
</div>
{{ assets.outputJs('footerJs') }}
</body>
</html>