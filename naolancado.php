<?php
session_start();
$mltoken="$_SESSION[access_token]";

$link10 = mysqli_connect(""host","user","pass","bd");

$link49 = mysqli_connect(""host","user","pass","bd");
 
// Define variables and initialize with empty values
$venda = "";
$venda_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	

	// Validate Venda
	$input_venda = trim($_POST["venda"]);
    if(empty($input_venda)){
        $venda_err = 'Digite o id do pagamento.';     
    } else{
        $venda = $input_venda;
    }
	
	 if(empty($venda_err)){
	
		$shipment = mysqli_query($link10,"SELECT shipments FROM meliorders WHERE pagamento='$venda'");
		$row5 = mysqli_fetch_array($shipment);
		$shipmentnumber = $row5[0];
	
		$rshipment="/shipments/$shipmentnumber";
		echo "$row5";
		echo "$shipmentnumber";

			
			if ($shipmentnumber == null) {
				
				
							echo "Não consta nenhum envio. Realizando pesquisa via API no MercadoLivre.<br>";
								//$attestoque="{'available_quantity':'$estoquecorrigido'}";
								$urlgetorder="https://api.mercadolibre.com/collections/$venda?access_token=$mltoken";
								$chES = curl_init($urlgetorder);
								curl_setopt_array($chES,[
									CURLOPT_CUSTOMREQUEST=>"GET",
									//CURLOPT_POSTFIELDS=>"$attestoque",
									CURLOPT_RETURNTRANSFER => 1,
									]);
								$respurlgetorder = (curl_exec($chES));
								curl_close($chES);
								
								$mlresponde86 = json_decode($respurlgetorder,true);
								
								echo "Pedido encontrado, obtendo dados necessários.<br>";
								//echo "$respurlgetorder <br>";
								
								$orderid=$mlresponde86['order_id'];
								
								//$attestoque="{'available_quantity':'$estoquecorrigido'}";
								$urlgetshipment="https://api.mercadolibre.com/orders/$orderid?access_token=$mltoken";
								$chESX = curl_init($urlgetshipment);
								curl_setopt_array($chESX,[
									CURLOPT_CUSTOMREQUEST=>"GET",
									//CURLOPT_POSTFIELDS=>"$attestoque",
									CURLOPT_RETURNTRANSFER => 1,
									]);
								$respurlgetshipment = (curl_exec($chESX));
								curl_close($chESX);
								
								$mlresponde89 = json_decode($respurlgetshipment,true);
								
								$shippingid=$mlresponde89['shipping']['id'];
								//echo "$mlresponde86 <br>";								
								//echo "$mlresponde89";
								echo "<br><br>";
								echo "$shippingid";
								//echo "$respurlgetshipment";
								//echo "$urlgetorder";
								echo "$orderid";
								
								
						mysqli_query($link49, "UPDATE melinotif SET `status`='lida' WHERE `resource`='/shipments/$shippingid' LIMIT 1");
				
					if (mysqli_affected_rows($link49) > 0) {
						echo "Cadastrado com sucesso. Aguarde o lançamento pelo integrador.<a href='https://shopdng1.websiteseguro.com/projeto-ml/naolancado.php'>Voltar</a>";
						mysqli_close($link49);
					}else{
						mysqli_query($link49, "INSERT INTO melinotif (resource,status) VALUES ('/shipments/$shippingid','novo')") OR die("Error:".mysql_error());
						echo "Inserido com sucesso. Aguarde o lançamento pelo integrador.<a href='https://shopdng1.websiteseguro.com/projeto-ml/naolancado.php'>Voltar</a>";
						mysqli_close($link49);
					}
			}else{
				mysqli_query($link10, "UPDATE melinotif SET `status`='lida' WHERE `resource`='$rshipment' LIMIT 1");
				echo "$rshipment";
				echo "Venda encontrada. Aguarde lançamento pelo integrador.<a href='https://shopdng1.websiteseguro.com/projeto-ml/naolancado.php'>Voltar</a>";
				}
	   
		}
    
    // Close connection
    mysqli_close($link10);
	
}else{
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Venda</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Adicionar venda</h2>
                    </div>
                    <p>Apenas para vendas não integradas. Caso haja "lance manualmente" deve ser feito diretamente no sistema.</p>
					<p>Insira o id do pagamento e clique em enviar.</p>
					  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					  <div class="col-md-6">
                        <div class="form-group <?php echo (!empty($venda_err)) ? 'has-error' : ''; ?>">
                            <label>Pagamento</label>
                            <input type="text" name="venda" class="form-control" value="<?php echo $venda; ?>">
                            <span class="help-block"><?php echo $venda_err;?></span>
                        </div>
						<input type="submit" class="btn btn-primary" value="Enviar">
						<a href="index.php" class="btn btn-default">Cancelar</a>
						</div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
<?
}
?>
