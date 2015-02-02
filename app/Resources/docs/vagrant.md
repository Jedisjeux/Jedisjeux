
1/ create a file 

``` bash
/etc/apache2/sites-available/jedisjeux.symfony.conf
```

2/ copy this lines :

<VirtualHost *:80>
	ServerName jedisjeux.symfony
	ServerAlias jedisjeux.symfony
	DocumentRoot /home/vagrant/www/jdj/web
 	<Directory /home/vagrant/www/jdj/web/>
		DirectoryIndex index.php
		AllowOverride All
		Order allow,deny
		Allow from all
	</Directory>

	ErrorLog ${APACHE_LOG_DIR}/jedisjeux-error.log

	# Possible values include: debug, info, notice, warn, error, crit,
	# alert, emerg.
	LogLevel warn

	CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>


3/ run this lines :
``` bash
    sudo a2ensite jedisjeux.symfony
    sudo service apache2 restart
```