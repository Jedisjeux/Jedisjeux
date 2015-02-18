# Class: tomcat::manager
#
class tomcat::manager inherits tomcat {

  if $tomcat::manager_package {
    package { 'tomcat-manager':
      ensure => $tomcat::manage_package,
      name   => $tomcat::manager_package,
    }
  }
}
