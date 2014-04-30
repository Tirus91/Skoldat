<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{tag:lang /}" lang="{tag:lang /}">
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <meta name="author" content="Tomas Kulhanek" />
  <meta name="description" content="Školdat" />
  <meta name="keywords" content="škola" />
  <meta name="robots" content="noindex,follow">
  <title>{tag:sitename /} :: {tag:title /}</title>
  <link rel="stylesheet" type="text/css" href="{tag:homelink /}/templates/default/styles/style.css" media="screen" />
  <link rel="stylesheet" type="text/css" href="{tag:homelink /}/templates/default/styles/calendar.css"  media="screen" />
  <link rel="stylesheet" type="text/css" href="{tag:homelink /}/templates/default/styles/jquery.css"  media="screen" />
  <script src="{tag:homelink /}/public/javascript/current_time.js" type="text/javascript"></script>
  <script src="{tag:homelink /}/public/javascript/jquery.js" type="text/javascript"></script>
  <script src="{tag:homelink /}/public/javascript/jquery1-8-8-ui.min.js" type="text/javascript"></script>
  <script src="{tag:homelink /}/public/javascript/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
  <script src="{tag:homelink /}/public/javascript/jquery/jquery.js" type="text/javascript"></script>



</head>

<body>
    <div class="main_body">
    <div id="head">
        <div id="top_bar">&nbsp;</div>
        <div id="middle_bar">{tag:sitename /}</div>
        <div class="bottom_bar">
            <div id="left">{tag:login_text /}</div>
            <div id="right"><span id="curr_time"></span></div>
        </div>
        <div class="bottom_bar"></div>
    </div>
    <div id="body_page">
        <div id="main_block">
            <div id="text">
            
                {tag:content /}
            <!--<input type="text" id="datepicker">
            <input type="text" id="timepicker">-->

            </div>
        </div>
        <div id="menu">
            {tag:menu_list /}
        </div> 
    </div>
    <div id="footer"></div>
</div>
<script> </script>
</body>
</html>
