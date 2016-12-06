<?php

$conn = new mysqli('localhost', 'root', '', 'dbletsgo');

if ($conn->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

$funcoes = ['logar','cadastrarUsuario'];

if(in_array($_POST['tipo'], $funcoes)){
	echo($_POST['tipo']($conn));
}else{
	echo 'Tipo não encontrado!';
}

$conn->close();

function logar($conn){
	if($_POST['s_email'] && $_POST['s_senha']){
		$senha = $_POST['s_senha'];
		$email = $_POST['s_email'];
		$sql = "SELECT * FROM TB_USUARIO WHERE S_EMAIL = '$email' AND S_SENHA = '$senha'";
		$result = $conn->query($sql);
		return json_encode(mysqli_fetch_assoc($result));
	}else{
		return "Dados invalidos";
	}
}

function cadastrarUsuario($conn){
	if($_GET['co_cidade'] && $_GET['s_nome'] && $_GET['s_sobrenome'] && $_GET['s_email'] &&
		$_GET['s_senha'] && $_GET['dt_nascimento'] && $_GET['s_rg'] && $_GET['s_cpf']){

		$cidade = $_GET['co_cidade'];
		$nome = $_GET['s_nome'];
		$sobrenome = $_GET['s_sobrenome'];
		$email = $_GET['s_email'];
		$senha = $_GET['s_senha'];
		$dtNasc = $_GET['dt_nascimento'];
		$rg = $_GET['s_rg'];
		$cpf = $_GET['s_cpf'];

		$sql = "INSERT INTO TB_USUARIO (CO_CIDADE, S_NOME, S_SOBRENOME, S_EMAIL, S_SENHA, DT_NASCIMENTO, S_RG, S_CPF)".
			"VALUES ($cidade, '$nome', '$sobrenome', '$email', '$senha', '$dtNasc', '$rg,?', '$cpf')";

		return json_encode($conn->query($sql));
	}else{
		return "Dados invalidos";
	}
}

function cadastrarExcursao($conn){
	if($_GET['s_nome'] && $_GET['co_usuario'] && $_GET['f_preco']){

		$nome = $_GET['s_nome'];
		$user = $_GET['co_usuario'];
		$valor = $_GET['f_preco'];

		$sql = "INSERT INTO TB_EXCURSAO (S_NOME, CO_USUARIO, F_PRECO)".
			"VALUES ('$nome', $user, $valor)";

		return json_encode($conn->query($sql));
	}else{
		return "Dados invalidos";
	}
}

function queryToJson($result){
	$rows = array();
	while($r = mysqli_fetch_assoc($result)) {
	    $rows[] = $r;
	}
	return json_encode(mysqli_fetch_assoc($result));
}

function db($a){
	echo '<pre>';
	var_dump($a);
	echo '</pre>';
}

function dbd($a){
	db($a);
	die();
}