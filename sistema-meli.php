<html>
<title>Puxa Estoque</title>
</html>
<?php
//Token do MercadoLivre
session_start();
$mltoken="$_SESSION[access_token]";

$link29 = mysqli_connect(""host","user","pass","bd");

$link30 = mysqli_connect(""host","user","pass","bd");

if (mysqli_connect_errno())
	{
echo "Falha o realizar conexão ao banco de dados: " . mysqli_connect_error();
	}
	
	//Busca itens para atualizar
	
	$buscapmaster = mysqli_query($link29,"SELECT * FROM pmaster WHERE status='ATT' LIMIT 1");
		
	//Se tem itens para atualizar ele continua o script.
	if (mysqli_num_rows($buscapmaster) > 0) {

		$dadospmaster = mysqli_fetch_array($buscapmaster,MYSQLI_ASSOC);
		$idpmaster=$dadospmaster['id'];
		$codigopmaster=$dadospmaster['codigo'];
		$estoquepmaster=$dadospmaster['estoque'];
		
		$buscamelip = mysqli_query($link30,"UPDATE meliprodutos SET estoque='$estoquepmaster', status='ATT' WHERE sku ='$codigopmaster'");
		
			
			if (mysqli_affected_rows($link30) > 0) {

				$atualizados = mysqli_affected_rows($link30);
				mysqli_query($link29,"UPDATE pmaster SET status='OK' WHERE id='$idpmaster'");	
		        echo "Foram enviados para atualização $atualizados anúncios!";
				echo "Procurando novos itens para atualizar em 3 segundos!";
		//echo "$idpmaster <br>$codigopmaster <br> $estoquepmaster";
				header("Refresh:3");
				mysqli_close($link30);
				mysqli_close($link29);
			}else{		
                mysqli_query($link29,"UPDATE pmaster SET status='erro' WHERE id='$idpmaster'");			
				$atualizados = mysqli_affected_rows($link29);
				echo "$atualizados registros alterados <br>";
				echo "$idpmaster";
				echo "Algum erro ocorreu no update da tabela meliprodutos. Verifique!";
				header("Refresh:3");
				mysqli_close($link29);
				}

			//Se não tem itens para atualizar ele fica em standby.
	}else{
		echo "Nenhum item para atualizar. Aguardando.<br> Verificando novamente em 90 segundos.";
		header("Refresh:90");
		mysqli_close($link29);
		mysqli_close($link30);
	}



?>