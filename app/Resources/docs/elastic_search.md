1 - a/ Mac installation

launch these commands :
``` bash
brew update
brew install elasticsearch
```

After elastica search is installed
launch this command :
``` bash
curl brew info elasticsearch
```

And then follow all the commands shown to lanch elastica

1 - b/ Linux installation

If you don't have openjdk-7-jre installed
launch this command :
``` bash
cd ~
sudo apt-get update
sudo apt-get install openjdk-7-jre-headless -y
```

Now to download and install elastic search
``` bash
wget https://download.elasticsearch.org/elasticsearch/elasticsearch/elasticsearch-1.3.2.deb
sudo dpkg -i elasticsearch-1.3.2.deb
```

2/ starting service
``` bash
sudo update-rc.d elasticsearch defaults 95 10
sudo /etc/init.d/elasticsearch start
```


3/ indexing

Now you can lanch these commands in order to indexing existing data :
go to your symfony project directory
``` bash
php app/console fos:elastica:populate
```