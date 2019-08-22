<?
$mltoken="APP_USR-4306843561211867-021908-61764517e81913e07202b9771ec664c2__N_H__-133631253";


//Passo 03

//$dados=
//Resource de orders
//$dados2=
//Resource de shipments

$link2 = mysqli_connect("mysql857.umbler.com","2ahanupr","?2{*}26ObouSh","fr8phust");

if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
//POR O $DADOS DE VOLTA NO LUGAR DO RESOURCE
mysqli_query($link2,"INSERT INTO meliorders (resource,orders,shipments,buyerid,produto,titulo,valor,quantidade,frete,valortotal,pagamento) VALUES ('/orders/1289342698','1289342698','25943308796','1234567890','7256','PRODUTO TESTE ML','128.9','1','15.51','144.41','894984984')");
	{
echo("Error description: " . mysqli_error($link2));
	}
mysqli_close($link2);



$link3 = mysqli_connect("mysql857.umbler.com","2ahanupr","?2{*}26ObouSh","fr8phust");

if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
mysqli_query($link3,"INSERT INTO melibuyer (id,nome,tipo,documento,ddd,telefone) VALUES ('1234567890','Manuel Testador da Silva','F','41231422491','11','999912345')");
	{
echo("Error description: " . mysqli_error($link3));
	}
mysqli_close($link3);

$link5 = mysqli_connect("mysql857.umbler.com","2ahanupr","?2{*}26ObouSh","fr8phust");

if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
mysqli_query($link5,"INSERT INTO melishipments (resource,shipment,cep,endereco,numero,cidade,codigoibge,bairro,complemento) VALUES ('/shipments/25943308796','25943308796','03128141','Rua Manuel Teste','1234','São Paulo','3550308','Vila Prudente','Apto 2245')");
	{
echo("Error description: " . mysqli_error($link5));
	}
mysqli_close($link5);

?>