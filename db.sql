-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS LIBRERIA;

-- Seleccionar la base de datos
USE LIBRERIA;

-- Crear la tabla de usuarios
CREATE TABLE IF NOT EXISTS USUARIOS (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    dirección VARCHAR(255),
    teléfono VARCHAR(15)
);

-- Crear la tabla de libros
CREATE TABLE IF NOT EXISTS LIBROS (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    título VARCHAR(255) NOT NULL,
    autor VARCHAR(255) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    cantidad INT NOT NULL
);

-- Crear la tabla de carrito
CREATE TABLE IF NOT EXISTS CARRITO (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    ID_usuario INT NOT NULL,
    ID_libro INT NOT NULL,
    cantidad INT NOT NULL,
    monto_total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (ID_usuario) REFERENCES USUARIOS(ID),
    FOREIGN KEY (ID_libro) REFERENCES LIBROS(ID)
);