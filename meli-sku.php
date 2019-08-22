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
	
	$buscameli = mysqli_query($link31,"SELECT * FROM meliprodutos WHERE status='SKU' LIMIT 1");
		
	//Se tem itens para atualizar ele continua o script.
	if (mysqli_num_rows($buscameli) > 0) {

		$dadosbmeli = mysqli_fetch_array($buscameli,MYSQLI_ASSOC);
		$iddadosbmeli=$dadosbmeli['id'];
		$mlbdadosbmeli=$dadosbmeli['mlb'];
		$variadadosbmeli=$dadosbmeli['varia'];
		$variationdadosbmeli=$dadosbmeli['variation'];
		$skudadosbmeli=$dadosbmeli['sku'];
		//$estoquedadosbmeli="5";
	
		
		//sem variacao
				if ($variadadosbmeli== "N"){
					echo "Item sem variação!";
					//start
					
								
						$attsku="{attributes:[{'id':'SELLER_SKU','value_name':'$skudadosbmeli'}]}";
								$urlestoqueatt="https://api.mercadolibre.com/items/$mlbdadosbmeli?access_token=$mltoken";
								$chES = curl_init($urlestoqueatt);
								curl_setopt_array($chES,[
									CURLOPT_CUSTOMREQUEST=>"PUT",
									CURLOPT_POSTFIELDS=>"$attsku",
									CURLOPT_RETURNTRANSFER => 1,
									]);
								$respostaestoqueatt = (curl_exec($chES));
								curl_close($chES);
								
								$mlresponde15 = json_decode($respostaestoqueatt,true);
								
														
								$atributos=$mlresponde15['attributes'];
								$ki=array_search("SELLER_SKU", array_column($atributos, 'id'));
								$sku0=$mlresponde15['attributes'][$ki]['value_name'];
								
												
									if ($sku0 == $skudadosbmeli){
										echo "<br>SKU atualizado com sucesso. Atualizando em 2 segundos. ID: $iddadosbmeli";
										echo "<br> Sku: $sku0";
										mysqli_query($link31,"UPDATE meliprodutos SET status='SKUATT' WHERE id ='$iddadosbmeli'");
										mysqli_close($link31);
										header("Refresh:2");										
									}else{
										echo "Houve algum erro. O sku está divergente! Verifique! ID: $iddadosbmeli";
										mysqli_query($link31,"UPDATE meliprodutos SET status='SKUERROR' WHERE id ='$iddadosbmeli'");
										mysqli_close($link31);
										echo "$respostaestoqueatt";
										echo "<br>$sku0";
										echo "$chES";
										header("Refresh:5");	
									}
					//end
				}
				if ($variadadosbmeli== "S"){
					echo "Item com variação!";
					
					
								$attskuv="{attributes:[{'id':'SELLER_SKU','value_name':'$skudadosbmeli'}]}";
								$urlestoqueattv="https://api.mercadolibre.com/items/$mlbdadosbmeli/variations/$variationdadosbmeli?include_attributes=all&access_token=$mltoken";
								$chESv = curl_init($urlestoqueattv);
								curl_setopt_array($chESv,[
									CURLOPT_CUSTOMREQUEST=>"PUT",
									CURLOPT_POSTFIELDS=>"$attskuv",
									CURLOPT_RETURNTRANSFER => 1,
									]);
								$respostaestoqueattv = (curl_exec($chESv));
								curl_close($chESv);
								
								$mlresponde15v = json_decode($respostaestoqueattv,true);
																					
								$estoquenovov=$mlresponde15v[0]['attributes'];
								
														
								$atributos=$mlresponde15v[0]['attributes'];
								$ki=array_search("SELLER_SKU", array_column($atributos, 'id'));
								$skuv=$mlresponde15v[0]['attributes'][$ki]['value_name'];
								
									echo "$skuv";
												
									if ($skuv == $skudadosbmeli){
										echo "<br>SKU atualizado com sucesso. Atualizando em 2 segundos. ID: $iddadosbmeli";
										echo "<br> Sku: $skuv";
										mysqli_query($link31,"UPDATE meliprodutos SET status='SKUATT' WHERE id ='$iddadosbmeli'");
										mysqli_close($link31);
									header("Refresh:2");										
									}else{
										echo "Houve algum erro. O SKU está divergente! Verifique! ID: $iddadosbmeli";
										mysqli_query($link31,"UPDATE meliprodutos SET status='SKUERROR' WHERE id ='$iddadosbmeli'");
										mysqli_close($link31);
										echo "<br><br>$mlresponde15v<br><br><br>$skuv";
										echo "<br><br>$chESv";
										header("Refresh:5");	
									}										
																
									
					
				}

			//Se não tem itens para atualizar ele fica em standby.
	}else{
		echo "Nenhum item para atualizar. Aguardando.<br> Verificando novamente em 90 segundos.";
		header("Refresh:90");
		mysqli_close($link31);
	}



?>