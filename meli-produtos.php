<html>
<title>Estoque ML</title>
</html>
<?php
//Token do MercadoLivre
session_start();
$mltoken="$_SESSION[access_token]";

$link31 = mysqli_connect(""host","user","pass","bd");

if (mysqli_connect_errno())
	{
echo "Falha o realizar conexão ao banco de dados: " . mysqli_connect_error();
	}
	
	//Busca itens para atualizar
	
	$buscameli = mysqli_query($link31,"SELECT * FROM meliprodutos WHERE status='ATT' LIMIT 1");
		
	//Se tem itens para atualizar ele continua o script.
	if (mysqli_num_rows($buscameli) > 0) {

		$dadosbmeli = mysqli_fetch_array($buscameli,MYSQLI_ASSOC);
		$iddadosbmeli=$dadosbmeli['id'];
		$mlbdadosbmeli=$dadosbmeli['mlb'];
		$variadadosbmeli=$dadosbmeli['varia'];
		$variationdadosbmeli=$dadosbmeli['variation'];
		$skudadosbmeli=$dadosbmeli['sku'];
		$estoquedadosbmeli=$dadosbmeli['estoque'];
		//$estoquedadosbmeli="5";
		
		//corrigir estoque
		$estoquecorrigido=$estoquedadosbmeli;
		
			if ($estoquedadosbmeli<= 0){
				$estoquecorrigido="0";
			}
			if ($estoquedadosbmeli>= 1000){
				$estoquecorrigido="999";
			}
		//sem variacao
				if ($variadadosbmeli== "N"){
					echo "Item sem variação!";
					//start
					
									
								$attestoque="{'available_quantity':'$estoquecorrigido'}";
								$urlestoqueatt="https://api.mercadolibre.com/items/$mlbdadosbmeli?access_token=$mltoken";
								$chES = curl_init($urlestoqueatt);
								curl_setopt_array($chES,[
									CURLOPT_CUSTOMREQUEST=>"PUT",
									CURLOPT_POSTFIELDS=>"$attestoque",
									CURLOPT_RETURNTRANSFER => 1,
									]);
								$respostaestoqueatt = (curl_exec($chES));
								curl_close($chES);
								
								$mlresponde15 = json_decode($respostaestoqueatt,true);
								
								$estoquenovo=$mlresponde15['available_quantity'];
								
									if ($estoquenovo == $estoquecorrigido){
										echo "Estoque atualizado com sucesso. Atualizando em 3 segundos. ID: $iddadosbmeli";
										mysqli_query($link31,"UPDATE meliprodutos SET status='OK' WHERE id ='$iddadosbmeli'");
										mysqli_close($link31);
										header("Refresh:3");										
									}else{
										echo "Houve algum erro. O estoque está divergente! Verifique! ID: $iddadosbmeli";
										mysqli_query($link31,"UPDATE meliprodutos SET status='erro' WHERE id ='$iddadosbmeli'");
										mysqli_close($link31);
										//echo "$respostaestoqueatt";
										//echo "<br>$estoquecorrigido";
										//echo "$chES";
										header("Refresh:5");	
									}
					//end
				}
				if ($variadadosbmeli== "S"){
					echo "Item com variação!";
					
					
								$attestoquev="{'available_quantity':'$estoquecorrigido'}";
								$urlestoqueattv="https://api.mercadolibre.com/items/$mlbdadosbmeli/variations/$variationdadosbmeli?access_token=$mltoken";
								$chESv = curl_init($urlestoqueattv);
								curl_setopt_array($chESv,[
									CURLOPT_CUSTOMREQUEST=>"PUT",
									CURLOPT_POSTFIELDS=>"$attestoquev",
									CURLOPT_RETURNTRANSFER => 1,
									]);
								$respostaestoqueattv = (curl_exec($chESv));
								curl_close($chESv);
								
								//$mlresponde15v = json_decode($respostaestoqueattv,true);
								
								//$estoquenovov=$mlresponde15v['available_quantity'];
								
								
										echo "Estoque atualizado com sucesso. Atualizando em 3 segundos. ID:$iddadosbmeli";
										mysqli_query($link31,"UPDATE meliprodutos SET status='OK' WHERE id ='$iddadosbmeli'");
										mysqli_close($link31);
										header("Refresh:3");										
																
									
					
				}

			//Se não tem itens para atualizar ele fica em standby.
	}else{
		echo "Nenhum item para atualizar. Aguardando.<br> Verificando novamente em 90 segundos.";
		header("Refresh:90");
		mysqli_close($link31);
	}



?>