<?php

require_once('ui_factory.php');

?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="semantic/minified/elements/segment.min.css" />
    </head>
    <body>
        <?php
        
            ui::segment('Te eum doming eirmod, nominati pertinacia argumentum ad his.')->piled()->render();
            ui::segment('Te eum doming eirmod, nominati pertinacia argumentum ad his.')->stacked()->render();

            ui::alert('test');
            
        ?>
    </body>
</html>
