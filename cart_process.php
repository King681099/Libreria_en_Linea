<?php
session_start();
include 'db_connect.php';

$user_id = $_SESSION['user_id'];

if (isset($_POST['add_to_cart'])) {
    $book_id = $_POST['book_id'];
    $quantity = $_POST['quantity'];
    $total_amount = 0;

    // Verificar si el libro ya está en el carrito
    $sql = "SELECT cantidad, precio FROM CARRITO c JOIN LIBROS l ON c.ID_libro = l.id WHERE ID_usuario = ? AND c.ID_libro = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $new_quantity = $row['cantidad'] + $quantity;
        $total_amount = $new_quantity * $row['precio'];
        $sql = "UPDATE CARRITO SET cantidad = ?, monto_total = ? WHERE ID_usuario = ? AND ID_libro = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("idii", $new_quantity, $total_amount, $user_id, $book_id);
    } else {
        $sql = "SELECT precio FROM LIBROS WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $price = $result->fetch_assoc()['precio'];
        $total_amount = $quantity * $price;
        $sql = "INSERT INTO CARRITO (ID_usuario, ID_libro, cantidad, monto_total) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiid", $user_id, $book_id, $quantity, $total_amount);
    }
    $stmt->execute();
}

if (isset($_POST['remove_from_cart'])) {
    $book_id = $_POST['book_id'];
    $sql = "DELETE FROM CARRITO WHERE ID_usuario = ? AND ID_libro = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
}

$stmt->close();
$conn->close();
header("Location: cart.php"); // Redirigir al carrito
exit();
?>