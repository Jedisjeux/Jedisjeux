# Define tomcat::mod_jk
#
# Configures Apache Httpd Mod_jk
#
# For now, all it does is create a workers.properties file that can be used
# by mod_jk. Assembles based on the tomcat::instance's where the
# $tomcat::instance::modjk_workers_file equals the $tomcat::mod_jk::workers_file
#
# == Parameters
#
# Standard class parameters
# Define the general class behaviour and customizations
#
# [*workers_file*]
# The path of the workers file to generate
#
#
define tomcat::mod_jk (
  $workers_file,
) {

  include concat::setup

  $normalized_workers_file = regsubst($workers_file, '/', '_', 'G')

  $workers_file_frags = "${::concat::setup::concatdir}/instance_tomcat_modjk_${normalized_workers_file}"
  concat{ $workers_file_frags:
    owner => root,
    group => root,
    mode  => '0644',
  }

  $names_file  = "${::concat::setup::concatdir}/instance_tomcat_modjk_names_${normalized_workers_file}"
  concat{ $names_file:
    owner => root,
    group => root,
    mode  => '0644',
  }

  file { $workers_file:
    owner   => root,
    group   => root,
    mode    => '0644',
    content => template('tomcat/modjk.workers.properties')
  }

  exec { "tomcat_mod_jk_${name}_replace_worker":
    command => "/bin/bash -c \"sed -i -e '/%%workers%%/{r ${workers_file_frags}' -e 'd}' ${workers_file}\"",
    require => Concat[$workers_file_frags],
  }

  exec { "tomcat_mod_jk_${name}_replace_worker_names":
    command => "/bin/bash -c \"sed -i 's/%%names_file%%/`cat ${names_file}`/' ${workers_file}\"",
    require => Concat[$names_file],
  }

}
