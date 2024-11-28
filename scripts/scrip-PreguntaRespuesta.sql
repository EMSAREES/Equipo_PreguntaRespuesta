-- Crear tabla tbl_Pregunta
CREATE TABLE Pregunta (
    Id_Pregunta INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Id_Autor INT,
    id_Tema INT,
    Pregunta TEXT NOT NULL,
    Contexto TEXT,
    Hora TIME,
    Fecha DATE,
    Estado TINYINT(1), -- Modificado para ser booleano
    FOREIGN KEY (Id_Autor) REFERENCES Usuario(Id_Usu),
    FOREIGN KEY (id_Tema) REFERENCES ponentes(id_ponente)
);