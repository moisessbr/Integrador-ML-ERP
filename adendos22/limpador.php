<?
$link5 = mysqli_connect("mysql857.umbler.com","2ahanupr","?2{*}26ObouSh","fr8phust");

if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
mysqli_query($link5,"DELETE FROM meliorders WHERE resource LIKE '/orders/%'");
	{
echo("Error description: " . mysqli_error($link5));
	}
mysqli_close($link5);

$link6 = mysqli_connect("mysql857.umbler.com","2ahanupr","?2{*}26ObouSh","fr8phust");

if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
mysqli_query($link6,"DELETE FROM melishipments WHERE resource LIKE '/shipments/%'");
	{
echo("Error description: " . mysqli_error($link6));
	}
mysqli_close($link6);

$link7 = mysqli_connect("mysql857.umbler.com","2ahanupr","?2{*}26ObouSh","fr8phust");

if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
mysqli_query($link7,"DELETE FROM melibuyer WHERE tipo ='F'");
mysqli_query($link7,"DELETE FROM melibuyer WHERE tipo ='J'");
	{
echo("Error description: " . mysqli_error($link7));
	}
mysqli_close($link7);
?>