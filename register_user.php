<?php
include 'db_connect.php';

// Recuperar datos del formulario
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); // Hashear la contraseña
$dirección = $_POST['dirección'];
$teléfono = $_POST['teléfono'];

// Insertar datos en la tabla de usuarios
$sql = "INSERT INTO USUARIOS (nombre, email, contraseña, dirección, teléfono) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $nombre, $email, $contraseña, $dirección, $teléfono);

if ($stmt->execute()) {
    echo "Usuario registrado con éxito.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>