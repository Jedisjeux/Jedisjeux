# Description
This configuration includes following software:

* PHP 5.4.19 
* MySQL 5.5.32
* GIT 1.7.9.5
* Apache 2.2.22
* Vim
* MC (Midnight commander)
* Curl
* Composer
* Sass/Compass
* Solr

# Usage

First you need to create vagrant VM

```
$ cd vagrant
$ vagrant up
```

While waiting for Vagrant to start up, you should add an entry into /etc/hosts file on the host machine.

```
10.0.0.210      cezembre.dev
```

You can access the following services on the subsequent urls :
Tomcat : [http://cezembre.dev:8080/manager/html]http://cezembre.dev:8080/manager/html (user : tomcat / pass : tomcat)
Solr : [http://cezembre.dev:8080/solr]http://cezembre.dev:8080/solr
MySQL : [http://localhost:33306/]mysql -hlocalhost -P33306 -u root -p

To get shell access to the VM, use

```
$ vagrant ssh
```

Then end cezembre installation with
```
$ cd /var/www/cezembre
$ app/console doctrine:schema:update
$ app/console doctrine:phpcr:repository:init
$ app/console doctrine:fixtures:load --fixtures=src/Pharmagest/Bundle/ImportBundle/DataFixtures

```

@TODO : add ruby standalone data loading script

From now you should be able to access your Cezembre project at [http://cezembre.dev/](http://cezembre.dev/)

# Troubleshooting

Using Symfony2 inside Vagrant can be slow due to synchronisation delay incurred by NFS. To avoid this, both locations have been moved to a shared memory segment under ``/dev/shm/sylius``.

To view the application logs, run the following commands:

```bash
$ tail -f /dev/shm/sylius/app/logs/prod.log
$ tail -f /dev/shm/sylius/app/logs/dev.log
```

# Extending a model

Adding field to a model has to be done un CoreBundle and requires theses steps :
- add field definition in Resource/config/doctrine/model/[Product].orm.xml

```xml
<field name="viewCount" column="view_count" type="integer" nullable="true"/>
```

- add Property, Accessors and Mutators Methods prototypes in Model Interface

```php
    /**
     * Get product view count.
     *
     * @return int
     */
    public function getViewCount();
    
    /**
     * Set product view count.
     *
     * @param int $viewCount
    */
    public function setViewCount($viewCount);
```

- Define model property and implement Accessors and Mutators Methods in Model

```php
    /**
     * Count visits of this product.
     *
     * @var int
     */
    protected $viewCount;

    /**
     * {@inheritdoc}
     */
    public function getViewCount()
    {
        return $this->viewCount;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setViewCount($viewCount)
    {
        $this->viewCount = $viewCount;
        
        return $this;
    }
```

- add (if necessary) the form element in the Form Type

```php
    $builder
            ->add('viewCount', 'number', array(
                'label'       => 'sylius.form.product.view_count',
            ))
    ;
```

- run the database schema update :

``` bash
app/console doctrine:schema:update
```

- and edit the twig templates in WebBundle to use the new property


To finish the setup :
Restart services :
 
``` bash
sudo service tomcat7 restart
sudo service apache2 restart
```

Run composer : 
``` bash
cd /var/www
composer install
```

Load schema in databases :
``` bash
mysql -u root -p < dump.sql
```

Add right in databases :
``` bash
mysql -u root -p
SET PASSWORD FOR 'root'@'localhost' = PASSWORD('mobizel');
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'mobizel' WITH GRANT OPTION;
FLUSH PRIVILEGES;
exit
```

Change bind port :
``` bash
sudo vi /etc/mysql/my.cnf 
bind 0.0.0.0
```

