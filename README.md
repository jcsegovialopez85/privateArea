# Technical test for backends

Technical test for backends has been done with PHP Phalcon Framework.

> Requires PHP 5.6 or newer

## Install enviroment

### XAMP

- **Windows:** https://www.apachefriends.org/xampp-files/5.6.33/xampp-win32-5.6.33-0-VC11-installer.exe

- **MacOS:** https://sourceforge.net/projects/xampp/files/XAMPP%20Mac%20OS%20X/5.6.30/xampp-osx-5.6.30-1-installer.dmg/download

### Phalcon Framework

- https://docs.phalconphp.com/en/3.2/installation

### Configure Web server

- Add VirtualHost into **httpd-vhosts.conf** file:

```ApacheConf
<VirtualHost *:80>
	ServerName privatearea.lan
	DocumentRoot "/Applications/XAMPP/xamppfiles/htdocs/privateArea"

	<Directory "/Applications/XAMPP/xamppfiles/htdocs/privateArea">
			Options Indexes FollowSymLinks
			AllowOverride All
			Require all granted
	</Directory>
</VirtualHost>
```

- Restart apache service from XAMP Control panel.

### Add domain to hosts file

> 127.0.0.1 privateArea.lan

## Install dependencies

`$ composer install`

## Tests

Execute test from folder tests.

`../vendor/bin/phpunit`

## Troubleshooting

- If message "Volt directory can't be written" is shown when access to website:

Review apache has permissions to write in privateArea/cache/volt folder.

## Dockerize  
Execute:  
	docker run --name test -p 8080:80 -d nikeyes/test-tech 
	
Create Image:  
	docker image build -t test-tech .
	docker image tag test-tech nikeyes/test-tech
	docker image push nikeyes/test-tech
	