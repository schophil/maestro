# maestro
A small PHP web content management system

Websites are defined by folder under `src/public`. An example website is provided in `src/public/example`.

A maestro website always consists of:
1. A `conductor.conf.php` file in the root of the website folder. This file defines the pages and flows.
2. A `persistency.conf.php` file in the root of the website folder. This file defines the database model used. 
The example website defines 2 common data types: documents and events.
3. One or several page PHP files. These files are the actual pages of the website.

The file 'src/public/index.php' is the entry point for all requests. 
This file loads the conductor and persistency modules and should not be adapted.

## Configuration
The configuration is done through `.env` files. And example can be found under `src/public/dot_env`. 
The configuration contains:
1. Database connection details.
2. The root password of the website administration tool.

## Development

### Running a database
A docker file is provided to create a database for development. To run it, use the following commands.
To build the image:
```bash
$ cd docker/database
$ docker build -t maestro/database .
```

To run the database:
```bash
docker run --detach --name maestrodb \
--env MARIADB_USER=maestro_user \
--env MARIADB_PASSWORD=maestro \
--env MARIADB_DATABASE=maestrodb \
--env MARIADB_ROOT_PASSWORD=maestro_root \
maestro/database
```

### Running the website

The website can be started using the PHP cli. 
The following command will start the website on port 8080.

```bash
$ cd src/public
$ php -S localhost:8080
```

## Deployment on apache
For apache you need to enable the rewrite module and add the following to your virtual host configuration:

```
php_flag display_errors on
RewriteEngine on

# Safe the authorization header to be sure it is kept after the rewrite
RewriteCond %{HTTP:Authorization} ^(.*)
RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]

# Rewrite to ./src/public
RewriteCond %{HTTP_HOST} ^<YOUR DOMAIN>$ [NC,OR]
RewriteCond %{HTTP_HOST} ^<YOUR DOMAIN>$
RewriteCond %{REQUEST_URI} !src/public/
RewriteRule (.*) /src/public/$1 [QSA,L]
```