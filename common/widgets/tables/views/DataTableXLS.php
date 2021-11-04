<?php
header("Content-Type:   application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=report.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
?>
<html>
 <head>
  <meta charset="utf-8">
 </head>
<body>
<?php
echo $header;
echo $body;
echo $footer;
?>
</body>
</html>