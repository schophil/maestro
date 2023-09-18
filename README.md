# maestro
A small PHP web content management system

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