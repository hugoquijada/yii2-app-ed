<p align="center">
    <a href="https://edesarrollos.com" target="_blank">
        <img src="https://edesarrollos.com/img/logo.png" height="100px">
    </a>
    <h1 align="center">Extensión de eDesarrollos para Yii 2</h1>
    <br>
</p>

Proyecto Base de eDesarrollos
=============================

Instalación
-----------

Para instalar el proyecto, utiliza el siguiente comando:

```bash
composer create-project --prefer-dist edesarrollos/yii2-app-ed
```

Servidor Web de Desarrollo
--------------------------

Para iniciar el servidor local:

```bash
php index.php
```

Estructura de Configuración
---------------------------

- `config/params.php`: Contiene la configuración general. La `jwt.key` se lee dinámicamente desde el archivo secreto local.
- `config/db.php`: Configuración de la base de datos (ignorado por Git, debe configurarse por entorno).
