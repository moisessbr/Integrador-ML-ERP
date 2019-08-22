<?php
//Token do MercadoLivre
session_start();
$mltoken="$_SESSION[access_token]";

$link10 = mysqli_connect(""host","user","pass","bd");

$buscavaria = mysqli_query($link10,"SELECT * FROM melivaria WHERE busca='A' LIMIT 1");

	$dadosbuscavaria = mysqli_fetch_array($buscavaria,MYSQLI_ASSOC);

	$idbuscavaria=$dadosbuscavaria['id'];
	$mlbbuscavaria=$dadosbuscavaria['mlb'];
	

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
	
	$urlapi9="https://api.mercadolibre.com/items/$mlbbuscavaria?access_token=$mltoken";

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

//daqui<<<<<<<<<<<<<<<<<<<<<<<<<<<
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
//até aqui	>>>>>>>>>>>>>>>>>	

	//echo "Código do item: $id0";
	//echo "$response9";
	echo '<br />';
	echo "SKU: $sku0 <br>";
	mysqli_query($link10,"UPDATE melivaria SET sku='$sku0',varia='N', busca='B' WHERE id ='$idbuscavaria'");		
	header("Refresh:2");
	echo "$idbuscavaria <br>";
	echo "Sem variações..";
	die("Sem variações. Salvando SKU principal.");	
	
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
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', busca='B' WHERE id ='$idbuscavaria'");
	header("Refresh:2");
	echo "$idbuscavaria <br>";
	echo "Variações:";
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
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', busca='B' WHERE id ='$idbuscavaria'");
	header("Refresh:2");
	echo "Variações:";
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
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', sku3='$sku3', var3='$id3', busca='B' WHERE id ='$idbuscavaria'");
	header("Refresh:2");
	echo "Variações:";
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
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', sku3='$sku3', var3='$id3', sku4='$sku4', var4='$id4', busca='B' WHERE id ='$idbuscavaria'");
	header("Refresh:2");
	echo "Variações:";
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
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', sku3='$sku3', var3='$id3', sku4='$sku4', var4='$id4', sku5='$sku5', var5='$id5', busca='B' WHERE id ='$idbuscavaria'");
	header("Refresh:2");
	echo "Variações:";
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
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', sku3='$sku3', var3='$id3', sku4='$sku4', var4='$id4', sku5='$sku5', var5='$id5', sku6='$sku6', var6='$id6',busca='B' WHERE id ='$idbuscavaria'");
	header("Refresh:2");
	echo "Variações:";
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
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', sku3='$sku3', var3='$id3', sku4='$sku4', var4='$id4', sku5='$sku5', var5='$id5', sku6='$sku6', var6='$id6', sku7='$sku7', var7='$id7', busca='B' WHERE id ='$idbuscavaria'");
	header("Refresh:2");
	echo "Variações:";
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
	$id7=$mlresponde6[6]['id'];
                            $sellercustomfield7=$mlresponde6[6]['seller_custom_field'];
				if ($sellercustomfield7== NULL){
					$atributos7=$mlresponde6[6]['attributes'];
					$ki7=array_search("SELLER_SKU", array_column($atributos7, 'id'));
							if ($ki7== false){
							$sku7="ssku";
							}else{
							$sku7=$mlresponde6[6]['attributes'][$ki7]['value_name'];
							}
			}else{
				$sku7=$sellercustomfield7;
			}
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
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', sku3='$sku3', var3='$id3', sku4='$sku4', var4='$id4', sku5='$sku5', var5='$id5', sku6='$sku6', var6='$id6', sku7='$sku7', var7='$id7', sku8='$sku8', var8='$id8', busca='B' WHERE id ='$idbuscavaria'");
	header("Refresh:2");
	echo "Variações:";
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
	$id7=$mlresponde6[6]['id'];
                            $sellercustomfield7=$mlresponde6[6]['seller_custom_field'];
				if ($sellercustomfield7== NULL){
					$atributos7=$mlresponde6[6]['attributes'];
					$ki7=array_search("SELLER_SKU", array_column($atributos7, 'id'));
							if ($ki7== false){
							$sku7="ssku";
							}else{
							$sku7=$mlresponde6[6]['attributes'][$ki7]['value_name'];
							}
			}else{
				$sku7=$sellercustomfield7;
			}
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
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', sku3='$sku3', var3='$id3', sku4='$sku4', var4='$id4', sku5='$sku5', var5='$id5', sku6='$sku6', var6='$id6', sku7='$sku7', var7='$id7', sku8='$sku8', var8='$id8', sku9='$sku9', var9='$id9', busca='B' WHERE id ='$idbuscavaria'");
	header("Refresh:2");
	echo "Variações:";
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
	$id7=$mlresponde6[6]['id'];
                            $sellercustomfield7=$mlresponde6[6]['seller_custom_field'];
				if ($sellercustomfield7== NULL){
					$atributos7=$mlresponde6[6]['attributes'];
					$ki7=array_search("SELLER_SKU", array_column($atributos7, 'id'));
							if ($ki7== false){
							$sku7="ssku";
							}else{
							$sku7=$mlresponde6[6]['attributes'][$ki7]['value_name'];
							}
			}else{
				$sku7=$sellercustomfield7;
			}
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
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', sku3='$sku3', var3='$id3', sku4='$sku4', var4='$id4', sku5='$sku5', var5='$id5', sku6='$sku6', var6='$id6', sku7='$sku7', var7='$id7', sku8='$sku8', var8='$id8', sku9='$sku9', var9='$id9', sku10='$sku10', var10='$id10', busca='B' WHERE id ='$idbuscavaria'");
	header("Refresh:2");
	echo "Variações:";
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
	$id7=$mlresponde6[6]['id'];
                            $sellercustomfield7=$mlresponde6[6]['seller_custom_field'];
				if ($sellercustomfield7== NULL){
					$atributos7=$mlresponde6[6]['attributes'];
					$ki7=array_search("SELLER_SKU", array_column($atributos7, 'id'));
							if ($ki7== false){
							$sku7="ssku";
							}else{
							$sku7=$mlresponde6[6]['attributes'][$ki7]['value_name'];
							}
			}else{
				$sku7=$sellercustomfield7;
			}
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
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', sku3='$sku3', var3='$id3', sku4='$sku4', var4='$id4', sku5='$sku5', var5='$id5', sku6='$sku6', var6='$id6', sku7='$sku7', var7='$id7', sku8='$sku8', var8='$id8', sku9='$sku9', var9='$id9', sku10='$sku10', var10='$id10', sku11='$sku11', var11='$id11', busca='B' WHERE id ='$idbuscavaria'");
	header("Refresh:2");
	echo "Variações:";
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
	$id7=$mlresponde6[6]['id'];
                            $sellercustomfield7=$mlresponde6[6]['seller_custom_field'];
				if ($sellercustomfield7== NULL){
					$atributos7=$mlresponde6[6]['attributes'];
					$ki7=array_search("SELLER_SKU", array_column($atributos7, 'id'));
							if ($ki7== false){
							$sku7="ssku";
							}else{
							$sku7=$mlresponde6[6]['attributes'][$ki7]['value_name'];
							}
			}else{
				$sku7=$sellercustomfield7;
			}
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
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', sku3='$sku3', var3='$id3', sku4='$sku4', var4='$id4', sku5='$sku5', var5='$id5', sku6='$sku6', var6='$id6', sku7='$sku7', var7='$id7', sku8='$sku8', var8='$id8', sku9='$sku9', var9='$id9', sku10='$sku10', var10='$id10', sku11='$sku11', var11='$id11',  sku12='$sku12', var12='$id12', busca='B' WHERE id ='$idbuscavaria'");
	header("Refresh:2");
	echo "Variações:";
	die("12");	
	
}

if ($contagem>= 13){
	header("Refresh:2");
	echo "Mais de 12 variações! Contate o administrador!";
	die("Muitas Variações.");	
	
}




//$id=$mlresponde6[0]['id'];
//$estoque=$mlresponde6[0]['seller_custom_field'];
//$id2=$mlresponde6[1]['id'];
//$estoque2=$mlresponde6[1]['seller_custom_field'];


// $result == 3

//echo "$response6";
//echo "<br>";
//echo "$id";
//echo "<br>";
//echo "$estoque";
//echo "<br>";
//echo "$id2";
//echo "<br>";
//echo "$estoque2";
//echo "<br>";
//echo "$contagem";
?>