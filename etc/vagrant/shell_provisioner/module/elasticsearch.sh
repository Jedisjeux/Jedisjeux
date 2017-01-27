apt-get -y install default-jdk rpm

wget https://download.elastic.co/elasticsearch/elasticsearch/elasticsearch-1.7.3.noarch.rpm
rpm --dbpath $HOME/myrpmdb --nodeps -ivh elasticsearch-1.7.3.noarch.rpm
systemctl enable elasticsearch.service
service elasticsearch start