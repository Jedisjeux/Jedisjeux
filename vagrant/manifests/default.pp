$host_name = "jdj.dev"
$db_name = "jdj"
$db_name_dev = "${db_name}_dev"
$db_name_tst = "${db_name}_test"

group { 'puppet': ensure => present }
Exec { path => [ '/bin/', '/sbin/', '/usr/bin/', '/usr/sbin/' ] }
File { owner => 0, group => 0, mode => 0644 }

file { "/dev/shm/jdj":
  ensure => directory,
  purge => true,
  force => true,
  owner => vagrant,
  group => vagrant
}

file { "/var/lock/apache2":
  ensure => directory,
  owner => vagrant
}

exec { "ApacheUserChange" :
  command => "sed -i 's/export APACHE_RUN_USER=.*/export APACHE_RUN_USER=vagrant/ ; s/export APACHE_RUN_GROUP=.*/export APACHE_RUN_GROUP=vagrant/' /etc/apache2/envvars",
  require => [ Package["apache"], File["/var/lock/apache2"] ],
  notify  => Service['apache'],
}

class {'apt':
  always_apt_update => true,
}

Class['::apt::update'] -> Package <|
    title != 'python-software-properties'
and title != 'software-properties-common'
|>

package { [
    'build-essential',
    'vim',
    'curl',
    'git-core',
    'mc'
  ]:
  ensure  => 'installed',
}

class { 'apache': }

#overload apache::dotconf to use right conf directory and add symlink

define apache::dotconf (
  $source  = '' ,
  $content = '' ,
  $ensure  = present ) {

  $manage_file_source = $source ? {
    ''        => undef,
    default   => $source,
  }

  $manage_file_content = $content ? {
    ''        => undef,
    default   => $content,
  }

  $conf_path = $::operatingsystemrelease ? {
    /(?i:14.04)/ => "${apache::config_dir}/conf-available/${name}.conf",
    default      => "${apache::config_dir}/conf.d/${name}.conf",
  }

  file { "Apache_${name}.conf":
    ensure  => $ensure,
    path    => "${apache::config_dir}/conf-available/${name}.conf",
    mode    => $apache::config_file_mode,
    owner   => $apache::config_file_owner,
    group   => $apache::config_file_group,
    require => Package['apache'],
    notify  => $apache::manage_service_autorestart,
    source  => $manage_file_source,
    content => $manage_file_content,
    audit   => $apache::manage_audit,
  }

  if $::operatingsystemrelease == 14.04 {
    $exec_a2enmod_subscribe = $install_package ? {
      false   => undef,
      default => Package["ApacheModule_${name}"]
    }
    exec { "/usr/sbin/a2enconf ${name}":
      unless    => "/bin/sh -c '[ -L ${apache::config_dir}/conf-enabled/${name}.conf ] && [ ${apache::config_dir}/conf-enabled/${name}.conf -ef ${apache::config_dir}/conf-available/${name}.conf ]'",
      notify    => $manage_service_autorestart,
      require   => Package['apache'],
      subscribe => $exec_a2enconf_subscribe,
    }
  }

}

apache::dotconf { 'jdj':
  content => 'EnableSendfile Off',
}

apache::module { 'rewrite': }

apache::vhost { "${host_name}":
  server_name   => "${host_name}",
  serveraliases => [
    "www.${host_name}"
  ],
  docroot       => "/var/www/jdj/web/",
  port          => '80',
  env_variables => [
    'VAGRANT VAGRANT'
  ],
  priority      => '1',
}

class { 'php':
  service             => 'apache',
  service_autorestart => false,
  module_prefix       => '',
}

php::module { 'php5-mysql': }
php::module { 'php5-cli': }
php::module { 'php5-curl': }
php::module { 'php5-intl': }
php::module { 'php5-mcrypt': }
php::module { 'php5-gd': }
php::module { 'php-apc': }

class { 'php::devel':
  require => Class['php'],
}

class { 'php::pear':
  require => Class['php'],
}

php::pear::module { 'PHPUnit':
  repository  => 'pear.phpunit.de',
  use_package => 'no',
  require => Class['php::pear']
}

php::pecl::module { 'mongo':
  use_package => "no",
}

#php::pecl::module { 'amqp':
#  use_package => 'false'
#}

class { 'composer':
  command_name => 'composer',
  target_dir   => '/usr/local/bin',
  auto_update => true,
  require => Package['php5', 'curl'],
}


php::ini { 'php_ini_configuration':
  value   => [
    'extension=mongo.so',
    'date.timezone = "Europe/Paris"',
    'display_errors = On',
    'error_reporting = -1',
    'short_open_tag = 0',
  ],
  notify  => Service['apache'],
  require => Class['php']
}

class ruby_compass {
    package { 'ruby-dev':
        ensure => 'installed',
    }
    # Ensure we can install gems
    package { ["ruby"]:
        ensure => 'installed'
    }
    # Install gems
    package { ['sass']:
        ensure => '3.2.13',
        provider => 'gem',
        require => Package['ruby']
    }~>
    package { ['compass']:
        ensure => '0.12.2',
        provider => 'gem',
        require => Package['ruby']
    }
}

include ruby_compass

# Start compass watch at each boot
file { '/etc/init/compass.conf':
  ensure => 'file',
  source => '/vagrant/puppet/init/compass.conf',
  owner => 'root',
  group => 'root',
}

service { 'compass':
  ensure => 'running',
  enable => true,
  require => File['/etc/init/compass.conf'],
}

class { 'mysql::server':
  override_options => { 'root_password' => 'jedisjeux', },
}

mysql_database{ "jdj":
  ensure  => present,
  charset => 'utf8',
  require => Class['mysql::server'],
}

mysql_database{ "jdj_dev":
  ensure  => present,
  charset => 'utf8',
  require => Class['mysql::server'],
}

mysql_database{ "jdj_test":
  ensure  => present,
  charset => 'utf8',
  require => Class['mysql::server'],
}

mysql_database{ "jedisjeux":
  ensure  => present,
  charset => 'utf8',
  require => Class['mysql::server'],
}

# Install Oracle Java and Tomcat

include java7

class { "tomcat": 
  config_file_group => 'tomcat7',
}

class { "tomcat::manager": }

tomcat::users  { 'users':
  source => '/vagrant/puppet/conf/tomcat-users.xml',
}

import 'solr.pp'

class { "solr":
  install_source => 'http://mir2.ovh.net/ftp.apache.org/dist/lucene/solr/4.7.1/solr-4.7.1.tgz',
  solr_version => '4.7.1', 
}

include solr

include '::rabbitmq'
