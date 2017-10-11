# config valid only for current version of Capistrano
lock '3.8.0'

set :symfony_directory_structure, 3
set :sensio_distribution_version, 5

# symfony-standard edition directories
set :app_path, "app"
set :web_path, "web"
set :var_path, "var"
set :bin_path, "bin"

# The next 3 settings are lazily evaluated from the above values, so take care
# when modifying them
set :app_config_path, "app/config"
set :log_path, "var/logs"
set :cache_path, "var/cache"

set :symfony_console_path, "bin/console"
set :symfony_console_flags, "--no-debug"

# Remove app_dev.php during deployment, other files in web/ can be specified here
set :controllers_to_clear, ["app_*.php"]

# asset management
set :assets_install_path, "web"
set :assets_install_flags,  '--symlink'

# Share files/directories between releases
set :linked_files, ["web/jedisjeux.xml"]
set :linked_dirs, ["var/logs", "var/sessions", "web/uploads", "web/media"]

set :application, 'Jedisjeux'
set :repo_url, 'https://github.com/Jedisjeux/Jedisjeux.git'

# Default branch is :master
ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

set :deploy_to, '/home/jedisjeux/'

# Default value for :scm is :git
# set :scm, :git

# Default value for :format is :pretty
# set :format, :pretty

# Default value for :log_level is :debug
# set :log_level, :debug

# Default value for :pty is false
set :pty, true

append :linked_files, fetch(:app_config_path) + '/parameters.yml', fetch(:web_path) + '/robots.txt', fetch(:web_path) + '/.htaccess'
append :linked_dirs, fetch(:web_path) + '/uploaded', fetch(:web_path) + '/uploads', fetch(:web_path) + '/media'

append :file_permissions_users, 'apache'
set :file_permissions_paths, ["var", "web/uploads"]

set :permission_method,   :acl
set :use_set_permissions, true

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

set :keep_releases, 3

namespace :deploy do

  after :restart, :clear_cache do
    on roles(:web), in: :groups, limit: 3, wait: 10 do
      # Here we can do anything such as:
      # within release_path do
      #   execute :rake, 'cache:clear'
      # end
    end
  end

end

namespace :deploy do
  task :migrate do
    invoke 'symfony:console', 'doctrine:migrations:migrate', '--no-interaction', 'db'
    # invoke 'symfony:console', 'fos:elastica:populate'
  end
end

after 'deploy:updated', :build_assets do
    on roles(:web) do
        puts "Build assets"
        execute "cd #{release_path} && yarn install && yarn run gulp"
    end
end

after 'deploy:updated', 'symfony:assets:install'
after 'deploy:updated', 'deploy:migrate'
