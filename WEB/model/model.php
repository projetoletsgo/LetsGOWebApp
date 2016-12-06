<?php
include ('../connection.php');

$funcoes = ['logar','cadastrarUsuario','cadastrarExcursao'];
if(in_array($_POST['tipo'], $funcoes)){
	echo $_POST['tipo']();
}
else
{
	echo 'Tipo nao encontrado!';
	dbd($_REQUEST);
}

$conn->close();

function logar(){

	global $conn;
	session_start();
   
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		// username and password sent from form 

		$email = mysqli_real_escape_string($conn,$_POST['s_email']);
		$senha = mysqli_real_escape_string($conn,$_POST['s_senha']); 

		$sql = "SELECT CO_SEQ_USUARIO FROM TB_USUARIO WHERE S_EMAIL = '$email' AND S_SENHA = '$senha'";
		$result = mysqli_query($conn,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$active = $row['CO_SEQ_USUARIO'];

		$count = mysqli_num_rows($result);

		// If result matched $myusername and $mypassword, table row must be 1 row

		if($count == 1) {
			session_register("email");
			$_SESSION['login_user'] = $email;
			header("location: minhasViagens.php");
		}else {
			$error = "Your Login Name or Password is invalid";
		}
   }

}

function cadastrarUsuario(){
	global $conn;
	$cidade = $_POST['s_cidade'];
	$estado = $_POST['s_estado'];
	$nome = $_POST['s_nome'];
	$sobrenome = $_POST['s_sobrenome'];
	$email = $_POST['s_email'];
	$senha = $_POST['s_senha'];
	$dtNasc = $_POST['dt_nascimento'];
	$rg = $_POST['s_rg'];
	$cpf = $_POST['s_cpf'];

	$co_cidade = inserirCidade($estado, $cidade);
	
	$sql = "INSERT INTO TB_USUARIO (CO_CIDADE, S_NOME, S_SOBRENOME, S_EMAIL, S_SENHA, DT_NASCIMENTO, S_RG, S_CPF)".
		"VALUES ($co_cidade, '$nome', '$sobrenome', '$email', '$senha', '$dtNasc', '$rg', '$cpf')";
	
	$conn->query($sql);
	$id = $conn->insert_id

	return $id;
}

function cadastrarExcursao(){
	global $conn;
	//dbd($_POST);
	if($_POST['nome_evento'] && $_POST['valor_evento']){

		$user = 1;
		$valor = preg_replace('/[,]/', '.', $_POST['valor_evento']);

		$co_excursao = inserirExcursao($_POST['nome_evento'], $user, $valor);

		$co_chat = inserirChat(NULL, $co_excursao, NULL);

		inserirChatUsuario($co_chat, $user);

		$local = $_POST['local'];
		$data = $_POST['data'];
		$cidade = $_POST['cidade'];
		$obs = $_POST['obs'];
		$idaVolta = $_POST['idaVolta'];

		for($i = 0; $i<= count($local); $i++){
			if($idaVolta[$i] == '1'){
				$co_cidade = inserirCidade(NULL, $cidade[$i]);
				inserirParada($co_cidade, $data[$i], $obs[$i], $co_excursao, $idaVolta[$i]);
			}
		}

		$co_cidade = inserirCidade(NULL, $_POST['cidade_evento']);
		inserirParada($co_cidade, $_POST['data_evento'], $_POST['obs_evento'], $co_excursao, 1);

		for($i = 0; $i < count($local); $i++){
			if($idaVolta[$i] != '1'){
				$co_cidade = inserirCidade(NULL, $cidade[$i]);
				inserirParada($co_cidade, $data[$i], $obs[$i], $co_excursao, 0);
			}
		}

		$qtdLugares = $_POST['qtdLugares'];
		for($i = 0; $i < count($local); $i++){
			$co_veiculo = inserirVeiculo($user, NULL, NULL, NULL, $qtdLugares[$i]);
			inserirVeiculoExcursao($co_veiculo, $co_excursao);
		}

		die('redirecionar');
	}else{
		return "Dados invalidos";
	}
}

function buscarUsuario(){
	global $conn;
	if($_POST['s_email'] && $_POST['s_senha']){
		$senha = $_POST['s_senha'];
		$email = $_POST['s_email'];
		$sql = "SELECT * FROM TB_USUARIO WHERE S_EMAIL = '$email' AND S_SENHA = '$senha'";
		$result = $conn->query($sql);
		$user = mysqli_fetch_assoc($result);
		$co_user = $user['CO_SEQ_USUARIO'];
		$user['excursoes_minhas'] = listarExcursoesMinhas($co_user);
		$user['excursoes_novas'] = listarExcursoesNovas($co_user);
		$user['excursoes_participando'] = listarExcursoesParticipando($co_user);
		$user['chats'] = listarChats($co_user);
		dbd($user);
		return json_encode($user);
	}else{
		return "Dados invalidos";
	}
}

function listarExcursoesNovas($co_usuario){
	global $conn;
	$sql = "SELECT * FROM TB_EXCURSAO WHERE CO_SEQ_EXCURSAO NOT IN (SELECT CO_EXCURSAO FROM TB_PARTICIPA_EXCURSAO WHERE CO_USUARIO = $co_usuario);";
	return mysqli_fetch_assoc($conn->query($sql));
}

function listarExcursoesParticipando($co_usuario){
	global $conn;
	$sql = "SELECT * FROM TB_EXCURSAO WHERE CO_SEQ_EXCURSAO IN (SELECT CO_EXCURSAO FROM TB_PARTICIPA_EXCURSAO WHERE CO_USUARIO = $co_usuario);";
	return mysqli_fetch_assoc($conn->query($sql));
}

function listarExcursoesMinhas($co_usuario){
	global $conn;
	$sql = "SELECT * FROM TB_EXCURSAO WHERE CO_USUARIO = $co_usuario;";
	return mysqli_fetch_assoc($conn->query($sql));
}

function listarChats($co_usuario){
	global $conn;
	$sql = "SELECT * FROM TB_CHAT WHERE CO_SEQ_CHAT IN (SELECT CO_CHAT FROM TB_CHAT_USUARIO WHERE CO_USUARIO = $co_usuario);";
	return mysqli_fetch_assoc($conn->query($sql));
}

function inserirExcursao($nome, $user, $valor){
	global $conn;
	$sql = "INSERT INTO TB_EXCURSAO (S_NOME, CO_USUARIO, F_PRECO)".
			"VALUES ('$nome', $user, $valor);";
	db($sql);
	$conn->query($sql);
	return ($conn->insert_id);
}

function inserirChat($co_usuario='NULL', $co_excursao='NULL', $nome = 'NULL'){
	global $conn;
	$sql = "INSERT INTO TB_CHAT (CO_USUARIO, CO_EXCURSAO, S_NOME)".
			"VALUES (NULL, $co_excursao, NULL);";
	db($sql);
	$conn->query($sql);
	return ($conn->insert_id);
}

function inserirChatUsuario($co_chat, $user){
	global $conn;
	$sql = "INSERT INTO TB_CHAT_USUARIO (CO_CHAT, CO_USUARIO)".
			"VALUES ($co_chat, $user);";
	db($sql);
	$conn->query($sql);
	return ($conn->insert_id);
}

function inserirCidade($estado = 'SP', $cidade){
	global $conn;
	$sql ="INSERT INTO TB_CIDADE(S_ESTADO, S_NOME) VALUES ('SP','$cidade');";
	db($sql);
	$conn->query($sql);
	return ($conn->insert_id);
}

function inserirParada($co_cidade, $data, $obs, $co_excursao, $idaVolta){
	global $conn;
	$sql = "INSERT INTO TB_PARADA (CO_CIDADE, DT_MOMENTO, S_OBS, CO_EXCURSAO, IS_IDA)".
				"VALUES ($co_cidade, $data, '$obs', $co_excursao, $idaVolta);";
	db($sql);
	$conn->query($sql);
	return ($conn->insert_id);
}

function inserirVeiculo($co_usuario, $marca='', $placa='', $cor='', $qtd){
	global $conn;
	$sql = "INSERT INTO TB_VEICULO (CO_USUARIO, S_MARCA, S_PLACA, S_COR, I_QTD_LUGARES)".
				"VALUES ($co_usuario, '$marca', '$placa', '$cor', $qtd);";
	db($sql);
	$conn->query($sql);
	return ($conn->insert_id);
}

function inserirVeiculoExcursao($co_veiculo, $co_excursao){
	global $conn;
	$sql = "INSERT INTO TB_VEICULO_EXCURSAO (CO_VEICULO, CO_EXCURSAO)".
				"VALUES ($co_veiculo, $co_excursao);";
	db($sql);
	$conn->query($sql);
	return ($conn->insert_id);
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