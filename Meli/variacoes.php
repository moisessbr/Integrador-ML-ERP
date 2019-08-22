<?php
//Token do MercadoLivre
session_start();
$mltoken="$_SESSION[access_token]";

$link10 = mysqli_connect("mysql857.umbler.com","2ahanupr","?2{*}26ObouSh","fr8phust");

$buscavaria = mysqli_query($link10,"SELECT * FROM melivaria WHERE busca='A' LIMIT 1");

	$dadosbuscavaria = mysqli_fetch_array($buscavaria,MYSQLI_ASSOC);

	$idbuscavaria=$dadosbuscavaria['id'];
	$mlbbuscavaria=$dadosbuscavaria['mlb'];
	

$urlapi6="https://api.mercadolibre.com/items/$mlbbuscavaria/variations?access_token=$mltoken";

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

    $sku0=$mlresponde6['seller_custom_field'];
	//echo "Código do item: $id0";
	echo "$response9";
	echo '<br />';
	echo "Estoque do banco: $sku0";
		
	//mysqli_query($link10,"UPDATE melivaria SET status='NOO' WHERE id ='$idbuscavaria'");
	//header("Refresh:5");
	//echo "Sem variações..";
	//die("NOO");	
	
}

if ($contagem== 1){
	$id1=$mlresponde6[0]['id'];
    $sku1=$mlresponde6[0]['seller_custom_field'];
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', busca='B' WHERE id ='$idbuscavaria'");
	//header("Refresh:5");
	echo "Uma variação!";
	die("1");	
	
}

if ($contagem== 2){
	$id1=$mlresponde6[0]['id'];
    $sku1=$mlresponde6[0]['seller_custom_field'];
	$id2=$mlresponde6[1]['id'];
    $sku2=$mlresponde6[1]['seller_custom_field'];
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', busca='B' WHERE id ='$idbuscavaria'");
	//header("Refresh:5");
	echo "Uma variação!";
	die("2");	
	
}

if ($contagem== 3){
	$id1=$mlresponde6[0]['id'];
    $sku1=$mlresponde6[0]['seller_custom_field'];
	$id2=$mlresponde6[1]['id'];
    $sku2=$mlresponde6[1]['seller_custom_field'];
	$id3=$mlresponde6[2]['id'];
    $sku3=$mlresponde6[2]['seller_custom_field'];
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', sku3='$sku3', var3='$id3', busca='B' WHERE id ='$idbuscavaria'");
	//header("Refresh:5");
	echo "Uma variação!";
	die("3");	
	
}

if ($contagem== 4){
	$id1=$mlresponde6[0]['id'];
    $sku1=$mlresponde6[0]['seller_custom_field'];
	$id2=$mlresponde6[1]['id'];
    $sku2=$mlresponde6[1]['seller_custom_field'];
	$id3=$mlresponde6[2]['id'];
    $sku3=$mlresponde6[2]['seller_custom_field'];
	$id4=$mlresponde6[3]['id'];
    $sku4=$mlresponde6[3]['seller_custom_field'];
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', sku3='$sku3', var3='$id3', sku4='$sku4', var4='$id4', busca='B' WHERE id ='$idbuscavaria'");
	//header("Refresh:5");
	echo "Uma variação!";
	die("4");	
	
}

if ($contagem== 5){
	$id1=$mlresponde6[0]['id'];
    $sku1=$mlresponde6[0]['seller_custom_field'];
	$id2=$mlresponde6[1]['id'];
    $sku2=$mlresponde6[1]['seller_custom_field'];
	$id3=$mlresponde6[2]['id'];
    $sku3=$mlresponde6[2]['seller_custom_field'];
	$id4=$mlresponde6[3]['id'];
    $sku4=$mlresponde6[3]['seller_custom_field'];
	$id5=$mlresponde6[4]['id'];
    $sku5=$mlresponde6[4]['seller_custom_field'];
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', sku3='$sku3', var3='$id3', sku4='$sku4', var4='$id4', sku5='$sku5', var5='$id5', busca='B' WHERE id ='$idbuscavaria'");
	//header("Refresh:5");
	echo "Uma variação!";
	die("5");	
	
}

if ($contagem== 6){
	$id1=$mlresponde6[0]['id'];
    $sku1=$mlresponde6[0]['seller_custom_field'];
	$id2=$mlresponde6[1]['id'];
    $sku2=$mlresponde6[1]['seller_custom_field'];
	$id3=$mlresponde6[2]['id'];
    $sku3=$mlresponde6[2]['seller_custom_field'];
	$id4=$mlresponde6[3]['id'];
    $sku4=$mlresponde6[3]['seller_custom_field'];
	$id5=$mlresponde6[4]['id'];
    $sku5=$mlresponde6[4]['seller_custom_field'];
	$id6=$mlresponde6[5]['id'];
    $sku6=$mlresponde6[5]['seller_custom_field'];
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', sku3='$sku3', var3='$id3', sku4='$sku4', var4='$id4', sku5='$sku5', var5='$id5', sku6='$sku6', var6='$id6',busca='B' WHERE id ='$idbuscavaria'");
	//header("Refresh:5");
	echo "Uma variação!";
	die("6");	
	
}

if ($contagem== 7){
	$id1=$mlresponde6[0]['id'];
    $sku1=$mlresponde6[0]['seller_custom_field'];
	$id2=$mlresponde6[1]['id'];
    $sku2=$mlresponde6[1]['seller_custom_field'];
	$id3=$mlresponde6[2]['id'];
    $sku3=$mlresponde6[2]['seller_custom_field'];
	$id4=$mlresponde6[3]['id'];
    $sku4=$mlresponde6[3]['seller_custom_field'];
	$id5=$mlresponde6[4]['id'];
    $sku5=$mlresponde6[4]['seller_custom_field'];
	$id6=$mlresponde6[5]['id'];
    $sku6=$mlresponde6[5]['seller_custom_field'];
	$id7=$mlresponde6[6]['id'];
    $sku7=$mlresponde6[6]['seller_custom_field'];
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', sku3='$sku3', var3='$id3', sku4='$sku4', var4='$id4', sku5='$sku5', var5='$id5', sku6='$sku6', var6='$id6', sku7='$sku7', var7='$id7', busca='B' WHERE id ='$idbuscavaria'");
	//header("Refresh:5");
	echo "Uma variação!";
	die("7");	
	
}

if ($contagem== 8){
	$id1=$mlresponde6[0]['id'];
    $sku1=$mlresponde6[0]['seller_custom_field'];
	$id2=$mlresponde6[1]['id'];
    $sku2=$mlresponde6[1]['seller_custom_field'];
	$id3=$mlresponde6[2]['id'];
    $sku3=$mlresponde6[2]['seller_custom_field'];
	$id4=$mlresponde6[3]['id'];
    $sku4=$mlresponde6[3]['seller_custom_field'];
	$id5=$mlresponde6[4]['id'];
    $sku5=$mlresponde6[4]['seller_custom_field'];
	$id6=$mlresponde6[5]['id'];
    $sku6=$mlresponde6[5]['seller_custom_field'];
	$id7=$mlresponde6[6]['id'];
    $sku7=$mlresponde6[6]['seller_custom_field'];
	$id8=$mlresponde6[7]['id'];
    $sku8=$mlresponde6[7]['seller_custom_field'];
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', sku3='$sku3', var3='$id3', sku4='$sku4', var4='$id4', sku5='$sku5', var5='$id5', sku6='$sku6', var6='$id6', sku7='$sku7', var7='$id7', sku8='$sku8', var8='$id8', busca='B' WHERE id ='$idbuscavaria'");
	//header("Refresh:5");
	echo "Uma variação!";
	die("8");	
	
}

if ($contagem== 9){
	$id1=$mlresponde6[0]['id'];
    $sku1=$mlresponde6[0]['seller_custom_field'];
	$id2=$mlresponde6[1]['id'];
    $sku2=$mlresponde6[1]['seller_custom_field'];
	$id3=$mlresponde6[2]['id'];
    $sku3=$mlresponde6[2]['seller_custom_field'];
	$id4=$mlresponde6[3]['id'];
    $sku4=$mlresponde6[3]['seller_custom_field'];
	$id5=$mlresponde6[4]['id'];
    $sku5=$mlresponde6[4]['seller_custom_field'];
	$id6=$mlresponde6[5]['id'];
    $sku6=$mlresponde6[5]['seller_custom_field'];
	$id7=$mlresponde6[6]['id'];
    $sku7=$mlresponde6[6]['seller_custom_field'];
	$id8=$mlresponde6[7]['id'];
    $sku8=$mlresponde6[7]['seller_custom_field'];
	$id9=$mlresponde6[8]['id'];
    $sku9=$mlresponde6[8]['seller_custom_field'];
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', sku3='$sku3', var3='$id3', sku4='$sku4', var4='$id4', sku5='$sku5', var5='$id5', sku6='$sku6', var6='$id6', sku7='$sku7', var7='$id7', sku8='$sku8', var8='$id8', sku9='$sku9', var9='$id9', busca='B' WHERE id ='$idbuscavaria'");
	//header("Refresh:5");
	echo "Uma variação!";
	die("9");	
	
}

if ($contagem== 10){
	$id1=$mlresponde6[0]['id'];
    $sku1=$mlresponde6[0]['seller_custom_field'];
	$id2=$mlresponde6[1]['id'];
    $sku2=$mlresponde6[1]['seller_custom_field'];
	$id3=$mlresponde6[2]['id'];
    $sku3=$mlresponde6[2]['seller_custom_field'];
	$id4=$mlresponde6[3]['id'];
    $sku4=$mlresponde6[3]['seller_custom_field'];
	$id5=$mlresponde6[4]['id'];
    $sku5=$mlresponde6[4]['seller_custom_field'];
	$id6=$mlresponde6[5]['id'];
    $sku6=$mlresponde6[5]['seller_custom_field'];
	$id7=$mlresponde6[6]['id'];
    $sku7=$mlresponde6[6]['seller_custom_field'];
	$id8=$mlresponde6[7]['id'];
    $sku8=$mlresponde6[7]['seller_custom_field'];
	$id9=$mlresponde6[8]['id'];
    $sku9=$mlresponde6[8]['seller_custom_field'];
	$id10=$mlresponde6[9]['id'];
    $sku10=$mlresponde6[9]['seller_custom_field'];
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', sku3='$sku3', var3='$id3', sku4='$sku4', var4='$id4', sku5='$sku5', var5='$id5', sku6='$sku6', var6='$id6', sku7='$sku7', var7='$id7', sku8='$sku8', var8='$id8', sku9='$sku9', var9='$id9', sku10='$sku10', var10='$id10', busca='B' WHERE id ='$idbuscavaria'");
	//header("Refresh:5");
	echo "Uma variação!";
	die("10");	
	
}

if ($contagem== 11){
	$id1=$mlresponde6[0]['id'];
    $sku1=$mlresponde6[0]['seller_custom_field'];
	$id2=$mlresponde6[1]['id'];
    $sku2=$mlresponde6[1]['seller_custom_field'];
	$id3=$mlresponde6[2]['id'];
    $sku3=$mlresponde6[2]['seller_custom_field'];
	$id4=$mlresponde6[3]['id'];
    $sku4=$mlresponde6[3]['seller_custom_field'];
	$id5=$mlresponde6[4]['id'];
    $sku5=$mlresponde6[4]['seller_custom_field'];
	$id6=$mlresponde6[5]['id'];
    $sku6=$mlresponde6[5]['seller_custom_field'];
	$id7=$mlresponde6[6]['id'];
    $sku7=$mlresponde6[6]['seller_custom_field'];
	$id8=$mlresponde6[7]['id'];
    $sku8=$mlresponde6[7]['seller_custom_field'];
	$id9=$mlresponde6[8]['id'];
    $sku9=$mlresponde6[8]['seller_custom_field'];
	$id10=$mlresponde6[9]['id'];
    $sku10=$mlresponde6[9]['seller_custom_field'];
	$id11=$mlresponde6[10]['id'];
    $sku11=$mlresponde6[10]['seller_custom_field'];
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', sku3='$sku3', var3='$id3', sku4='$sku4', var4='$id4', sku5='$sku5', var5='$id5', sku6='$sku6', var6='$id6', sku7='$sku7', var7='$id7', sku8='$sku8', var8='$id8', sku9='$sku9', var9='$id9', sku10='$sku10', var10='$id10', sku11='$sku11', var11='$id11', busca='B' WHERE id ='$idbuscavaria'");
	//header("Refresh:5");
	echo "Uma variação!";
	die("11");	
	
}

if ($contagem== 12){
	$id1=$mlresponde6[0]['id'];
    $sku1=$mlresponde6[0]['seller_custom_field'];
	$id2=$mlresponde6[1]['id'];
    $sku2=$mlresponde6[1]['seller_custom_field'];
	$id3=$mlresponde6[2]['id'];
    $sku3=$mlresponde6[2]['seller_custom_field'];
	$id4=$mlresponde6[3]['id'];
    $sku4=$mlresponde6[3]['seller_custom_field'];
	$id5=$mlresponde6[4]['id'];
    $sku5=$mlresponde6[4]['seller_custom_field'];
	$id6=$mlresponde6[5]['id'];
    $sku6=$mlresponde6[5]['seller_custom_field'];
	$id7=$mlresponde6[6]['id'];
    $sku7=$mlresponde6[6]['seller_custom_field'];
	$id8=$mlresponde6[7]['id'];
    $sku8=$mlresponde6[7]['seller_custom_field'];
	$id9=$mlresponde6[8]['id'];
    $sku9=$mlresponde6[8]['seller_custom_field'];
	$id10=$mlresponde6[9]['id'];
    $sku10=$mlresponde6[9]['seller_custom_field'];
	$id11=$mlresponde6[10]['id'];
    $sku11=$mlresponde6[10]['seller_custom_field'];
	$id12=$mlresponde6[11]['id'];
    $sku12=$mlresponde6[11]['seller_custom_field'];
	mysqli_query($link10,"UPDATE melivaria SET varia='S', sku1='$sku1', var1='$id1', sku2='$sku2', var2='$id2', sku3='$sku3', var3='$id3', sku4='$sku4', var4='$id4', sku5='$sku5', var5='$id5', sku6='$sku6', var6='$id6', sku7='$sku7', var7='$id7', sku8='$sku8', var8='$id8', sku9='$sku9', var9='$id9', sku10='$sku10', var10='$id10', sku11='$sku11', var11='$id11',  sku12='$sku12', var12='$id12', busca='B' WHERE id ='$idbuscavaria'");
	//header("Refresh:5");
	echo "Uma variação!";
	die("12");	
	
}

if ($contagem>= 13){
	//header("Refresh:5");
	echo "Mais de 12 variações! Contate o administrador!";
	die("Muitas Variações.");	
	
}




$id=$mlresponde6[0]['id'];
$estoque=$mlresponde6[0]['seller_custom_field'];
$id2=$mlresponde6[1]['id'];
$estoque2=$mlresponde6[1]['seller_custom_field'];


// $result == 3

//echo "$response6";
echo "<br>";
echo "$id";
echo "<br>";
echo "$estoque";
echo "<br>";
echo "$id2";
echo "<br>";
echo "$estoque2";
echo "<br>";
echo "$contagem";
?>