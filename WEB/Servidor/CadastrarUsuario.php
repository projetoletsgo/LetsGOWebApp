<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbletsgo";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['Nome']) && isset($_POST['SobreNome']) && isset($_POST['Email']) && isset($_POST['Senha']))
{
	//Get the POST variables
	$nome = $_POST['Nome'];
	$sobrenome = $_POST['SobreNome'];
	$email = $_POST['Email'];
	$senha = $_POST['Senha'];
	
	//Insere novo Usuario;
	$sql = 'INSERT INTO TB_USUARIO (CO_CIDADE, S_NOME, S_SOBRENOME, S_EMAIL, S_SENHA) VALUES (1, \''.$nome.'\',\''.$sobrenome.'\',\''.$email.'\',\''.$senha.'\')';
	if ($conn->query($sql) === TRUE) {
		$last_id = $conn->insert_id;
		echo $last_id;
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

?>