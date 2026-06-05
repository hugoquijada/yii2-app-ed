<p align="center">
    <a href="https://edesarrollos.com" target="_blank">
        <img src="https://edesarrollos.com/img/logo.png" height="100px">
    </a>
</p>

# yii2-app-ed

Plantilla base de eDesarrollos sobre Yii2. Este proyecto consume `edesarrollos/yii2-ed` para exponer APIs REST, autenticacion JWT, exportacion de reportes, correo y documentacion OpenAPI con Scalar.

## Requisitos

- PHP `>= 7.4`
- Yii2 `^2.0.54`
- Base de datos configurada en `config/db.php`

## Instalacion

Para crear un proyecto nuevo:

```bash
composer create-project --prefer-dist edesarrollos/yii2-app-ed
```

Si ya estas dentro del proyecto y solo quieres instalar dependencias:

```bash
composer install
```

Si es una instalacion nueva, el proyecto ejecuta scripts para:

- generar la llave JWT local
- ajustar permisos base

## Estructura principal

- `config/`: configuracion web, consola, exportacion, correo y OpenAPI
- `comandos/`: comandos del proyecto
- `modelos/`: modelos de aplicacion
- `modulos/v1/`: API principal
- `modulos/p/`: endpoints adicionales
- `mail/`: vistas de correo del proyecto
- `publico/`: punto de entrada web y archivos generados
- `migraciones/`: migraciones

## Configuracion importante

### JWT

La llave JWT se carga desde `/.jwt-key`. Si el archivo no existe, se usa el valor por defecto definido en `config/params.php`.

### Correo

El mailer usa `yiisoft/yii2-symfonymailer` y toma su configuracion desde `config/correo.php`.

- `viewPath` apunta a `@app/mail`
- el layout base esta en `mail/layouts/html.php`
- el modulo `mail` esta disponible en web y consola

### Exportacion

La configuracion global vive en `config/exportacion.php`.

Formatos soportados:

- `json`
- `xml`
- `html`
- `csv`
- `xlsx`
- `pdf`
- `docx`

## Convencion REST

Los controladores REST heredan de:

- `eDesarrollos\rest\JsonController`
- `eDesarrollos\rest\AuthController`

Convencion base:

- `GET /recurso.json` -> `actionIndex()`
- `POST /recurso.json` -> `actionPost()`
- `PUT /recurso.json` -> `actionPut()`
- `DELETE /recurso.json` -> `actionDelete()`

Ejemplos:

```text
/v1/usuario.json
/v1/usuario.csv
/v1/usuario.xlsx
/v1/usuario.pdf
/v1/usuario.docx
```

Para exportar todos los registros en formatos documentales, puedes usar:

```text
/v1/usuario.xlsx?todo=1
/v1/usuario.pdf?todo=1
/v1/usuario.docx?todo=1
/v1/usuario.csv?todo=1
```

## OpenAPI y Scalar

La configuracion esta en `config/openapi.php`.

Para generar la documentacion:

```bash
php yii openapi/generar
```

Archivos generados:

- `publico/openapi.json`
- `publico/scalar.html`

## Correo programado

```bash
php yii mail/cron
```

## Comandos utiles

```bash
php yii migrate
php yii openapi/generar
php yii mail/cron
```

## Notas

- `config/db.php` se configura por entorno.
- `urlManager` usa `eDesarrollos\rest\UrlManager`.
- las respuestas HTTP de error se transforman al formato estandar de `eDesarrollos\data\Respuesta`
