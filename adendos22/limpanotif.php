<?
$link5 = mysqli_connect("mysql857.umbler.com","2ahanupr","?2{*}26ObouSh","fr8phust");

if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
mysqli_query($link5,"DELETE from melinotif LIMIT 715");
	{
echo("Error description: " . mysqli_error($link5));
	}
mysqli_close($link5);
?>