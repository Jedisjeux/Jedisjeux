# config valid only for current version of Capistrano
lock '3.4.0'

set :application, 'Jedisjeux'
set :repo_url, 'git@bitbucket.org:jedisjeux/jdj.git'

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

set :linked_files, fetch(:linked_files, []).push(fetch(:app_config_path) + '/parameters.yml', fetch(:web_path) + '/robots.txt')
set :linked_dirs, fetch(:linked_dirs, [fetch(:log_path)]).push(fetch(:web_path) + '/uploads', fetch(:web_path) + '/media')

set :file_permission_paths, fetch(:file_permission_paths, []).push(fetch(:web_path) + '/uploads', fetch(:web_path) + '/media')
set :file_permissions_users, fetch(:file_permissions_users, []).push('apache')
set :permission_method,   :acl
set :use_set_permissions, true

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Bower
set :bower_target_path, lambda { "#{release_path}/web/assets/" }

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
  end
end

after 'deploy:updated', 'symfony:assets:install'
after 'deploy:updated', 'symfony:assetic:dump'
after 'deploy:updated', 'deploy:migrate'