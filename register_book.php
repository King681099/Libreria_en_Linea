<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "tu_usuario";
$password = "tu_contraseña";
$dbname = "libreria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recuperación de datos del formulario
$title = $_POST['title'];
$author = $_POST['author'];
$price = $_POST['price'];
$quantity = $_POST['quantity'];

// Inserción de datos en la base de datos
$sql = "INSERT INTO libros (título, autor, precio, cantidad) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdi", $title, $author, $price, $quantity);

if ($stmt->execute()) {
    echo "Libro registrado exitosamente.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$stmt->close();
$conn->close();
?>/
