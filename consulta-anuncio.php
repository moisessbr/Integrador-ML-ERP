<?php
session_start();
$mltoken="$_SESSION[access_token]";

$link29 = mysqli_connect(""host","user","pass","bd");
$link30 = mysqli_connect(""host","user","pass","bd");
 
// Define variables and initialize with empty values
$consultaexclue = "";
$consultaexclue_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
		

	// Validate Venda
	$input_consultaexclue = trim($_POST["consultaexclue"]);
	
	//echo $input_consultaexclue;
	//echo $_POST["consultaexclue"];
	
		if(empty($input_consultaexclue)){
        $consultaexclue_err = 'Digite o código do anúncio.';     
    } else{
        $consultaexclue = $input_consultaexclue;
    }
	
		if(empty($consultaexclue_err)){
			
			$buscamelip = mysqli_query($link30,"DELETE FROM meliprodutos WHERE mlb='$consultaexclue'");
		
			
			if (mysqli_affected_rows($link30) > 0) {

				$atualizados = mysqli_affected_rows($link30);
				echo "Foram excluídos $atualizados produtos(s) e variações.";
				echo "<a href='consulta-anuncio.php'>Voltar</a>";
				mysqli_close($link30);
				mysqli_close($link29);
                die(".");					
				//Se não tem itens para atualizar ele fica em standby.
	}else{
		echo "Item não cadastrado!";
		echo "<a href='consulta-anuncio.php'>Voltar</a>";
		echo "<br><a href='sobe-ml.php'>Voltar</a>";
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
    <title>Exclusor de Conteúdos</title>
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
                        <h2>Consultar/Excluir</h2>
                    </div>
                    <p>Digite o código no formato <b>MLB123456</b>. Ao dar OK o registro será excluído caso conste. </p>
					<p>Após a exclusão adicione novamente <a href="sobe-ml.php" target="_blank">clicando aqui</a></p>
					  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					  <div class="col-md-6">
                        <div class="form-group <?php echo (!empty($consultaexclue_err)) ? 'has-error' : ''; ?>">
                            <label>Código:</label>
                            <input type="text" name="consultaexclue" class="form-control" value="<?php echo $consultaexclue; ?>">
                            <span class="help-block"><?php echo $consultaexclue_err;?></span>
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
