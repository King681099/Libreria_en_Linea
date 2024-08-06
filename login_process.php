<?php
session_start(); // Iniciar la sesión

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
$email = $_POST['email'];
$password = $_POST['password'];

// Consulta para verificar el usuario
$sql = "SELECT id, contraseña FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['contraseña'])) {
        $_SESSION['user_id'] = $row['id'];
        header("Location: index.php"); // Redirigir al inicio
        exit();
    } else {
        echo "Contraseña incorrecta.";
    }
} else {
    echo "Correo electrónico no encontrado.";
}

$stmt->close();
$conn->close();
?>