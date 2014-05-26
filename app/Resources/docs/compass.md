check if ruby is setup
after this, lanch this command :

``` bash
sudo gem update --system
sudo gem install compass
```

if you use phpStorm, go into file/settings/File Watchers or PhpStorm/Preferences/File Watchers
compile /Applications/MAMP/htdocs/jdj/src/JDJ/FoundationBundle/Resources/public

if you don't have create a new watcher called compass

choose selected value :
file-type: scss
scope: project files
program: compass
arguments: compile /Applications/MAMP/htdocs/jdj/src/JDJ/FoundationBundle/Resources/public