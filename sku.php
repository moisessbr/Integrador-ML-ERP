<?php
session_start();
$mltoken="$_SESSION[access_token]";

$link29 = mysqli_connect(""host","user","pass","bd");
$link30 = mysqli_connect(""host","user","pass","bd");
 
// Define variables and initialize with empty values
$addsku = "";
$addsku_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
		

	// Validate Venda
	$input_addsku = trim($_POST["addsku"]);
	
	//echo $input_addsku;
	//echo $_POST["addsku"];
	
		if(empty($input_addsku)){
        $addsku_err = 'Digite o código do anúncio.';     
    } else{
        $addsku = $input_addsku;
    }
	
		if(empty($addsku_err)){
			
			$buscamelip = mysqli_query($link30,"SELECT * FROM pmaster WHERE codigo='$addsku'");
		
			
			if (mysqli_affected_rows($link30) > 0) {

				//$atualizados = mysqli_affected_rows($link30);
				echo "Item já cadastrado no sistema!";
				echo "<a href='sku.php'>Voltar</a>";
				mysqli_close($link30);
				mysqli_close($link29);
                die("");					
				//Se não tem itens para atualizar ele fica em standby.
			}else{
				echo "Item não cadastrado!<br>";
				echo "Realizando o cadastro!<br>";
				mysqli_query($link30,"INSERT INTO pmaster (codigo,estoque,status,busca) VALUES ('$addsku','0','OK','A')") OR die("Error:".mysql_error());	
				echo "<br>Cadastro realizado com sucesso!";
				echo "<br><a href='sku.php'>Voltar</a>";
				mysqli_close($link29);
				mysqli_close($link30);
				die(".");	
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
    <title>Adicionar SKUs</title>
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
                        <h2>Adição de SKUs para Sincronia de Estoque</h2>
                    </div>
                    <p>Digite o código do SKU no sistema. Após a adição ele será enviado para a fila de sincronia.</p>
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					  <div class="col-md-6">
                        <div class="form-group <?php echo (!empty($addsku_err)) ? 'has-error' : ''; ?>">
                            <label>Código:</label>
                            <input type="text" name="addsku" class="form-control" value="<?php echo $addsku; ?>">
                            <span class="help-block"><?php echo $addsku_err;?></span>
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
