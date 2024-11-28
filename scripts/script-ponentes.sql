CREATE TABLE ponentes (
  id_ponente INT(11) NOT NULL AUTO_INCREMENT,
  Nombre_ponente VARCHAR(100) NOT NULL,
  Apellido_ponente VARCHAR(100) NOT NULL,
  Correo_ponente VARCHAR(200) NOT NULL,
  Telefono_ponente VARCHAR(15) NOT NULL,
  Fecha_ponente DATE NOT NULL,
  Descripcion_ponente TEXT NOT NULL,
  Tema_ponente VARCHAR(100) NOT NULL,
  PRIMARY KEY (id_ponente)
);