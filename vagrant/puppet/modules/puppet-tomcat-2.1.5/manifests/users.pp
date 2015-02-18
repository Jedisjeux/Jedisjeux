# Define: tomcat::users
# NOTE: Work in progress. Has only been tested under Ubuntu 12.04
#
# Tomcat user instance
#
# == Parameters
#
# Standard class parameters
# Define the general class behaviour and customizations
#
# [*filemode*]
#
#
#
# Usage:
# With standard template:
# tomcat::users  { 'users':
#   source => 'puppet:///files/tomcat/users.xml',
# }
#
# Notes
# =====

define tomcat::users (
  $filemode = '0640',
  $source   ='',
  ) {

  require tomcat


  file { "tomcat_users_${name}":
    ensure  => file,
    path    => "${tomcat::config_dir}/tomcat-users.xml",
    mode    => $filemode,
    source  => $source,
    owner   => $tomcat::config_file_owner,
    group   => $tomcat::config_file_group,
    require => Class['tomcat'],
  }

}
