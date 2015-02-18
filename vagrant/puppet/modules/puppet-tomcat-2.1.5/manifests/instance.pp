# Define: tomcat::instance
#
define tomcat::instance (

  $http_port,
  $control_port,
  $ajp_port                     = '',

  $dirmode                      = '0755',
  $filemode                     = '0644',
  $owner                        = '',
  $group                        = '',

  $magicword                    = 'SHUTDOWN',

  $runtime_dir                  = '',

  $java_opts                    = '-Djava.awt.headless=true -Xmx128m  -XX:+UseConcMarkSweepGC',
  $catalina_opts                = '',
  $java_home                    = '',

  $catalina_properties_template = '',
  $logging_properties_template  = '',
  $init_template                = '',
  $init_defaults_template       = '',
  $startup_sh_template          = '',
  $shutdown_sh_template         = '',
  $setenv_sh_template           = '',
  $params_template              = '',
  $create_instance_cmd_template = '',
  $create_instance_cmd_exec     = '',
  $server_xml_template          = '',
  $context_xml_template         = '',
  $tomcat_users_xml_template    = '',
  $web_xml_template             = '',
  $manager_xml_template         = 'tomcat/instance/manager.xml.erb',

  $tomcatuser                   = '',
  $tomcatpassword               = '',

  $puppi                        = false,
  $monitor                      = false,
  $monitor_tool                 = $::monitor_tool,

  $manager                      = false,

  $modjk_workers_file           = '',

  $apache_vhost_create          = false,
  $apache_vhost_template        = 'tomcat/apache/vhost.conf.erb',
  $apache_vhost_server_name     = '',
  $apache_vhost_docroot         = undef,
  $apache_vhost_proxy_alias     = '',
  $apache_vhost_context         = '',

  ) {

  require tomcat::params

  $tomcat_version = $tomcat::params::real_version

  # Application name, required
  $instance_name = $name

  # Application owner, by default the same instance name
  $instance_owner = $owner ? {
    ''      => $tomcat::process_user,
    default => $owner,
  }

  # Application group, by default the same instance name
  $instance_group = $group ? {
    ''      => $tomcat::process_user,
    default => $group,
  }

  # CATALINA BASE
  $instance_path = "/var/lib/${tomcat::params::pkgver}-${instance_name}"

  # Startup script
  $instance_startup = "${instance_path}/bin/startup.sh"

  # Shutdown script
  $instance_shutdown = "${instance_path}/bin/shutdown.sh"

  $instance_init_template = $init_template ? {
    ''      => "tomcat/instance/init${tomcat_version}-${::osfamily}.erb",
    default => $init_template
  }

  $instance_init_defaults_template = $init_defaults_template ? {
    ''      => "tomcat/instance/defaults${tomcat_version}-${::osfamily}.erb",
    default => $init_defaults_template
  }

  $instance_init_defaults_template_path = $::osfamily ? {
    Debian => "/etc/default/tomcat${tomcat_version}-${instance_name}",
    RedHat => "/etc/sysconfig/tomcat${tomcat_version}-${instance_name}",
  }

  # Create instance
  $instance_create_instance_cmd_template = $create_instance_cmd_template ? {
    ''      => 'tomcat/instance/tomcat-instance-create.erb',
    default => $create_instance_cmd_template
  }

  $real_ajp_port = $ajp_port ? {
    ''      => '',
    default => "-a ${ajp_port}",
  }

  $real_runtime_dir = $runtime_dir ? {
    ''      => '',
    default => "-r ${runtime_dir}/${instance_name}",
  }

  $instance_create_instance_cmd_exec = $create_instance_cmd_exec ? {
    ''      => "/usr/bin/tomcat-instance-create -p ${http_port} -c ${control_port} ${real_ajp_port} -w ${magicword} -o ${instance_owner} -g ${instance_group} ${real_runtime_dir} ${instance_path}",
    default => $create_instance_cmd_exec,
  }

  if (!defined(File['/usr/bin/tomcat-instance-create'])) {
    file { '/usr/bin/tomcat-instance-create':
      ensure  => present,
      mode    => '0775',
      owner   => 'root',
      group   => 'root',
      content => template($instance_create_instance_cmd_template),
      before  => Exec["instance_tomcat_${instance_name}"]
    }
  }

  exec { "instance_tomcat_${instance_name}":
    command => $instance_create_instance_cmd_exec,
    creates => "${instance_path}/webapps",
    require => [ Package['tomcat'] ],
  }

  # Install Manager if $manager == true
  if $manager == true {
    if (!defined(Class['tomcat::manager'])) {
      class { 'tomcat::manager':
        before => Exec["instance_tomcat_${instance_name}"],
      }
    }

    file { "instance_manager_xml_${instance_name}":
      ensure  => present,
      path    => "/etc/${tomcat::params::pkgver}-${instance_name}/Catalina/localhost/manager.xml",
      mode    => '0644',
      owner   => 'root',
      group   => 'root',
      require => Exec["instance_tomcat_${instance_name}"],
      notify  => Service["tomcat-${instance_name}"],
      content => template($manager_xml_template),
    }

  }

  # Running service
  service { "tomcat-${instance_name}":
    ensure     => running,
    name       => "${tomcat::params::pkgver}-${instance_name}",
    enable     => true,
    pattern    => $instance_name,
    hasrestart => true,
    hasstatus  => $tomcat::params::service_status,
    require    => Exec["instance_tomcat_${instance_name}"],
    subscribe  => File["instance_tomcat_init_${instance_name}"],
  }

  # Create service initd file
  file { "instance_tomcat_init_${instance_name}":
    ensure  => present,
    path    => "${tomcat::params::config_file_init}-${instance_name}",
    mode    => '0755',
    owner   => 'root',
    group   => 'root',
    require => Exec["instance_tomcat_${instance_name}"],
    notify  => Service["tomcat-${instance_name}"],
    content => template($instance_init_template),
  }

  file { "instance_tomcat_defaults_${instance_name}":
    ensure  => present,
    path    => $instance_init_defaults_template_path,
    mode    => '0644',
    owner   => 'root',
    group   => 'root',
    require => Exec["instance_tomcat_${instance_name}"],
    notify  => Service["tomcat-${instance_name}"],
    content => template($instance_init_defaults_template),
  }

  # catalina.properties is defined only if $catalina_properties_template is set
  if $catalina_properties_template != '' {
    file { "instance_tomcat_catalina.properties_${instance_name}":
      ensure  => present,
      path    => "${instance_path}/conf/catalina.properties",
      mode    => $filemode,
      owner   => $instance_owner,
      group   => $instance_group,
      require => Exec["instance_tomcat_${instance_name}"],
      notify  => Service["tomcat-${instance_name}"],
      content => template($catalina_properties_template),
    }
  }

  # Ensure logging.properties presence
  if $logging_properties_template != '' {
    file { "instance_tomcat_logging.properties_${instance_name}":
      ensure  => present,
      path    => "${instance_path}/conf/logging.properties",
      mode    => $filemode,
      owner   => $instance_owner,
      group   => $instance_group,
      require => Exec["instance_tomcat_${instance_name}"],
      notify  => Service["tomcat-${instance_name}"],
      content => template($logging_properties_template),
    }
  }

  # Ensure setenv.sh presence
  if $setenv_sh_template != '' {
    file { "instance_tomcat_setenv.sh_${instance_name}":
      ensure  => present,
      path    => "${instance_path}/bin/setenv.sh",
      mode    => '0755',
      owner   => $instance_owner,
      group   => $instance_group,
      require => Exec["instance_tomcat_${instance_name}"],
      notify  => Service["tomcat-${instance_name}"],
      content => template($setenv_sh_template),
    }
  }

  # Ensure params presence
  if $params_template != '' {
    file { "instance_tomcat_params_${instance_name}":
      ensure  => present,
      path    => "${instance_path}/bin/params",
      mode    => '0755',
      owner   => $instance_owner,
      group   => $instance_group,
      require => Exec["instance_tomcat_${instance_name}"],
      content => template($params_template),
    }
  }

  # Ensure startup.sh presence
  if $startup_sh_template != '' {
    file { "instance_tomcat_startup.sh_${instance_name}":
      ensure  => present,
      path    => $instance_startup,
      mode    => '0755',
      owner   => $instance_owner,
      group   => $instance_group,
      require => Exec["instance_tomcat_${instance_name}"],
      content => template($startup_sh_template),
    }
  }

  # Ensure shutdown.sh presence
  if $shutdown_sh_template != '' {
    file { "instance_tomcat_shutdown.sh_${instance_name}":
      ensure  => present,
      path    => $instance_shutdown,
      mode    => '0755',
      owner   => $instance_owner,
      group   => $instance_group,
      require => Exec["instance_tomcat_${instance_name}"],
      content => template($shutdown_sh_template),
    }
  }

  # server.xml is defined only if $server_xml_template is set
  if $server_xml_template != '' {
    file { "instance_tomcat_server.xml_${instance_name}":
      ensure  => present,
      path    => "${instance_path}/conf/server.xml",
      mode    => $filemode,
      owner   => $instance_owner,
      group   => $instance_group,
      require => Exec["instance_tomcat_${instance_name}"],
      notify  => Service["tomcat-${instance_name}"],
      content => template($server_xml_template),
    }
  }

  # context.xml is defined only if $context_xml_template is set
  if $context_xml_template != '' {
    file { "instance_tomcat_context.xml_${instance_name}":
      ensure  => present,
      path    => "${instance_path}/conf/context.xml",
      mode    => $filemode,
      owner   => $instance_owner,
      group   => $instance_group,
      require => Exec["instance_tomcat_${instance_name}"],
      notify  => Service["tomcat-${instance_name}"],
      content => template($context_xml_template),
    }
  }

  # tomcat-users.xml is defined only if $tomcat_users_xml_template is set
  if $tomcat_users_xml_template != '' {
    file { "instance_tomcat_tomcat-users.xml_${instance_name}":
      ensure  => present,
      path    => "${instance_path}/conf/tomcat-users.xml",
      mode    => $filemode,
      owner   => $instance_owner,
      group   => $instance_group,
      require => Exec["instance_tomcat_${instance_name}"],
      notify  => Service["tomcat-${instance_name}"],
      content => template($tomcat_users_xml_template),
    }
  }

  # web.xml is defined only if $web_xml_template is set
  if $web_xml_template != '' {
    file { "instance_tomcat_web.xml_${instance_name}":
      ensure  => present,
      path    => "${instance_path}/conf/web.xml",
      mode    => $filemode,
      owner   => $instance_owner,
      group   => $instance_group,
      require => Exec["instance_tomcat_${instance_name}"],
      notify  => Service["tomcat-${instance_name}"],
      content => template($web_xml_template),
    }
  }

  if ($modjk_workers_file != '') {
    include concat::setup

    $normalized_modjk_workers_file = regsubst($modjk_workers_file, '/', '_', 'G')

    concat::fragment{"instance_tomcat_modjk_${instance_name}":
      target  => "${::concat::setup::concatdir}/instance_tomcat_modjk_${normalized_modjk_workers_file}",
      content => template('tomcat/modjk.worker.properties'),
    }

    concat::fragment{"instance_tomcat_modjk_names_${instance_name}":
      target  => "${::concat::setup::concatdir}/instance_tomcat_modjk_names_${normalized_modjk_workers_file}",
      content => "${instance_name}_worker, ",
    }

  }

  if $monitor == true {
    monitor::process { "tomcat-${instance_name}":
      process  => 'java',
      argument => $instance_name,
      service  => "tomcat-${instance_name}",
      pidfile  => "/var/run/${tomcat::params::pkgver}-${instance_name}.pid",
      enable   => true,
      tool     => $monitor_tool,
    }

    monitor::port { "tomcat-${instance_name}-${http_port}":
      protocol => 'tcp',
      port     => $http_port,
      target   => $::fqdn,
      enable   => true,
      tool     => $monitor_tool,
    }
  }
  if $puppi == true {
    tomcat::puppi::instance { "tomcat-${instance_name}":
      servicename  => "tomcat-${instance_name}",
      processname  => $instance_name,
      configdir    => "${instance_path}/conf/",
      bindir       => "${instance_path}/bin/",
      pidfile      => "/var/run/${tomcat::params::pkgver}-${instance_name}.pid",
      datadir      => "${instance_path}/webapps",
      logdir       => "${instance_path}/logs",
      httpport     => $http_port,
      controlport  => $control_port,
      ajpport      => $ajp_port,
      description  => "Info for ${instance_name} Tomcat instance" ,
    }
  }

  if $apache_vhost_create == true {
    $instance_apache_vhost_context = $apache_vhost_context ? {
      ''      => $instance_name,
      default => $apache_vhost_context,
    }

    $instance_apache_vhost_proxy_alias = $apache_vhost_proxy_alias ? {
      ''      => $ajp_port ? {
        ''      => "/${instance_apache_vhost_context} http://localhost:${http_port}/${instance_apache_vhost_context}",
        default => "/${instance_apache_vhost_context} ajp://localhost:${ajp_port}/${instance_apache_vhost_context}",
      },
      default => $apache_vhost_proxy_alias,
    }

    if ! defined(Apache::Module['proxy']) {
      apache::module { 'proxy': }
    }

    if ! defined(Apache::Module['proxy_http']) {
      apache::module { 'proxy_http': }
    }

    if ! defined(Apache::Module['proxy_ajp']) {
      if $ajp_port != '' {
        apache::module { 'proxy_ajp': }
      }
    }

    if $manager == true {
      $array_instance_apache_vhost_proxy_alias = concat( [ $instance_apache_vhost_proxy_alias ] , [ "/manager http://localhost:${http_port}/manager" ] )
    } else {
      $array_instance_apache_vhost_proxy_alias = $instance_apache_vhost_proxy_alias
    }

    if $apache_vhost_server_name == '' {
      fail('You must specify the parameter apache_vhost_server_name on your tomcat::install when apache_vhost_create == true')
    }

    apache::vhost { $instance_name:
      server_name => $apache_vhost_server_name ,
      proxy_alias => $array_instance_apache_vhost_proxy_alias,
      template    => $apache_vhost_template,
      docroot     => $apache_vhost_docroot,
    }
  }

}
