<IfModule mod_rewrite.c>
   <IfModule mod_negotiation.c>
       Options -MultiViews
   </IfModule>
   RewriteEngine On
   # Redirect Trailing Slashes If Not A Folder...
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteRule ^(.*)/$ /$1 [L,R=301]
   # Handle Front Controller...
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteRule ^ index.php [L]
   # Handle Authorization Header
   RewriteCond %{HTTP:Authorization} .
   RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
</IfModule>

# BEGIN cPanel-generated php ini directives, do not edit
# Manual editing of this file may result in unexpected behavior.
# To make changes to this file, use the cPanel MultiPHP INI Editor (Home >> Software >> MultiPHP INI Editor)
# For more information, read our documentation (https://go.cpanel.net/EA4ModifyINI)
<IfModule php5_module>
  php_value post_max_size 200M
  php_value max_execution_time 120
  php_flag asp_tags Off
  php_flag display_errors Off
  php_value max_input_time 60
  php_value max_input_vars 1000
  php_value memory_limit 64M
  php_value session.gc_maxlifetime 1440
  php_value session.save_path "/var/cpanel/php/sessions/ea-php56"
  php_value upload_max_filesize 200M
  php_flag zlib.output_compression Off
</IfModule>
# END cPanel-generated php ini directives, do not edit