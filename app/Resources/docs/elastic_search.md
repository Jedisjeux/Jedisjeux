check if brew is installed
``` bash
ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"
```

launch these commands :
``` bash
brew update
brew install elasticsearch
```

After elastica search is installed
launch this command :
``` bash
curl brew info elasticsearch
```

And then follow all the commands shown to lanch elastica


Now you can lanch these commands in order to indexing existing data :
``` bash
elasticsearch start
php app/console fos:elastica:populate

```