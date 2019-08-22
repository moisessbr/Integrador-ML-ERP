<?

$linkx = mysqli_connect("mysql857.umbler.com","2ahanupr","?2{*}26ObouSh","fr8phust");

if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: "
 . mysqli_connect_error();
	}
//POR O $DADOS DE VOLTA NO LUGAR DO RESOURCE
mysqli_query($linkx,"INSERT INTO meliorders (resource,orders,shipments,buyerid,produto,titulo,valor,quantidade,frete,valortotal,pagamento,data) VALUES ('/orders/1289343330','1289343330','25943308796','1234567890','7257','PRODUTO TESTE ML 2','10.82','1','19.23','30.05','894984984','2018-02-05T15:17:56-04:00
')");
	{
echo("Error description: " . mysqli_error($linkx));
	}
mysqli_close($linkx);

?>