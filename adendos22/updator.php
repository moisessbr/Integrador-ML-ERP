<?
$link10 = mysqli_connect("mysql857.umbler.com","2ahanupr","?2{*}26ObouSh","fr8phust");

mysqli_query($link10,"UPDATE melinotif SET status='novo' WHERE resource LIKE '/orders/%' LIMIT 50");

mysqli_query($link10,"UPDATE melinotif SET status='novo' WHERE resource LIKE '/shipments/%' LIMIT 50");

mysqli_close($link10);
?>