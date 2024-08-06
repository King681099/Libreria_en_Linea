<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirigir si no está autenticado
    exit();
}

// Conexión a la base de datos
$servername = "localhost";
$username = "tu_usuario";
$password = "tu_contraseña";
$dbname = "libreria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Agregar un libro al carrito
if (isset($_POST['add_to_cart'])) {
    $book_id = $_POST['book_id'];
    $quantity = $_POST['quantity'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO carrito (ID_usuario, ID_libro, cantidad) VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE cantidad = cantidad + ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $user_id, $book_id, $quantity, $quantity);
    $stmt->execute();
}

// Eliminar un libro del carrito
if (isset($_POST['remove_from_cart'])) {
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM carrito WHERE ID_usuario = ? AND ID_libro = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
}

// Recuperar libros disponibles
$sql = "SELECT id, título, autor, precio FROM libros";
$result_books = $conn->query($sql);

// Recuperar el carrito de compras
$sql = "SELECT b.título, c.cantidad, b.precio, (c.cantidad * b.precio) as total 
        FROM carrito c JOIN libros b ON c.ID_libro = b.id 
        WHERE c.ID_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result_cart = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Bienvenido a la Librería</h2>
    <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>

    <h3>Libros Disponibles</h3>
    <form action="index.php" method="post">
        <div class="form-group">
            <label for="book">Selecciona un libro:</label>
            <select class="form-control" id="book" name="book_id">
                <?php while ($row = $result_books->fetch_assoc()) { ?>
                    <option value="<?php echo $row['id']; ?>">
                        <?php echo $row['título'] . " - $" . $row['precio']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="quantity">Cantidad:</label>
            <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
        </div>
        <button type="submit" name="add_to_cart" class="btn btn-primary">Agregar al Carrito</button>
    </form>

    <h3>Carrito de Compras</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Título</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_cart->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['título']; ?></td>
                    <td><?php echo $row['cantidad']; ?></td>
                    <td>$<?php echo $row['precio']; ?></td>
                    <td>$<?php echo $row['total']; ?></td>
                    <td>
                        <form action="index.php" method="post" style="display:inline;">
                            <input type="hidden" name="book_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="remove_from_cart" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>