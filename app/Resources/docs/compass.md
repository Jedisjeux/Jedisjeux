check if ruby is setup
after this, launch this command :

``` bash
sudo gem update --system
sudo gem install compass
sudo gem install foundation
```

check if you have nodeJs installed
``` bash
sudo npm install -g bower grunt-cli
sudo gem foundation install
sudo foundation update
bower update
```

if you use phpStorm, go into file/settings/File Watchers or PhpStorm/Preferences/File Watchers
compile /Applications/MAMP/htdocs/jdj/src/JDJ/FoundationBundle/Resources/public

if you don't have create a new watcher called compass

choose selected value :
file-type: scss
scope: project files
program: compass
arguments: compile /Applications/MAMP/htdocs/jdj/src/JDJ/FoundationBundle/Resources/public

create new watcher
file-type: scss
scope: project files
program: compass
compile /src/JDJ/WebBundle/Resources/public


test