<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Carrito de Compras</h2>

    <form action="cart_process.php" method="post">
        <div class="form-group">
            <label for="book">Selecciona un libro:</label>
            <select class="form-control" id="book" name="book_id">
                <?php
                include 'db_connect.php';
                $sql = "SELECT id, título, precio FROM LIBROS";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['título']} - $ {$row['precio']}</option>";
                }
                $conn->close();
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="quantity">Cantidad:</label>
            <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
        </div>
        <button type="submit" name="add_to_cart" class="btn btn-primary">Agregar al Carrito</button>
    </form>

    <h3>Contenido del Carrito</h3>
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
            <?php
            session_start();
            include 'db_connect.php';
            $user_id = $_SESSION['user_id'];

            $sql = "SELECT b.título, c.cantidad, b.precio, (c.cantidad * b.precio) as total 
                    FROM CARRITO c JOIN LIBROS b ON c.ID_libro = b.id 
                    WHERE c.ID_usuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['título']}</td>
                        <td>{$row['cantidad']}</td>
                        <td>$ {$row['precio']}</td>
                        <td>$ {$row['total']}</td>
                        <td>
                            <form action='cart_process.php' method='post' style='display:inline;'>
                                <input type='hidden' name='book_id' value='{$row['id']}'>
                                <button type='submit' name='remove_from_cart' class='btn btn-danger'>Eliminar</button>
                            </form>
                        </td>
                    </tr>";
            }

            $stmt->close();
            $conn->close();
            ?>
        </tbody>
    </table>
</div>
</body>
</html>