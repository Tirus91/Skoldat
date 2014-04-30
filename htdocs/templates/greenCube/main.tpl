<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{tag:lang /}" lang="{tag:lang /}">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta name="author" content="Tomas Kulhanek" />
        <meta name="description" content="Školdat" />
        <meta name="keywords" content="škola" />
        <meta name="robots" content="noindex,follow">
            <title>{tag:sitename /} :: {tag:title /}</title>
            <link rel="stylesheet" type="text/css" href="{tag:homelink /}/templates/greenCube/styles/style.css" media="screen" />
            <link rel="stylesheet" type="text/css" href="{tag:homelink /}/templates/greenCube/styles/calendar.css"  media="screen" />
            <link rel="stylesheet" type="text/css" href="{tag:homelink /}/templates/greenCube/styles/jquery.css"  media="screen" />
            <script src="{tag:homelink /}/public/javascript/current_time.js" type="text/javascript"></script>
            <script src="{tag:homelink /}/public/javascript/jquery.js" type="text/javascript"></script>
            <script src="{tag:homelink /}/public/javascript/jquery1-8-8-ui.min.js" type="text/javascript"></script>
            <script src="{tag:homelink /}/public/javascript/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
            <script src="{tag:homelink /}/public/javascript/jquery/jquery.js" type="text/javascript"></script>



    </head>

    <body>
        <div id="wrapper">
            <div id="head">
                <h1>{tag:sitename /}</h1><span id="login_text">{tag:login_text /}</span> <span id="curr_time"></span>
            </div>
            <div id="head_bottom">
                <ul class="menu_vertical">
                    {loop:vertical_menu}
                    <li><a href="{tag:vertical_menu[].link /}" title="{tag:vertical_menu[].title /}">{tag:vertical_menu[].name /}</a></li>
                    {/loop:vertical_menu}
                </ul>
            </div>
            <div id="left_block">
                <div class="agenda">
                    {tag:menu_list /}
                </div>
            </div>
            <div id="right_block">
                <div class="obsah">
                    {tag:content /}
                </div>
            </div>             
        </div>
    </body>
</html>
