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
php app/console fos:elastica:populate
```