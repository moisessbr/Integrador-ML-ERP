<?php
session_start();
$mltoken="$_SESSION[access_token]";

$link10 = mysqli_connect(""host","user","pass","bd");

 
// Define variables and initialize with empty values
$mlbbuscavaria = "";
$mlbbuscavaria_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
		

	// Validate Venda
	$input_mlbbuscavaria = trim($_POST["mlbbuscavaria"]);
	
	//echo $input_mlbbuscavaria;
	//echo $_POST["mlbbuscavaria"];
	
		if(empty($input_mlbbuscavaria)){
        $mlbbuscavaria_err = 'Digite o código do anúncio.';     
    } else{
        $mlbbuscavaria = $input_mlbbuscavaria;
    }
	
		if(empty($mlbbuscavaria_err)){
		
				$urlapi6="https://api.mercadolibre.com/items/$mlbbuscavaria/variations?include_attributes=all&access_token=$mltoken";

				$curl6 = curl_init();

				curl_setopt_array($curl6, array(
				  CURLOPT_URL => $urlapi6,
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "GET",
				  CURLOPT_HTTPHEADER => array("x-format-new: true"),
				));

				$response6 = curl_exec($curl6);
				$err6 = curl_error($curl6);

				curl_close($curl6);

				//>>>   CONTAR OS OBJETOS JSON, DEPOIS CRIAR OS MYSQL PARA SALVAR OS DADOS DAS VARIACOES NOS CAMPOS DA TABELA <<

				$mlresponde6 = json_decode($response6,true);

				$contagem = count($mlresponde6);

				if ($contagem== NULL){
					
					$urlapi9="https://api.mercadolibre.com/items/$mlbbuscavaria?include_attributes=all&access_token=$mltoken";

				$curl9 = curl_init();

				curl_setopt_array($curl9, array(
				  CURLOPT_URL => $urlapi9,
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "GET",
				  CURLOPT_HTTPHEADER => array("x-format-new: true"),
				));

				$response9 = curl_exec($curl9);
				$err9 = curl_error($curl9);

				curl_close($curl9);

				$mlresponde9 = json_decode($response9,true);

							$sellercustomfield0=$mlresponde9['seller_custom_field'];
							
							if ($sellercustomfield0== NULL){
								
									$atributos=$mlresponde9['attributes'];

									$ki=array_search("SELLER_SKU", array_column($atributos, 'id'));
											if ($ki== false){
											$sku0="ssku";
											}else{
											$sku0=$mlresponde9['attributes'][$ki]['value_name'];
											}
							}else{
								$sku0=$sellercustomfield0;
							}
					$estoque0=$mlresponde9['available_quantity'];
					//echo "Código do item: $id0";
					//echo "$response9";
					echo '<br />';
					mysqli_query($link10,"INSERT INTO meliprodutos (mlb,varia,sku,estoque,status) VALUES ('$mlbbuscavaria','N','$sku0','$estoque0','OK')") OR die("Error:".mysql_error());	
					//header("Refresh:2");
					//echo "$idbuscavaria <br>";
					echo "ID: $mlbbuscavaria<br>";
					echo "SKU: $sku0 <br>";
					echo "<a href='sobe-ml.php'>Voltar</a>";
					die("Sem variações. Inserido SKU principal.");	
					
				}

				if ($contagem== 1){
					$id1=$mlresponde6[0]['id'];
					$sellercustomfield1=$mlresponde6[0]['seller_custom_field'];
				if ($sellercustomfield1== NULL){
					$atributos1=$mlresponde6[0]['attributes'];
					$ki1=array_search("SELLER_SKU", array_column($atributos1, 'id'));
							if ($ki1== false){
							$sku1="ssku";
							}else{
							$sku1=$mlresponde6[0]['attributes'][$ki1]['value_name'];
							}
			}else{
				$sku1=$sellercustomfield1;
			}
					$estoque1=$mlresponde6[0]['available_quantity'];
					mysqli_query($link10,"INSERT INTO meliprodutos (mlb,varia,variation,sku,estoque,status) VALUES ('$mlbbuscavaria','S','$id1','$sku1','$estoque1','OK')") OR die("Error:".mysql_error());
					//header("Refresh:2");
					//echo "$idbuscavaria <br>";
					echo "ID: $mlbbuscavaria<br>";
					echo "SKU: $sku1 <br>";
					echo "<a href='sobe-ml.php'>Voltar</a>";
					echo "Encontrado anúncio com uma variação. Inserido:";
					die("1");	
					
				}
				
				if ($contagem== 2){
					$id1=$mlresponde6[0]['id'];
					$sellercustomfield1=$mlresponde6[0]['seller_custom_field'];
				if ($sellercustomfield1== NULL){
					$atributos1=$mlresponde6[0]['attributes'];
					$ki1=array_search("SELLER_SKU", array_column($atributos1, 'id'));
							if ($ki1== false){
							$sku1="ssku";
							}else{
							$sku1=$mlresponde6[0]['attributes'][$ki1]['value_name'];
							}
			}else{
				$sku1=$sellercustomfield1;
			}
					$estoque1=$mlresponde6[0]['available_quantity'];
					$id2=$mlresponde6[1]['id'];
					$sellercustomfield2=$mlresponde6[1]['seller_custom_field'];
				if ($sellercustomfield2== NULL){
					$atributos2=$mlresponde6[1]['attributes'];
					$ki2=array_search("SELLER_SKU", array_column($atributos2, 'id'));
							if ($ki2== false){
							$sku2="ssku";
							}else{
							$sku2=$mlresponde6[1]['attributes'][$ki2]['value_name'];
							}
			}else{
				$sku2=$sellercustomfield2;
			}
					$estoque2=$mlresponde6[1]['available_quantity'];
					mysqli_query($link10,"INSERT INTO meliprodutos (mlb,varia,variation,sku,estoque,status) VALUES ('$mlbbuscavaria','S','$id1','$sku1','$estoque1','OK'),('$mlbbuscavaria','S','$id2','$sku2','$estoque2','OK')") OR die("Error:".mysql_error());
					//header("Refresh:2");
					//echo "$idbuscavaria <br>";
					echo "ID: $mlbbuscavaria<br>";
					echo "SKU: $sku1 <br>";
					echo "SKU: $sku2 <br>";
					echo "<a href='sobe-ml.php'>Voltar</a>";
					echo "Encontrado anúncio com duas variações. Inserido:";
					die("2");	
					
				}
				
				if ($contagem== 3){
					$id1=$mlresponde6[0]['id'];
					$sellercustomfield1=$mlresponde6[0]['seller_custom_field'];
				if ($sellercustomfield1== NULL){
					$atributos1=$mlresponde6[0]['attributes'];
					$ki1=array_search("SELLER_SKU", array_column($atributos1, 'id'));
							if ($ki1== false){
							$sku1="ssku";
							}else{
							$sku1=$mlresponde6[0]['attributes'][$ki1]['value_name'];
							}
			}else{
				$sku1=$sellercustomfield1;
			}
					$estoque1=$mlresponde6[0]['available_quantity'];
					$id2=$mlresponde6[1]['id'];
					$sellercustomfield2=$mlresponde6[1]['seller_custom_field'];
				if ($sellercustomfield2== NULL){
					$atributos2=$mlresponde6[1]['attributes'];
					$ki2=array_search("SELLER_SKU", array_column($atributos2, 'id'));
							if ($ki2== false){
							$sku2="ssku";
							}else{
							$sku2=$mlresponde6[1]['attributes'][$ki2]['value_name'];
							}
			}else{
				$sku2=$sellercustomfield2;
			}
					$estoque2=$mlresponde6[1]['available_quantity'];
					$id3=$mlresponde6[2]['id'];
					$sellercustomfield3=$mlresponde6[2]['seller_custom_field'];
				if ($sellercustomfield3== NULL){
					$atributos3=$mlresponde6[2]['attributes'];
					$ki3=array_search("SELLER_SKU", array_column($atributos3, 'id'));
							if ($ki3== false){
							$sku3="ssku";
							}else{
							$sku3=$mlresponde6[2]['attributes'][$ki3]['value_name'];
							}
			}else{
				$sku3=$sellercustomfield3;
			}
					$estoque3=$mlresponde6[2]['available_quantity'];
					mysqli_query($link10,"INSERT INTO meliprodutos (mlb,varia,variation,sku,estoque,status) VALUES ('$mlbbuscavaria','S','$id1','$sku1','$estoque1','OK'),('$mlbbuscavaria','S','$id2','$sku2','$estoque2','OK'),('$mlbbuscavaria','S','$id3','$sku3','$estoque3','OK')") OR die("Error:".mysql_error());
					//header("Refresh:2");
					//echo "$idbuscavaria <br>";
					echo "ID: $mlbbuscavaria<br>";
					echo "SKU: $sku1 <br>";
					echo "SKU: $sku2 <br>";
					echo "SKU: $sku3 <br>";
					echo "<a href='sobe-ml.php'>Voltar</a>";
					echo "Encontrado anúncio com três variações. Inserido:";
					die("3");	
					
				}
				
				if ($contagem== 4){
					$id1=$mlresponde6[0]['id'];
					$sellercustomfield1=$mlresponde6[0]['seller_custom_field'];
				if ($sellercustomfield1== NULL){
					$atributos1=$mlresponde6[0]['attributes'];
					$ki1=array_search("SELLER_SKU", array_column($atributos1, 'id'));
							if ($ki1== false){
							$sku1="ssku";
							}else{
							$sku1=$mlresponde6[0]['attributes'][$ki1]['value_name'];
							}
			}else{
				$sku1=$sellercustomfield1;
			}
					$estoque1=$mlresponde6[0]['available_quantity'];
					$id2=$mlresponde6[1]['id'];
					$sellercustomfield2=$mlresponde6[1]['seller_custom_field'];
				if ($sellercustomfield2== NULL){
					$atributos2=$mlresponde6[1]['attributes'];
					$ki2=array_search("SELLER_SKU", array_column($atributos2, 'id'));
							if ($ki2== false){
							$sku2="ssku";
							}else{
							$sku2=$mlresponde6[1]['attributes'][$ki2]['value_name'];
							}
			}else{
				$sku2=$sellercustomfield2;
			}
					$estoque2=$mlresponde6[1]['available_quantity'];
					$id3=$mlresponde6[2]['id'];
					$sellercustomfield3=$mlresponde6[2]['seller_custom_field'];
				if ($sellercustomfield3== NULL){
					$atributos3=$mlresponde6[2]['attributes'];
					$ki3=array_search("SELLER_SKU", array_column($atributos3, 'id'));
							if ($ki3== false){
							$sku3="ssku";
							}else{
							$sku3=$mlresponde6[2]['attributes'][$ki3]['value_name'];
							}
			}else{
				$sku3=$sellercustomfield3;
			}
					$estoque3=$mlresponde6[2]['available_quantity'];
					$id4=$mlresponde6[3]['id'];
					 $sellercustomfield4=$mlresponde6[3]['seller_custom_field'];
				if ($sellercustomfield4== NULL){
					$atributos4=$mlresponde6[3]['attributes'];
					$ki4=array_search("SELLER_SKU", array_column($atributos4, 'id'));
							if ($ki4== false){
							$sku4="ssku";
							}else{
							$sku4=$mlresponde6[3]['attributes'][$ki4]['value_name'];
							}
			}else{
				$sku4=$sellercustomfield4;
			}
					$estoque4=$mlresponde6[3]['available_quantity'];
					mysqli_query($link10,"INSERT INTO meliprodutos (mlb,varia,variation,sku,estoque,status) VALUES ('$mlbbuscavaria','S','$id1','$sku1','$estoque1','OK'),('$mlbbuscavaria','S','$id2','$sku2','$estoque2','OK'),('$mlbbuscavaria','S','$id3','$sku3','$estoque3','OK'),('$mlbbuscavaria','S','$id4','$sku4','$estoque4','OK')") OR die("Error:".mysql_error());
					//header("Refresh:2");
					//echo "$idbuscavaria <br>";
					echo "ID: $mlbbuscavaria<br>";
					echo "SKU: $sku1 <br>";
					echo "SKU: $sku2 <br>";
					echo "SKU: $sku3 <br>";
					echo "SKU: $sku4 <br>";
					echo "<a href='sobe-ml.php'>Voltar</a>";
					echo "Encontrado anúncio com quatro variações. Inserido:";
					die("4");	
					
				}
				
				if ($contagem== 5){
					$id1=$mlresponde6[0]['id'];
					 $sellercustomfield1=$mlresponde6[0]['seller_custom_field'];
				if ($sellercustomfield1== NULL){
					$atributos1=$mlresponde6[0]['attributes'];
					$ki1=array_search("SELLER_SKU", array_column($atributos1, 'id'));
							if ($ki1== false){
							$sku1="ssku";
							}else{
							$sku1=$mlresponde6[0]['attributes'][$ki1]['value_name'];
							}
			}else{
				$sku1=$sellercustomfield1;
			}
					$estoque1=$mlresponde6[0]['available_quantity'];
					$id2=$mlresponde6[1]['id'];
					$sellercustomfield2=$mlresponde6[1]['seller_custom_field'];
				if ($sellercustomfield2== NULL){
					$atributos2=$mlresponde6[1]['attributes'];
					$ki2=array_search("SELLER_SKU", array_column($atributos2, 'id'));
							if ($ki2== false){
							$sku2="ssku";
							}else{
							$sku2=$mlresponde6[1]['attributes'][$ki2]['value_name'];
							}
			}else{
				$sku2=$sellercustomfield2;
			}
					$estoque2=$mlresponde6[1]['available_quantity'];
					$id3=$mlresponde6[2]['id'];
					 $sellercustomfield3=$mlresponde6[2]['seller_custom_field'];
				if ($sellercustomfield3== NULL){
					$atributos3=$mlresponde6[2]['attributes'];
					$ki3=array_search("SELLER_SKU", array_column($atributos3, 'id'));
							if ($ki3== false){
							$sku3="ssku";
							}else{
							$sku3=$mlresponde6[2]['attributes'][$ki3]['value_name'];
							}
			}else{
				$sku3=$sellercustomfield3;
			}
					$estoque3=$mlresponde6[2]['available_quantity'];
					$id4=$mlresponde6[3]['id'];
					$sellercustomfield4=$mlresponde6[3]['seller_custom_field'];
				if ($sellercustomfield4== NULL){
					$atributos4=$mlresponde6[3]['attributes'];
					$ki4=array_search("SELLER_SKU", array_column($atributos4, 'id'));
							if ($ki4== false){
							$sku4="ssku";
							}else{
							$sku4=$mlresponde6[3]['attributes'][$ki4]['value_name'];
							}
			}else{
				$sku4=$sellercustomfield4;
			}
					$estoque4=$mlresponde6[3]['available_quantity'];
					$id5=$mlresponde6[4]['id'];
					$sellercustomfield5=$mlresponde6[4]['seller_custom_field'];
				if ($sellercustomfield5== NULL){
					$atributos5=$mlresponde6[4]['attributes'];
					$ki5=array_search("SELLER_SKU", array_column($atributos5, 'id'));
							if ($ki5== false){
							$sku5="ssku";
							}else{
							$sku5=$mlresponde6[4]['attributes'][$ki5]['value_name'];
							}
			}else{
				$sku5=$sellercustomfield5;
			}
					$estoque5=$mlresponde6[4]['available_quantity'];
					mysqli_query($link10,"INSERT INTO meliprodutos (mlb,varia,variation,sku,estoque,status) VALUES ('$mlbbuscavaria','S','$id1','$sku1','$estoque1','OK'),('$mlbbuscavaria','S','$id2','$sku2','$estoque2','OK'),('$mlbbuscavaria','S','$id3','$sku3','$estoque3','OK'),('$mlbbuscavaria','S','$id4','$sku4','$estoque4','OK'),('$mlbbuscavaria','S','$id5','$sku5','$estoque5','OK')") OR die("Error:".mysql_error());
					//header("Refresh:2");
					//echo "$idbuscavaria <br>";
					echo "ID: $mlbbuscavaria<br>";
					echo "SKU: $sku1 <br>";
					echo "SKU: $sku2 <br>";
					echo "SKU: $sku3 <br>";
					echo "SKU: $sku4 <br>";
					echo "SKU: $sku5 <br>";
					echo "<a href='sobe-ml.php'>Voltar</a>";
					echo "Encontrado anúncio com cinco variações. Inserido:";
					die("5");	
					
				}
				
				if ($contagem== 6){
					$id1=$mlresponde6[0]['id'];
					$sellercustomfield1=$mlresponde6[0]['seller_custom_field'];
				if ($sellercustomfield1== NULL){
					$atributos1=$mlresponde6[0]['attributes'];
					$ki1=array_search("SELLER_SKU", array_column($atributos1, 'id'));
							if ($ki1== false){
							$sku1="ssku";
							}else{
							$sku1=$mlresponde6[0]['attributes'][$ki1]['value_name'];
							}
			}else{
				$sku1=$sellercustomfield1;
			}
					$estoque1=$mlresponde6[0]['available_quantity'];
					$id2=$mlresponde6[1]['id'];
					$sellercustomfield2=$mlresponde6[1]['seller_custom_field'];
				if ($sellercustomfield2== NULL){
					$atributos2=$mlresponde6[1]['attributes'];
					$ki2=array_search("SELLER_SKU", array_column($atributos2, 'id'));
							if ($ki2== false){
							$sku2="ssku";
							}else{
							$sku2=$mlresponde6[1]['attributes'][$ki2]['value_name'];
							}
			}else{
				$sku2=$sellercustomfield2;
			}
					$estoque2=$mlresponde6[1]['available_quantity'];
					$id3=$mlresponde6[2]['id'];
					$sellercustomfield3=$mlresponde6[2]['seller_custom_field'];
				if ($sellercustomfield3== NULL){
					$atributos3=$mlresponde6[2]['attributes'];
					$ki3=array_search("SELLER_SKU", array_column($atributos3, 'id'));
							if ($ki3== false){
							$sku3="ssku";
							}else{
							$sku3=$mlresponde6[2]['attributes'][$ki3]['value_name'];
							}
			}else{
				$sku3=$sellercustomfield3;
			}
					$estoque3=$mlresponde6[2]['available_quantity'];
					$id4=$mlresponde6[3]['id'];
					$sellercustomfield4=$mlresponde6[3]['seller_custom_field'];
				if ($sellercustomfield4== NULL){
					$atributos4=$mlresponde6[3]['attributes'];
					$ki4=array_search("SELLER_SKU", array_column($atributos4, 'id'));
							if ($ki4== false){
							$sku4="ssku";
							}else{
							$sku4=$mlresponde6[3]['attributes'][$ki4]['value_name'];
							}
			}else{
				$sku4=$sellercustomfield4;
			}
					$estoque4=$mlresponde6[3]['available_quantity'];
					$id5=$mlresponde6[4]['id'];
					 $sellercustomfield5=$mlresponde6[4]['seller_custom_field'];
				if ($sellercustomfield5== NULL){
					$atributos5=$mlresponde6[4]['attributes'];
					$ki5=array_search("SELLER_SKU", array_column($atributos5, 'id'));
							if ($ki5== false){
							$sku5="ssku";
							}else{
							$sku5=$mlresponde6[4]['attributes'][$ki5]['value_name'];
							}
			}else{
				$sku5=$sellercustomfield5;
			}
					$estoque5=$mlresponde6[4]['available_quantity'];
					$id6=$mlresponde6[5]['id'];
					$sellercustomfield6=$mlresponde6[5]['seller_custom_field'];
				if ($sellercustomfield6== NULL){
					$atributos6=$mlresponde6[5]['attributes'];
					$ki6=array_search("SELLER_SKU", array_column($atributos6, 'id'));
							if ($ki6== false){
							$sku6="ssku";
							}else{
							$sku6=$mlresponde6[5]['attributes'][$ki6]['value_name'];
							}
			}else{
				$sku6=$sellercustomfield6;
			}
					$estoque6=$mlresponde6[5]['available_quantity'];
					mysqli_query($link10,"INSERT INTO meliprodutos (mlb,varia,variation,sku,estoque,status) VALUES ('$mlbbuscavaria','S','$id1','$sku1','$estoque1','OK'),('$mlbbuscavaria','S','$id2','$sku2','$estoque2','OK'),('$mlbbuscavaria','S','$id3','$sku3','$estoque3','OK'),('$mlbbuscavaria','S','$id4','$sku4','$estoque4','OK'),('$mlbbuscavaria','S','$id5','$sku5','$estoque5','OK'),('$mlbbuscavaria','S','$id6','$sku6','$estoque6','OK')") OR die("Error:".mysql_error());
					//header("Refresh:2");
					//echo "$idbuscavaria <br>";
					echo "ID: $mlbbuscavaria<br>";
					echo "SKU: $sku1 <br>";
					echo "SKU: $sku2 <br>";
					echo "SKU: $sku3 <br>";
					echo "SKU: $sku4 <br>";
					echo "SKU: $sku5 <br>";
					echo "SKU: $sku6 <br>";
					echo "<a href='sobe-ml.php'>Voltar</a>";
					echo "Encontrado anúncio com seis variações. Inserido:";
					die("6");	
					
				}
				
				if ($contagem== 7){
										$id1=$mlresponde6[0]['id'];
					$sellercustomfield1=$mlresponde6[0]['seller_custom_field'];
				if ($sellercustomfield1== NULL){
					$atributos1=$mlresponde6[0]['attributes'];
					$ki1=array_search("SELLER_SKU", array_column($atributos1, 'id'));
							if ($ki1== false){
							$sku1="ssku";
							}else{
							$sku1=$mlresponde6[0]['attributes'][$ki1]['value_name'];
							}
			}else{
				$sku1=$sellercustomfield1;
			}
					$estoque1=$mlresponde6[0]['available_quantity'];
					$id2=$mlresponde6[1]['id'];
					$sellercustomfield2=$mlresponde6[1]['seller_custom_field'];
				if ($sellercustomfield2== NULL){
					$atributos2=$mlresponde6[1]['attributes'];
					$ki2=array_search("SELLER_SKU", array_column($atributos2, 'id'));
							if ($ki2== false){
							$sku2="ssku";
							}else{
							$sku2=$mlresponde6[1]['attributes'][$ki2]['value_name'];
							}
			}else{
				$sku2=$sellercustomfield2;
			}
					$estoque2=$mlresponde6[1]['available_quantity'];
					$id3=$mlresponde6[2]['id'];
					$sellercustomfield3=$mlresponde6[2]['seller_custom_field'];
				if ($sellercustomfield3== NULL){
					$atributos3=$mlresponde6[2]['attributes'];
					$ki3=array_search("SELLER_SKU", array_column($atributos3, 'id'));
							if ($ki3== false){
							$sku3="ssku";
							}else{
							$sku3=$mlresponde6[2]['attributes'][$ki3]['value_name'];
							}
			}else{
				$sku3=$sellercustomfield3;
			}
					$estoque3=$mlresponde6[2]['available_quantity'];
					$id4=$mlresponde6[3]['id'];
					$sellercustomfield4=$mlresponde6[3]['seller_custom_field'];
				if ($sellercustomfield4== NULL){
					$atributos4=$mlresponde6[3]['attributes'];
					$ki4=array_search("SELLER_SKU", array_column($atributos4, 'id'));
							if ($ki4== false){
							$sku4="ssku";
							}else{
							$sku4=$mlresponde6[3]['attributes'][$ki4]['value_name'];
							}
			}else{
				$sku4=$sellercustomfield4;
			}
					$estoque4=$mlresponde6[3]['available_quantity'];
					$id5=$mlresponde6[4]['id'];
					 $sellercustomfield5=$mlresponde6[4]['seller_custom_field'];
				if ($sellercustomfield5== NULL){
					$atributos5=$mlresponde6[4]['attributes'];
					$ki5=array_search("SELLER_SKU", array_column($atributos5, 'id'));
							if ($ki5== false){
							$sku5="ssku";
							}else{
							$sku5=$mlresponde6[4]['attributes'][$ki5]['value_name'];
							}
			}else{
				$sku5=$sellercustomfield5;
			}
					$estoque5=$mlresponde6[4]['available_quantity'];
					$id6=$mlresponde6[5]['id'];
					$sellercustomfield6=$mlresponde6[5]['seller_custom_field'];
				if ($sellercustomfield6== NULL){
					$atributos6=$mlresponde6[5]['attributes'];
					$ki6=array_search("SELLER_SKU", array_column($atributos6, 'id'));
							if ($ki6== false){
							$sku6="ssku";
							}else{
							$sku6=$mlresponde6[5]['attributes'][$ki6]['value_name'];
							}
			}else{
				$sku6=$sellercustomfield6;
			}
					$estoque6=$mlresponde6[5]['available_quantity'];
					$id7=$mlresponde6[6]['id'];
					                            $sellercustomfield7=$mlresponde6[5]['seller_custom_field'];
				if ($sellercustomfield7== NULL){
					$atributos7=$mlresponde6[5]['attributes'];
					$ki7=array_search("SELLER_SKU", array_column($atributos7, 'id'));
							if ($ki7== false){
							$sku7="ssku";
							}else{
							$sku7=$mlresponde6[5]['attributes'][$ki7]['value_name'];
							}
			}else{
				$sku7=$sellercustomfield7;
			}
					$estoque7=$mlresponde6[6]['available_quantity'];
					mysqli_query($link10,"INSERT INTO meliprodutos (mlb,varia,variation,sku,estoque,status) VALUES ('$mlbbuscavaria','S','$id1','$sku1','$estoque1','OK'),('$mlbbuscavaria','S','$id2','$sku2','$estoque2','OK'),('$mlbbuscavaria','S','$id3','$sku3','$estoque3','OK'),('$mlbbuscavaria','S','$id4','$sku4','$estoque4','OK'),('$mlbbuscavaria','S','$id5','$sku5','$estoque5','OK'),('$mlbbuscavaria','S','$id6','$sku6','$estoque6','OK'),('$mlbbuscavaria','S','$id7','$sku7','$estoque7','OK')") OR die("Error:".mysql_error());
					//header("Refresh:2");
					//echo "$idbuscavaria <br>";
					echo "ID: $mlbbuscavaria<br>";
					echo "SKU: $sku1 <br>";
					echo "SKU: $sku2 <br>";
					echo "SKU: $sku3 <br>";
					echo "SKU: $sku4 <br>";
					echo "SKU: $sku5 <br>";
					echo "SKU: $sku6 <br>";
					echo "SKU: $sku7 <br>";
					echo "<a href='sobe-ml.php'>Voltar</a>";
					echo "Encontrado anúncio com sete variações. Inserido:";
					die("7");	
					
				}
				
				if ($contagem== 8){
															$id1=$mlresponde6[0]['id'];
					$sellercustomfield1=$mlresponde6[0]['seller_custom_field'];
				if ($sellercustomfield1== NULL){
					$atributos1=$mlresponde6[0]['attributes'];
					$ki1=array_search("SELLER_SKU", array_column($atributos1, 'id'));
							if ($ki1== false){
							$sku1="ssku";
							}else{
							$sku1=$mlresponde6[0]['attributes'][$ki1]['value_name'];
							}
			}else{
				$sku1=$sellercustomfield1;
			}
					$estoque1=$mlresponde6[0]['available_quantity'];
					$id2=$mlresponde6[1]['id'];
					$sellercustomfield2=$mlresponde6[1]['seller_custom_field'];
				if ($sellercustomfield2== NULL){
					$atributos2=$mlresponde6[1]['attributes'];
					$ki2=array_search("SELLER_SKU", array_column($atributos2, 'id'));
							if ($ki2== false){
							$sku2="ssku";
							}else{
							$sku2=$mlresponde6[1]['attributes'][$ki2]['value_name'];
							}
			}else{
				$sku2=$sellercustomfield2;
			}
					$estoque2=$mlresponde6[1]['available_quantity'];
					$id3=$mlresponde6[2]['id'];
					$sellercustomfield3=$mlresponde6[2]['seller_custom_field'];
				if ($sellercustomfield3== NULL){
					$atributos3=$mlresponde6[2]['attributes'];
					$ki3=array_search("SELLER_SKU", array_column($atributos3, 'id'));
							if ($ki3== false){
							$sku3="ssku";
							}else{
							$sku3=$mlresponde6[2]['attributes'][$ki3]['value_name'];
							}
			}else{
				$sku3=$sellercustomfield3;
			}
					$estoque3=$mlresponde6[2]['available_quantity'];
					$id4=$mlresponde6[3]['id'];
					$sellercustomfield4=$mlresponde6[3]['seller_custom_field'];
				if ($sellercustomfield4== NULL){
					$atributos4=$mlresponde6[3]['attributes'];
					$ki4=array_search("SELLER_SKU", array_column($atributos4, 'id'));
							if ($ki4== false){
							$sku4="ssku";
							}else{
							$sku4=$mlresponde6[3]['attributes'][$ki4]['value_name'];
							}
			}else{
				$sku4=$sellercustomfield4;
			}
					$estoque4=$mlresponde6[3]['available_quantity'];
					$id5=$mlresponde6[4]['id'];
					 $sellercustomfield5=$mlresponde6[4]['seller_custom_field'];
				if ($sellercustomfield5== NULL){
					$atributos5=$mlresponde6[4]['attributes'];
					$ki5=array_search("SELLER_SKU", array_column($atributos5, 'id'));
							if ($ki5== false){
							$sku5="ssku";
							}else{
							$sku5=$mlresponde6[4]['attributes'][$ki5]['value_name'];
							}
			}else{
				$sku5=$sellercustomfield5;
			}
					$estoque5=$mlresponde6[4]['available_quantity'];
					$id6=$mlresponde6[5]['id'];
					$sellercustomfield6=$mlresponde6[5]['seller_custom_field'];
				if ($sellercustomfield6== NULL){
					$atributos6=$mlresponde6[5]['attributes'];
					$ki6=array_search("SELLER_SKU", array_column($atributos6, 'id'));
							if ($ki6== false){
							$sku6="ssku";
							}else{
							$sku6=$mlresponde6[5]['attributes'][$ki6]['value_name'];
							}
			}else{
				$sku6=$sellercustomfield6;
			}
					$estoque6=$mlresponde6[5]['available_quantity'];
					$id7=$mlresponde6[6]['id'];
					                            $sellercustomfield7=$mlresponde6[5]['seller_custom_field'];
				if ($sellercustomfield7== NULL){
					$atributos7=$mlresponde6[5]['attributes'];
					$ki7=array_search("SELLER_SKU", array_column($atributos7, 'id'));
							if ($ki7== false){
							$sku7="ssku";
							}else{
							$sku7=$mlresponde6[5]['attributes'][$ki7]['value_name'];
							}
			}else{
				$sku7=$sellercustomfield7;
			}
					$estoque7=$mlresponde6[6]['available_quantity'];
					$id8=$mlresponde6[7]['id'];
					                                $sellercustomfield8=$mlresponde6[7]['seller_custom_field'];
				if ($sellercustomfield8== NULL){
					$atributos8=$mlresponde6[7]['attributes'];
					$ki8=array_search("SELLER_SKU", array_column($atributos8, 'id'));
							if ($ki8== false){
							$sku8="ssku";
							}else{
							$sku8=$mlresponde6[7]['attributes'][$ki8]['value_name'];
							}
				}else{
				$sku8=$sellercustomfield8;
			}
					$estoque8=$mlresponde6[7]['available_quantity'];
					mysqli_query($link10,"INSERT INTO meliprodutos (mlb,varia,variation,sku,estoque,status) VALUES ('$mlbbuscavaria','S','$id1','$sku1','$estoque1','OK'),('$mlbbuscavaria','S','$id2','$sku2','$estoque2','OK'),('$mlbbuscavaria','S','$id3','$sku3','$estoque3','OK'),('$mlbbuscavaria','S','$id4','$sku4','$estoque4','OK'),('$mlbbuscavaria','S','$id5','$sku5','$estoque5','OK'),('$mlbbuscavaria','S','$id6','$sku6','$estoque6','OK'),('$mlbbuscavaria','S','$id7','$sku7','$estoque7','OK'),('$mlbbuscavaria','S','$id8','$sku8','$estoque8','OK')") OR die("Error:".mysql_error());
					//header("Refresh:2");
					//echo "$idbuscavaria <br>";
					echo "ID: $mlbbuscavaria<br>";
					echo "SKU: $sku1 <br>";
					echo "SKU: $sku2 <br>";
					echo "SKU: $sku3 <br>";
					echo "SKU: $sku4 <br>";
					echo "SKU: $sku5 <br>";
					echo "SKU: $sku6 <br>";
					echo "SKU: $sku7 <br>";
					echo "SKU: $sku8 <br>";
					echo "<a href='sobe-ml.php'>Voltar</a>";
					echo "Encontrado anúncio com oito variações. Inserido:";
					die("8");	
					
				}
				if ($contagem== 9){
																				$id1=$mlresponde6[0]['id'];
					$sellercustomfield1=$mlresponde6[0]['seller_custom_field'];
				if ($sellercustomfield1== NULL){
					$atributos1=$mlresponde6[0]['attributes'];
					$ki1=array_search("SELLER_SKU", array_column($atributos1, 'id'));
							if ($ki1== false){
							$sku1="ssku";
							}else{
							$sku1=$mlresponde6[0]['attributes'][$ki1]['value_name'];
							}
			}else{
				$sku1=$sellercustomfield1;
			}
					$estoque1=$mlresponde6[0]['available_quantity'];
					$id2=$mlresponde6[1]['id'];
					$sellercustomfield2=$mlresponde6[1]['seller_custom_field'];
				if ($sellercustomfield2== NULL){
					$atributos2=$mlresponde6[1]['attributes'];
					$ki2=array_search("SELLER_SKU", array_column($atributos2, 'id'));
							if ($ki2== false){
							$sku2="ssku";
							}else{
							$sku2=$mlresponde6[1]['attributes'][$ki2]['value_name'];
							}
			}else{
				$sku2=$sellercustomfield2;
			}
					$estoque2=$mlresponde6[1]['available_quantity'];
					$id3=$mlresponde6[2]['id'];
					$sellercustomfield3=$mlresponde6[2]['seller_custom_field'];
				if ($sellercustomfield3== NULL){
					$atributos3=$mlresponde6[2]['attributes'];
					$ki3=array_search("SELLER_SKU", array_column($atributos3, 'id'));
							if ($ki3== false){
							$sku3="ssku";
							}else{
							$sku3=$mlresponde6[2]['attributes'][$ki3]['value_name'];
							}
			}else{
				$sku3=$sellercustomfield3;
			}
					$estoque3=$mlresponde6[2]['available_quantity'];
					$id4=$mlresponde6[3]['id'];
					$sellercustomfield4=$mlresponde6[3]['seller_custom_field'];
				if ($sellercustomfield4== NULL){
					$atributos4=$mlresponde6[3]['attributes'];
					$ki4=array_search("SELLER_SKU", array_column($atributos4, 'id'));
							if ($ki4== false){
							$sku4="ssku";
							}else{
							$sku4=$mlresponde6[3]['attributes'][$ki4]['value_name'];
							}
			}else{
				$sku4=$sellercustomfield4;
			}
					$estoque4=$mlresponde6[3]['available_quantity'];
					$id5=$mlresponde6[4]['id'];
					 $sellercustomfield5=$mlresponde6[4]['seller_custom_field'];
				if ($sellercustomfield5== NULL){
					$atributos5=$mlresponde6[4]['attributes'];
					$ki5=array_search("SELLER_SKU", array_column($atributos5, 'id'));
							if ($ki5== false){
							$sku5="ssku";
							}else{
							$sku5=$mlresponde6[4]['attributes'][$ki5]['value_name'];
							}
			}else{
				$sku5=$sellercustomfield5;
			}
					$estoque5=$mlresponde6[4]['available_quantity'];
					$id6=$mlresponde6[5]['id'];
					$sellercustomfield6=$mlresponde6[5]['seller_custom_field'];
				if ($sellercustomfield6== NULL){
					$atributos6=$mlresponde6[5]['attributes'];
					$ki6=array_search("SELLER_SKU", array_column($atributos6, 'id'));
							if ($ki6== false){
							$sku6="ssku";
							}else{
							$sku6=$mlresponde6[5]['attributes'][$ki6]['value_name'];
							}
			}else{
				$sku6=$sellercustomfield6;
			}
					$estoque6=$mlresponde6[5]['available_quantity'];
					$id7=$mlresponde6[6]['id'];
					                            $sellercustomfield7=$mlresponde6[5]['seller_custom_field'];
				if ($sellercustomfield7== NULL){
					$atributos7=$mlresponde6[5]['attributes'];
					$ki7=array_search("SELLER_SKU", array_column($atributos7, 'id'));
							if ($ki7== false){
							$sku7="ssku";
							}else{
							$sku7=$mlresponde6[5]['attributes'][$ki7]['value_name'];
							}
			}else{
				$sku7=$sellercustomfield7;
			}
					$estoque7=$mlresponde6[6]['available_quantity'];
					$id8=$mlresponde6[7]['id'];
					                                $sellercustomfield8=$mlresponde6[7]['seller_custom_field'];
				if ($sellercustomfield8== NULL){
					$atributos8=$mlresponde6[7]['attributes'];
					$ki8=array_search("SELLER_SKU", array_column($atributos8, 'id'));
							if ($ki8== false){
							$sku8="ssku";
							}else{
							$sku8=$mlresponde6[7]['attributes'][$ki8]['value_name'];
							}
				}else{
				$sku8=$sellercustomfield8;
			}
					$estoque8=$mlresponde6[7]['available_quantity'];
					$id9=$mlresponde6[8]['id'];
					$sellercustomfield9=$mlresponde6[8]['seller_custom_field'];
				if ($sellercustomfield9== NULL){
					$atributos9=$mlresponde6[8]['attributes'];
					$ki9=array_search("SELLER_SKU", array_column($atributos9, 'id'));
							if ($ki9== false){
							$sku9="ssku";
							}else{
							$sku9=$mlresponde6[8]['attributes'][$ki9]['value_name'];
							}
				}else{
				$sku9=$sellercustomfield9;
			}
					$estoque9=$mlresponde6[8]['available_quantity'];
					mysqli_query($link10,"INSERT INTO meliprodutos (mlb,varia,variation,sku,estoque,status) VALUES ('$mlbbuscavaria','S','$id1','$sku1','$estoque1','OK'),('$mlbbuscavaria','S','$id2','$sku2','$estoque2','OK'),('$mlbbuscavaria','S','$id3','$sku3','$estoque3','OK'),('$mlbbuscavaria','S','$id4','$sku4','$estoque4','OK'),('$mlbbuscavaria','S','$id5','$sku5','$estoque5','OK'),('$mlbbuscavaria','S','$id6','$sku6','$estoque6','OK'),('$mlbbuscavaria','S','$id7','$sku7','$estoque7','OK'),('$mlbbuscavaria','S','$id8','$sku8','$estoque8','OK'),('$mlbbuscavaria','S','$id9','$sku9','$estoque9','OK')") OR die("Error:".mysql_error());
					//header("Refresh:2");
					//echo "$idbuscavaria <br>";
					echo "ID: $mlbbuscavaria<br>";
					echo "SKU: $sku1 <br>";
					echo "SKU: $sku2 <br>";
					echo "SKU: $sku3 <br>";
					echo "SKU: $sku4 <br>";
					echo "SKU: $sku5 <br>";
					echo "SKU: $sku6 <br>";
					echo "SKU: $sku7 <br>";
					echo "SKU: $sku8 <br>";
					echo "SKU: $sku9 <br>";
					echo "<a href='sobe-ml.php'>Voltar</a>";
					echo "Encontrado anúncio com nove variações. Inserido:";
					die("9");	
					
				}
				if ($contagem== 10){
																									$id1=$mlresponde6[0]['id'];
					$sellercustomfield1=$mlresponde6[0]['seller_custom_field'];
				if ($sellercustomfield1== NULL){
					$atributos1=$mlresponde6[0]['attributes'];
					$ki1=array_search("SELLER_SKU", array_column($atributos1, 'id'));
							if ($ki1== false){
							$sku1="ssku";
							}else{
							$sku1=$mlresponde6[0]['attributes'][$ki1]['value_name'];
							}
			}else{
				$sku1=$sellercustomfield1;
			}
					$estoque1=$mlresponde6[0]['available_quantity'];
					$id2=$mlresponde6[1]['id'];
					$sellercustomfield2=$mlresponde6[1]['seller_custom_field'];
				if ($sellercustomfield2== NULL){
					$atributos2=$mlresponde6[1]['attributes'];
					$ki2=array_search("SELLER_SKU", array_column($atributos2, 'id'));
							if ($ki2== false){
							$sku2="ssku";
							}else{
							$sku2=$mlresponde6[1]['attributes'][$ki2]['value_name'];
							}
			}else{
				$sku2=$sellercustomfield2;
			}
					$estoque2=$mlresponde6[1]['available_quantity'];
					$id3=$mlresponde6[2]['id'];
					$sellercustomfield3=$mlresponde6[2]['seller_custom_field'];
				if ($sellercustomfield3== NULL){
					$atributos3=$mlresponde6[2]['attributes'];
					$ki3=array_search("SELLER_SKU", array_column($atributos3, 'id'));
							if ($ki3== false){
							$sku3="ssku";
							}else{
							$sku3=$mlresponde6[2]['attributes'][$ki3]['value_name'];
							}
			}else{
				$sku3=$sellercustomfield3;
			}
					$estoque3=$mlresponde6[2]['available_quantity'];
					$id4=$mlresponde6[3]['id'];
					$sellercustomfield4=$mlresponde6[3]['seller_custom_field'];
				if ($sellercustomfield4== NULL){
					$atributos4=$mlresponde6[3]['attributes'];
					$ki4=array_search("SELLER_SKU", array_column($atributos4, 'id'));
							if ($ki4== false){
							$sku4="ssku";
							}else{
							$sku4=$mlresponde6[3]['attributes'][$ki4]['value_name'];
							}
			}else{
				$sku4=$sellercustomfield4;
			}
					$estoque4=$mlresponde6[3]['available_quantity'];
					$id5=$mlresponde6[4]['id'];
					 $sellercustomfield5=$mlresponde6[4]['seller_custom_field'];
				if ($sellercustomfield5== NULL){
					$atributos5=$mlresponde6[4]['attributes'];
					$ki5=array_search("SELLER_SKU", array_column($atributos5, 'id'));
							if ($ki5== false){
							$sku5="ssku";
							}else{
							$sku5=$mlresponde6[4]['attributes'][$ki5]['value_name'];
							}
			}else{
				$sku5=$sellercustomfield5;
			}
					$estoque5=$mlresponde6[4]['available_quantity'];
					$id6=$mlresponde6[5]['id'];
					$sellercustomfield6=$mlresponde6[5]['seller_custom_field'];
				if ($sellercustomfield6== NULL){
					$atributos6=$mlresponde6[5]['attributes'];
					$ki6=array_search("SELLER_SKU", array_column($atributos6, 'id'));
							if ($ki6== false){
							$sku6="ssku";
							}else{
							$sku6=$mlresponde6[5]['attributes'][$ki6]['value_name'];
							}
			}else{
				$sku6=$sellercustomfield6;
			}
					$estoque6=$mlresponde6[5]['available_quantity'];
					$id7=$mlresponde6[6]['id'];
					                            $sellercustomfield7=$mlresponde6[5]['seller_custom_field'];
				if ($sellercustomfield7== NULL){
					$atributos7=$mlresponde6[5]['attributes'];
					$ki7=array_search("SELLER_SKU", array_column($atributos7, 'id'));
							if ($ki7== false){
							$sku7="ssku";
							}else{
							$sku7=$mlresponde6[5]['attributes'][$ki7]['value_name'];
							}
			}else{
				$sku7=$sellercustomfield7;
			}
					$estoque7=$mlresponde6[6]['available_quantity'];
					$id8=$mlresponde6[7]['id'];
					                                $sellercustomfield8=$mlresponde6[7]['seller_custom_field'];
				if ($sellercustomfield8== NULL){
					$atributos8=$mlresponde6[7]['attributes'];
					$ki8=array_search("SELLER_SKU", array_column($atributos8, 'id'));
							if ($ki8== false){
							$sku8="ssku";
							}else{
							$sku8=$mlresponde6[7]['attributes'][$ki8]['value_name'];
							}
				}else{
				$sku8=$sellercustomfield8;
			}
					$estoque8=$mlresponde6[7]['available_quantity'];
					$id9=$mlresponde6[8]['id'];
					$sellercustomfield9=$mlresponde6[8]['seller_custom_field'];
				if ($sellercustomfield9== NULL){
					$atributos9=$mlresponde6[8]['attributes'];
					$ki9=array_search("SELLER_SKU", array_column($atributos9, 'id'));
							if ($ki9== false){
							$sku9="ssku";
							}else{
							$sku9=$mlresponde6[8]['attributes'][$ki9]['value_name'];
							}
				}else{
				$sku9=$sellercustomfield9;
			}
					$estoque9=$mlresponde6[8]['available_quantity'];
					$id10=$mlresponde6[9]['id'];
					 $sellercustomfield10=$mlresponde6[9]['seller_custom_field'];
				if ($sellercustomfield10== NULL){
					$atributos10=$mlresponde6[9]['attributes'];
					$ki10=array_search("SELLER_SKU", array_column($atributos10, 'id'));
							if ($ki10== false){
							$sku10="ssku";
							}else{
							$sku10=$mlresponde6[9]['attributes'][$ki10]['value_name'];
							}
				}else{
				$sku10=$sellercustomfield10;
			}
					$estoque10=$mlresponde6[9]['available_quantity'];
					mysqli_query($link10,"INSERT INTO meliprodutos (mlb,varia,variation,sku,estoque,status) VALUES ('$mlbbuscavaria','S','$id1','$sku1','$estoque1','OK'),('$mlbbuscavaria','S','$id2','$sku2','$estoque2','OK'),('$mlbbuscavaria','S','$id3','$sku3','$estoque3','OK'),('$mlbbuscavaria','S','$id4','$sku4','$estoque4','OK'),('$mlbbuscavaria','S','$id5','$sku5','$estoque5','OK'),('$mlbbuscavaria','S','$id6','$sku6','$estoque6','OK'),('$mlbbuscavaria','S','$id7','$sku7','$estoque7','OK'),('$mlbbuscavaria','S','$id8','$sku8','$estoque8','OK'),('$mlbbuscavaria','S','$id9','$sku9','$estoque9','OK'),('$mlbbuscavaria','S','$id10','$sku10','$estoque10','OK')") OR die("Error:".mysql_error());
					//header("Refresh:2");
					//echo "$idbuscavaria <br>";
					echo "ID: $mlbbuscavaria<br>";
					echo "SKU: $sku1 <br>";
					echo "SKU: $sku2 <br>";
					echo "SKU: $sku3 <br>";
					echo "SKU: $sku4 <br>";
					echo "SKU: $sku5 <br>";
					echo "SKU: $sku6 <br>";
					echo "SKU: $sku7 <br>";
					echo "SKU: $sku8 <br>";
					echo "SKU: $sku9 <br>";
					echo "SKU: $sku10 <br>";
					echo "<a href='sobe-ml.php'>Voltar</a>";
					echo "Encontrado anúncio com dez variações. Inserido:";
					die("10");	
					
				}
				
				if ($contagem== 11){
																														$id1=$mlresponde6[0]['id'];
					$sellercustomfield1=$mlresponde6[0]['seller_custom_field'];
				if ($sellercustomfield1== NULL){
					$atributos1=$mlresponde6[0]['attributes'];
					$ki1=array_search("SELLER_SKU", array_column($atributos1, 'id'));
							if ($ki1== false){
							$sku1="ssku";
							}else{
							$sku1=$mlresponde6[0]['attributes'][$ki1]['value_name'];
							}
			}else{
				$sku1=$sellercustomfield1;
			}
					$estoque1=$mlresponde6[0]['available_quantity'];
					$id2=$mlresponde6[1]['id'];
					$sellercustomfield2=$mlresponde6[1]['seller_custom_field'];
				if ($sellercustomfield2== NULL){
					$atributos2=$mlresponde6[1]['attributes'];
					$ki2=array_search("SELLER_SKU", array_column($atributos2, 'id'));
							if ($ki2== false){
							$sku2="ssku";
							}else{
							$sku2=$mlresponde6[1]['attributes'][$ki2]['value_name'];
							}
			}else{
				$sku2=$sellercustomfield2;
			}
					$estoque2=$mlresponde6[1]['available_quantity'];
					$id3=$mlresponde6[2]['id'];
					$sellercustomfield3=$mlresponde6[2]['seller_custom_field'];
				if ($sellercustomfield3== NULL){
					$atributos3=$mlresponde6[2]['attributes'];
					$ki3=array_search("SELLER_SKU", array_column($atributos3, 'id'));
							if ($ki3== false){
							$sku3="ssku";
							}else{
							$sku3=$mlresponde6[2]['attributes'][$ki3]['value_name'];
							}
			}else{
				$sku3=$sellercustomfield3;
			}
					$estoque3=$mlresponde6[2]['available_quantity'];
					$id4=$mlresponde6[3]['id'];
					$sellercustomfield4=$mlresponde6[3]['seller_custom_field'];
				if ($sellercustomfield4== NULL){
					$atributos4=$mlresponde6[3]['attributes'];
					$ki4=array_search("SELLER_SKU", array_column($atributos4, 'id'));
							if ($ki4== false){
							$sku4="ssku";
							}else{
							$sku4=$mlresponde6[3]['attributes'][$ki4]['value_name'];
							}
			}else{
				$sku4=$sellercustomfield4;
			}
					$estoque4=$mlresponde6[3]['available_quantity'];
					$id5=$mlresponde6[4]['id'];
					 $sellercustomfield5=$mlresponde6[4]['seller_custom_field'];
				if ($sellercustomfield5== NULL){
					$atributos5=$mlresponde6[4]['attributes'];
					$ki5=array_search("SELLER_SKU", array_column($atributos5, 'id'));
							if ($ki5== false){
							$sku5="ssku";
							}else{
							$sku5=$mlresponde6[4]['attributes'][$ki5]['value_name'];
							}
			}else{
				$sku5=$sellercustomfield5;
			}
					$estoque5=$mlresponde6[4]['available_quantity'];
					$id6=$mlresponde6[5]['id'];
					$sellercustomfield6=$mlresponde6[5]['seller_custom_field'];
				if ($sellercustomfield6== NULL){
					$atributos6=$mlresponde6[5]['attributes'];
					$ki6=array_search("SELLER_SKU", array_column($atributos6, 'id'));
							if ($ki6== false){
							$sku6="ssku";
							}else{
							$sku6=$mlresponde6[5]['attributes'][$ki6]['value_name'];
							}
			}else{
				$sku6=$sellercustomfield6;
			}
					$estoque6=$mlresponde6[5]['available_quantity'];
					$id7=$mlresponde6[6]['id'];
					                            $sellercustomfield7=$mlresponde6[5]['seller_custom_field'];
				if ($sellercustomfield7== NULL){
					$atributos7=$mlresponde6[5]['attributes'];
					$ki7=array_search("SELLER_SKU", array_column($atributos7, 'id'));
							if ($ki7== false){
							$sku7="ssku";
							}else{
							$sku7=$mlresponde6[5]['attributes'][$ki7]['value_name'];
							}
			}else{
				$sku7=$sellercustomfield7;
			}
					$estoque7=$mlresponde6[6]['available_quantity'];
					$id8=$mlresponde6[7]['id'];
					                                $sellercustomfield8=$mlresponde6[7]['seller_custom_field'];
				if ($sellercustomfield8== NULL){
					$atributos8=$mlresponde6[7]['attributes'];
					$ki8=array_search("SELLER_SKU", array_column($atributos8, 'id'));
							if ($ki8== false){
							$sku8="ssku";
							}else{
							$sku8=$mlresponde6[7]['attributes'][$ki8]['value_name'];
							}
				}else{
				$sku8=$sellercustomfield8;
			}
					$estoque8=$mlresponde6[7]['available_quantity'];
					$id9=$mlresponde6[8]['id'];
					$sellercustomfield9=$mlresponde6[8]['seller_custom_field'];
				if ($sellercustomfield9== NULL){
					$atributos9=$mlresponde6[8]['attributes'];
					$ki9=array_search("SELLER_SKU", array_column($atributos9, 'id'));
							if ($ki9== false){
							$sku9="ssku";
							}else{
							$sku9=$mlresponde6[8]['attributes'][$ki9]['value_name'];
							}
				}else{
				$sku9=$sellercustomfield9;
			}
					$estoque9=$mlresponde6[8]['available_quantity'];
					$id10=$mlresponde6[9]['id'];
					 $sellercustomfield10=$mlresponde6[9]['seller_custom_field'];
				if ($sellercustomfield10== NULL){
					$atributos10=$mlresponde6[9]['attributes'];
					$ki10=array_search("SELLER_SKU", array_column($atributos10, 'id'));
							if ($ki10== false){
							$sku10="ssku";
							}else{
							$sku10=$mlresponde6[9]['attributes'][$ki10]['value_name'];
							}
				}else{
				$sku10=$sellercustomfield10;
			}
					$estoque10=$mlresponde6[9]['available_quantity'];
					$id11=$mlresponde6[10]['id'];
					 $sellercustomfield11=$mlresponde6[10]['seller_custom_field'];
				if ($sellercustomfield11== NULL){
					$atributos11=$mlresponde6[10]['attributes'];
					$ki11=array_search("SELLER_SKU", array_column($atributos11, 'id'));
							if ($ki11== false){
							$sku11="ssku";
							}else{
							$sku11=$mlresponde6[10]['attributes'][$ki11]['value_name'];
							}
				}else{
				$sku11=$sellercustomfield11;
			}
					$estoque11=$mlresponde6[10]['available_quantity'];
					mysqli_query($link10,"INSERT INTO meliprodutos (mlb,varia,variation,sku,estoque,status) VALUES ('$mlbbuscavaria','S','$id1','$sku1','$estoque1','OK'),('$mlbbuscavaria','S','$id2','$sku2','$estoque2','OK'),('$mlbbuscavaria','S','$id3','$sku3','$estoque3','OK'),('$mlbbuscavaria','S','$id4','$sku4','$estoque4','OK'),('$mlbbuscavaria','S','$id5','$sku5','$estoque5','OK'),('$mlbbuscavaria','S','$id6','$sku6','$estoque6','OK'),('$mlbbuscavaria','S','$id7','$sku7','$estoque7','OK'),('$mlbbuscavaria','S','$id8','$sku8','$estoque8','OK'),('$mlbbuscavaria','S','$id9','$sku9','$estoque9','OK'),('$mlbbuscavaria','S','$id10','$sku10','$estoque10','OK'),('$mlbbuscavaria','S','$id11','$sku11','$estoque11','OK')") OR die("Error:".mysql_error());
					//header("Refresh:2");
					//echo "$idbuscavaria <br>";
					echo "ID: $mlbbuscavaria<br>";
					echo "SKU: $sku1 <br>";
					echo "SKU: $sku2 <br>";
					echo "SKU: $sku3 <br>";
					echo "SKU: $sku4 <br>";
					echo "SKU: $sku5 <br>";
					echo "SKU: $sku6 <br>";
					echo "SKU: $sku7 <br>";
					echo "SKU: $sku8 <br>";
					echo "SKU: $sku9 <br>";
					echo "SKU: $sku10 <br>";
					echo "SKU: $sku11 <br>";
					echo "<a href='sobe-ml.php'>Voltar</a>";
					echo "Encontrado anúncio com onze variações. Inserido:";
					die("11");	
					
				}
				
				if ($contagem== 12){
																																			$id1=$mlresponde6[0]['id'];
					$sellercustomfield1=$mlresponde6[0]['seller_custom_field'];
				if ($sellercustomfield1== NULL){
					$atributos1=$mlresponde6[0]['attributes'];
					$ki1=array_search("SELLER_SKU", array_column($atributos1, 'id'));
							if ($ki1== false){
							$sku1="ssku";
							}else{
							$sku1=$mlresponde6[0]['attributes'][$ki1]['value_name'];
							}
			}else{
				$sku1=$sellercustomfield1;
			}
					$estoque1=$mlresponde6[0]['available_quantity'];
					$id2=$mlresponde6[1]['id'];
					$sellercustomfield2=$mlresponde6[1]['seller_custom_field'];
				if ($sellercustomfield2== NULL){
					$atributos2=$mlresponde6[1]['attributes'];
					$ki2=array_search("SELLER_SKU", array_column($atributos2, 'id'));
							if ($ki2== false){
							$sku2="ssku";
							}else{
							$sku2=$mlresponde6[1]['attributes'][$ki2]['value_name'];
							}
			}else{
				$sku2=$sellercustomfield2;
			}
					$estoque2=$mlresponde6[1]['available_quantity'];
					$id3=$mlresponde6[2]['id'];
					$sellercustomfield3=$mlresponde6[2]['seller_custom_field'];
				if ($sellercustomfield3== NULL){
					$atributos3=$mlresponde6[2]['attributes'];
					$ki3=array_search("SELLER_SKU", array_column($atributos3, 'id'));
							if ($ki3== false){
							$sku3="ssku";
							}else{
							$sku3=$mlresponde6[2]['attributes'][$ki3]['value_name'];
							}
			}else{
				$sku3=$sellercustomfield3;
			}
					$estoque3=$mlresponde6[2]['available_quantity'];
					$id4=$mlresponde6[3]['id'];
					$sellercustomfield4=$mlresponde6[3]['seller_custom_field'];
				if ($sellercustomfield4== NULL){
					$atributos4=$mlresponde6[3]['attributes'];
					$ki4=array_search("SELLER_SKU", array_column($atributos4, 'id'));
							if ($ki4== false){
							$sku4="ssku";
							}else{
							$sku4=$mlresponde6[3]['attributes'][$ki4]['value_name'];
							}
			}else{
				$sku4=$sellercustomfield4;
			}
					$estoque4=$mlresponde6[3]['available_quantity'];
					$id5=$mlresponde6[4]['id'];
					 $sellercustomfield5=$mlresponde6[4]['seller_custom_field'];
				if ($sellercustomfield5== NULL){
					$atributos5=$mlresponde6[4]['attributes'];
					$ki5=array_search("SELLER_SKU", array_column($atributos5, 'id'));
							if ($ki5== false){
							$sku5="ssku";
							}else{
							$sku5=$mlresponde6[4]['attributes'][$ki5]['value_name'];
							}
			}else{
				$sku5=$sellercustomfield5;
			}
					$estoque5=$mlresponde6[4]['available_quantity'];
					$id6=$mlresponde6[5]['id'];
					$sellercustomfield6=$mlresponde6[5]['seller_custom_field'];
				if ($sellercustomfield6== NULL){
					$atributos6=$mlresponde6[5]['attributes'];
					$ki6=array_search("SELLER_SKU", array_column($atributos6, 'id'));
							if ($ki6== false){
							$sku6="ssku";
							}else{
							$sku6=$mlresponde6[5]['attributes'][$ki6]['value_name'];
							}
			}else{
				$sku6=$sellercustomfield6;
			}
					$estoque6=$mlresponde6[5]['available_quantity'];
					$id7=$mlresponde6[6]['id'];
					                            $sellercustomfield7=$mlresponde6[5]['seller_custom_field'];
				if ($sellercustomfield7== NULL){
					$atributos7=$mlresponde6[5]['attributes'];
					$ki7=array_search("SELLER_SKU", array_column($atributos7, 'id'));
							if ($ki7== false){
							$sku7="ssku";
							}else{
							$sku7=$mlresponde6[5]['attributes'][$ki7]['value_name'];
							}
			}else{
				$sku7=$sellercustomfield7;
			}
					$estoque7=$mlresponde6[6]['available_quantity'];
					$id8=$mlresponde6[7]['id'];
					                                $sellercustomfield8=$mlresponde6[7]['seller_custom_field'];
				if ($sellercustomfield8== NULL){
					$atributos8=$mlresponde6[7]['attributes'];
					$ki8=array_search("SELLER_SKU", array_column($atributos8, 'id'));
							if ($ki8== false){
							$sku8="ssku";
							}else{
							$sku8=$mlresponde6[7]['attributes'][$ki8]['value_name'];
							}
				}else{
				$sku8=$sellercustomfield8;
			}
					$estoque8=$mlresponde6[7]['available_quantity'];
					$id9=$mlresponde6[8]['id'];
					$sellercustomfield9=$mlresponde6[8]['seller_custom_field'];
				if ($sellercustomfield9== NULL){
					$atributos9=$mlresponde6[8]['attributes'];
					$ki9=array_search("SELLER_SKU", array_column($atributos9, 'id'));
							if ($ki9== false){
							$sku9="ssku";
							}else{
							$sku9=$mlresponde6[8]['attributes'][$ki9]['value_name'];
							}
				}else{
				$sku9=$sellercustomfield9;
			}
					$estoque9=$mlresponde6[8]['available_quantity'];
					$id10=$mlresponde6[9]['id'];
					 $sellercustomfield10=$mlresponde6[9]['seller_custom_field'];
				if ($sellercustomfield10== NULL){
					$atributos10=$mlresponde6[9]['attributes'];
					$ki10=array_search("SELLER_SKU", array_column($atributos10, 'id'));
							if ($ki10== false){
							$sku10="ssku";
							}else{
							$sku10=$mlresponde6[9]['attributes'][$ki10]['value_name'];
							}
				}else{
				$sku10=$sellercustomfield10;
			}
					$estoque10=$mlresponde6[9]['available_quantity'];
					$id11=$mlresponde6[10]['id'];
					 $sellercustomfield11=$mlresponde6[10]['seller_custom_field'];
				if ($sellercustomfield11== NULL){
					$atributos11=$mlresponde6[10]['attributes'];
					$ki11=array_search("SELLER_SKU", array_column($atributos11, 'id'));
							if ($ki11== false){
							$sku11="ssku";
							}else{
							$sku11=$mlresponde6[10]['attributes'][$ki11]['value_name'];
							}
				}else{
				$sku11=$sellercustomfield11;
			}
					$estoque11=$mlresponde6[10]['available_quantity'];
					$id12=$mlresponde6[11]['id'];
					$sellercustomfield12=$mlresponde6[11]['seller_custom_field'];
				if ($sellercustomfield12== NULL){
					$atributos12=$mlresponde6[11]['attributes'];
					$ki12=array_search("SELLER_SKU", array_column($atributos12, 'id'));
							if ($ki12== false){
							$sku12="ssku";
							}else{
							$sku12=$mlresponde6[11]['attributes'][$ki12]['value_name'];
							}
				}else{
				$sku12=$sellercustomfield12;
			}
					$estoque12=$mlresponde6[11]['available_quantity'];
					mysqli_query($link10,"INSERT INTO meliprodutos (mlb,varia,variation,sku,estoque,status) VALUES ('$mlbbuscavaria','S','$id1','$sku1','$estoque1','OK'),('$mlbbuscavaria','S','$id2','$sku2','$estoque2','OK'),('$mlbbuscavaria','S','$id3','$sku3','$estoque3','OK'),('$mlbbuscavaria','S','$id4','$sku4','$estoque4','OK'),('$mlbbuscavaria','S','$id5','$sku5','$estoque5','OK'),('$mlbbuscavaria','S','$id6','$sku6','$estoque6','OK'),('$mlbbuscavaria','S','$id7','$sku7','$estoque7','OK'),('$mlbbuscavaria','S','$id8','$sku8','$estoque8','OK'),('$mlbbuscavaria','S','$id9','$sku9','$estoque9','OK'),('$mlbbuscavaria','S','$id10','$sku10','$estoque10','OK'),('$mlbbuscavaria','S','$id11','$sku11','$estoque11','OK'),('$mlbbuscavaria','S','$id12','$sku12','$estoque12','OK')") OR die("Error:".mysql_error());
					//header("Refresh:2");
					//echo "$idbuscavaria <br>";
					echo "ID: $mlbbuscavaria<br>";
					echo "SKU: $sku1 <br>";
					echo "SKU: $sku2 <br>";
					echo "SKU: $sku3 <br>";
					echo "SKU: $sku4 <br>";
					echo "SKU: $sku5 <br>";
					echo "SKU: $sku6 <br>";
					echo "SKU: $sku7 <br>";
					echo "SKU: $sku8 <br>";
					echo "SKU: $sku9 <br>";
					echo "SKU: $sku10 <br>";
					echo "SKU: $sku11 <br>";
					echo "SKU: $sku12 <br>";
					echo "<a href='sobe-ml.php'>Voltar</a>";
					echo "Encontrado anúncio com doze variações. Inserido:";
					die("12");	
					
				}
				
				if ($contagem>= 13){
					//header("Refresh:2");
					echo "Mais de 12 variações! Contate o administrador!";
					echo "<a href='sobe-ml.php'>Voltar</a>";
				die("Muitas Variações.");	
	
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
    <title>Atualizador Base ML</title>
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
                        <h2>Adicionar Anúncio</h2>
                    </div>
                    <p>Digite o código no formato <b>MLB123456</b>. Lembre-se de consultar se o anúncio realmente não está na base <a href="consulta-anuncio.php" target="_blank">clicando aqui</a>.</p>
					<p>Para atualizar um anúncio é necessário retirar ele  da base <a href="consulta-anuncio.php" target="_blank">clicando aqui</a> e inserir novamente por este formulário.</p>
					  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					  <div class="col-md-6">
                        <div class="form-group <?php echo (!empty($mlbbuscavaria_err)) ? 'has-error' : ''; ?>">
                            <label>Código:</label>
                            <input type="text" name="mlbbuscavaria" class="form-control" value="<?php echo $mlbbuscavaria; ?>">
                            <span class="help-block"><?php echo $mlbbuscavaria_err;?></span>
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
