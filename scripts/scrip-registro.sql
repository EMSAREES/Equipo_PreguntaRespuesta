create database sistemajornada;

use sistemajornada;


-- Tabla de los roles de los usuarios
CREATE TABLE Rol (
Id_Rol INT PRIMARY KEY AUTO_INCREMENT,
Descripcion VARCHAR(20) NOT NULL UNIQUE
);

-- Tabla de la información de los usuarios
CREATE TABLE Usuario (
Id_Usu INT PRIMARY KEY AUTO_INCREMENT,
Nombre_Usu VARCHAR(60) NOT NULL,
Apellidos_Usu VARCHAR(80) NOT NULL,
Telefono_Usu CHAR(10) NOT NULL,
Matricula_Usu CHAR(8),
Correo_Usu VARCHAR(254) NOT NULL UNIQUE,
Contrasena_Usu VARCHAR(255) NOT NULL,
Carrera VARCHAR(40) NOT NULL,
Semestre CHAR(1),
Grupo CHAR(2),
Rol_Usu INT,
status ENUM('pendiente', 'activo', 'inactivo') DEFAULT 'pendiente',
Foto_Perfil VARCHAR(255),
FOREIGN KEY (Rol_Usu) REFERENCES Rol(Id_Rol)
	ON UPDATE CASCADE
	ON DELETE RESTRICT
);


-- Tabla para los tokens de verificación
CREATE TABLE verification_tokens (
    email VARCHAR(254) NOT NULL,
    token VARCHAR(64) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (email),
    UNIQUE KEY (token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;