# Introduction #

Everybody runs linux/apache/whatever differently so I thought it would be wise for me to drop a little note here to say how I develop.  I run a XAMPP installation on a Macbook.  This means I am assuming you have the following installed

  * PHP 5.2+
  * Apache 2.x
  * Mysql 5.x

## Apache Config ##

  * Uncomment the VirtualHosts reference in httpd.conf
  * Edit the httpd-vhosts.conf file and enter something like this

```
<VirtualHost *>
    DocumentRoot /path/to/code/html/public
    ServerName recipes.local
    <Directory /path/to/code/html/public>
        Order deny,allow
        Allow from all
        DirectoryIndex index.php index.html
        AllowOverride All
    </Directory>
    ErrorLog /path/to/code/logs/error_log
    CustomLog /path/to/code/logs/access_log common
</VirtualHost>
```

  * Restart apache
  * Edit your hosts file (/etc/hosts on a Mac)
  * Enter an entry for recipes.local

## MySQL ##

  * Create a database with a sensible name (I use recipe\_development)
  * Import the schema (in the docs directory schema.sql)

## Application ##

  * Edit the config.xml
  * Change the 

&lt;local&gt;

 set of entries so they correspond with your dev environment
  * For linix/mac chmod 777 the cache dir
  * Create a log file in accordance with what you set in the config
  * For linix/mac chmod 777 the log file