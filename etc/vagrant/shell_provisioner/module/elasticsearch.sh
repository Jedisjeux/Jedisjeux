wget https://download.java.net/java/GA/jdk10/10.0.1/fb4372174a714e6b8c52526dc134031e/10//openjdk-10.0.1_linux-x64_bin.tar.gz
mkdir /opt/jdk
tar -zxf openjdk-10.0.1_linux-x64_bin.tar.gz -C /opt/jdk/
rm openjdk-10.0.1_linux-x64_bin.tar.gz

# update-alternatives --install /usr/bin/java java /opt/jdk/jdk-10.0.1/bin/java 1
# update-alternatives --install /usr/bin/javac javac /opt/jdk/jdk-10.0.1/bin/javac 1

apt-get install apt-transport-https
echo "deb https://artifacts.elastic.co/packages/6.x/apt stable main" | sudo tee -a /etc/apt/sources.list.d/elastic-6.x.list
apt-get update && apt-get -y --force-yes install elasticsearch
systemctl enable elasticsearch.service
service elasticsearch start