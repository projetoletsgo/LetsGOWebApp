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

$sql = "SELECT * FROM tb_usuario";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table style=\"border:1px solid black\"><tr>".
        "<th>Id</th>".
        "<th>Nome</th>".
        "<th>Sobrenome</th>".
        "<th>Email</th>".
        "<th>Senha</th>".
        "</tr>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>".
            "<td>".$row["CO_SEQ_USUARIO"]."</td>".
            "<td>".$row["S_NOME"]."</td>".
            "<td>".$row["S_SOBRENOME"]."</td>".
            "<td>".$row["S_EMAIL"]."</td>".
            "<td>".$row["S_SENHA"]."</td>".
            "</tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}
$conn->close();