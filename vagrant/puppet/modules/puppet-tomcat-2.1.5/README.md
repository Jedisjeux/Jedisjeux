# Puppet module: tomcat

This is a Puppet tomcat module from the second generation of Example42 Puppet Modules.

Made by Alessandro Franceschi / Lab42

Official site: http://www.example42.com

Official git repository: http://github.com/example42/puppet-tomcat

Released under the terms of Apache 2 License.

This module requires functions provided by the Example42 Puppi module.

For detailed info about the logic and usage patterns of Example42 modules read README.usage on Example42 main modules set.

## USAGE - Basic management

* Install tomcat with default settings

        class { "tomcat": }

* Disable tomcat service and create single tomcat instances (note for each for them you must specify (different) http_port and control_port

        class { "tomcat":
          disable => true
        }

        tomcat::instance { 'my_app':
          http_port    => '8088',
          control_port => '6088',
        }

        tomcat::instance { 'other_app':
          http_port    => '8089',
          control_port => '6089',
          ajp_port     => '9089',
        }

* Disable tomcat service at boot time, but don't stop if is running.

        class { "tomcat":
          disableboot => true
        }

* Remove tomcat package

        class { "tomcat":
          absent => true
        }

* Enable auditing without making changes on existing tomcat configuration files

        class { "tomcat":
          audit_only => true
        }

* Install a custom version of tomcat. The default is chose according to OS versions, but you can try to install a different one (given that a tomcat${version} packge is available)

        class { "tomcat": }

        On hiera set this key with the version value you want:
        tomcat::params::version: 7

Note: that some templates used by tomcat::instance may not be present for your version. You should provide them with the *_template parameters.

## USAGE - Overrides and Customizations
* Use custom sources for main config file 

        class { "tomcat":
          source => [ "puppet:///modules/lab42/tomcat/tomcat.conf-${hostname}" , "puppet:///modules/lab42/tomcat/tomcat.conf" ], 
        }


* Use custom source directory for the whole configuration dir

        class { "tomcat":
          source_dir       => "puppet:///modules/lab42/tomcat/conf/",
          source_dir_purge => false, # Set to true to purge any existing file not present in $source_dir
        }

* Use custom template for main config file 

        class { "tomcat":
          template => "example42/tomcat/tomcat.conf.erb",      
        }

* Define custom options that can be used in a custom template without the
  need to add parameters to the tomcat class

        class { "tomcat":
          template => "example42/tomcat/tomcat.conf.erb",    
          options  => {
            'LogLevel' => 'INFO',
            'UsePAM'   => 'yes',
          },
        }

* Automaticallly include a custom subclass

        class { "tomcat:"
          my_class => 'tomcat::example42',
        }


## USAGE - Example42 extensions management 
* Activate puppi (recommended, but disabled by default)
  Note that this option requires the usage of Example42 puppi module

        class { "tomcat": 
          puppi    => true,
        }

* Activate puppi and use a custom puppi_helper template (to be provided separately with
  a puppi::helper define ) to customize the output of puppi commands 

        class { "tomcat":
          puppi        => true,
          puppi_helper => "myhelper", 
        }

* Activate automatic monitoring (recommended, but disabled by default)
  This option requires the usage of Example42 monitor and relevant monitor tools modules

        class { "tomcat":
          monitor      => true,
          monitor_tool => [ "nagios" , "monit" , "munin" ],
        }

* Activate automatic firewalling 
  This option requires the usage of Example42 firewall and relevant firewall tools modules

        class { "tomcat":       
          firewall      => true,
          firewall_tool => "iptables",
          firewall_src  => "10.42.0.0/24",
          firewall_dst  => "$ipaddress_eth0",
        }


[![Build Status](https://travis-ci.org/example42/puppet-tomcat.png?branch=master)](https://travis-ci.org/example42/puppet-tomcat)
