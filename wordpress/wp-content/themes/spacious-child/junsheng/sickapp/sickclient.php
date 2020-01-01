<?php

?>


<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
     <!-- <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>
      <link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/flick/jquery-ui.css"> -->


      <style type="text/css">

          div.ui-datepicker
          {
            font-size:12px;
          }

      </style>

  </head>
  <body>

<div class="row">
<div class="col-sm-12" style="margin-bottom:2%;">
  <b>Courier Sick Leave Application</b>
  <p></p>
  Please select a courier:<br/>
  <select id="ddList" size="8">
  </select>
</div>
<div class="col-sm-12">
  <div id="datedisplay" style="display:none;">
  MC End Date (Click icon to select date): 
  <input type="text" id="datepicker" disabled style="width:50%;">
  </div>
</div>

<div class="col-sm-12">
  <div id="confirmdisplay" style="display:none">
  </div>
  <br/>

  <div id="showbuttons" style="display:none">
  <input id="confirmbtn" type="button" value="Confirm"/>
  <input id="nobtn" type="button" value="No"/>
  </div>

</div>

</div>

  </body>
</html>