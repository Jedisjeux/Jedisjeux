# Define tomcat::puppi::instance
#
# This is a puppi info plugin specific for the tomcat::instance define
#
define tomcat::puppi::instance (
  $servicename  = '',
  $processname  = '',
  $configdir    = '',
  $bindir       = '',
  $pidfile      = '',
  $datadir      = '',
  $logdir       = '',
  $httpport     = '',
  $controlport  = '',
  $ajpport      = '',
  $description  = '',
  $run          = '',
  $verbose      = 'no',
  $templatefile = 'puppi/info/instance.erb' ) {

  require puppi
  require puppi::params

  puppi::log { $name:
    log => "${logdir}/catalina.out",
  }

  file { "${puppi::params::infodir}/${name}":
    ensure  => present,
    mode    => '0750',
    owner   => $puppi::params::configfile_owner,
    group   => $puppi::params::configfile_group,
    content => template($templatefile),
    tag     => 'puppi_info',
  }

}
