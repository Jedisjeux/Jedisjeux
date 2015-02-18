class solr (
	$install_source = 'http://mir2.ovh.net/ftp.apache.org/dist/lucene/solr/4.7.1/solr-4.7.1.tgz',
	$install_destination = '/tmp/',
	$install_precommand = '',
	$install_postcommand = '',
	$owner = 'tomcat7',
	$group = 'tomcat7',
	$solr_version = '4.7.1',
) {

  puppi::netinstall { 'netinstall_solr':
    url                 => $install_source,
    destination_dir     => $install_destination,
    preextract_command  => $install_precommand,
    postextract_command => $install_postcommand,
  }->
  
  file { [ "/opt/solr/", "/opt/solr/webapp/", "/opt/solr/home/", "/opt/solr/home/marqueverte" ]:
    ensure => "directory",
    mode   => '0644',
    owner  => $owner,
    group  => $group,
  }->

  exec { 'Install Solr webapp':
    command => "cp /tmp/solr-${solr_version}/dist/solr-${solr_version}.war /opt/solr/webapp/",
    creates => "/opt/solr/webapp/solr-${solr_version}.war",
  }->
  
  file { 'Symlink to dev conf Directory':
    ensure => 'link',
    path => '/opt/solr/home/marqueverte/conf',
    target => '/var/www/jdj/solr/marqueverte/conf',
  }->
  
  exec { 'Install Solr Log config':
    command => "cp /tmp/solr-${solr_version}/example/resources/log4j.properties /opt/solr/home/",
    creates => '/opt/solr/home/log4j.properties',
  }->
  
  exec {'Add Solr Dependencies to Tomcat7 lib': 
    command => "cp /tmp/solr-${solr_version}/example/lib/ext/* /vagrant/puppet/bin/mysql-connector-java-5.1.30-bin.jar /usr/share/tomcat7/lib/",
  }->
  
  exec {'Add Solr Dependencies to Solr lib': 
    command => "cp -r /tmp/solr-${solr_version}/dist /opt/solr/dist",
  }->
  exec {'Add Solr Contribs to Solr contrib dir': 
    command => "cp -r /tmp/solr-${solr_version}/contrib /opt/solr/contrib",
  }->
  
  file { 'Symlink MarqueVerte Core properties file': 
    ensure => 'link',
    path => '/opt/solr/home/marqueverte/core.properties',
    target => '/var/www/jdj/solr/marqueverte/core.properties',
  }->
 
  file { 'solr.xml Webapp Configuration':
    ensure  => 'present',
    path    => '/etc/tomcat7/Catalina/localhost/solr.xml',
    mode    => '0644',
    owner   => $owner,
    group   => $group,
    content => template("/vagrant/puppet/conf/solr_webapp.xml.erb")
  }->
  
  file { 'solr.xml':
    ensure  => 'present',
    path    => '/opt/solr/home/solr.xml',
    mode    => '0644',
    owner   => $owner,
    group   => $group,
    require => Class['tomcat'],
    source  => '/vagrant/puppet/conf/solr.xml',
  }
  
}
