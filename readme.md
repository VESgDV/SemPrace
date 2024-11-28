Routing


add to xampp\apache\conf\extra in httpd-vhosts.conf the next code

<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/semprace"
    ServerName localhost
    <Directory "C:/xampp/htdocs/semprace">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
