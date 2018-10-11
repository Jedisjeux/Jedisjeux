1/ Only If you are on windows 7 you need to add behat on your path.

Follow the information on this page :
http://geekswithblogs.net/renso/archive/2009/10/21/how-to-set-the-windows-path-in-windows-7.aspx
to add "bin" directory of your symfony project into your path

2/ Increase the nesting level

edit your php.ini file of your php-cli and add at the end of the file :
``` bash
xdebug.max_nesting_level=200
```

3/ Start selenium

3-1 On firefox
``` bash
java -jar ../selenium-server-standalone-2.45.0.jar
```

3-2 On internet explorer
``` bash
java -jar ../selenium-server-standalone-2.45.0.jar -Dwebdriver.ie.driver=../selenium/drivers/IEDriverServer.exe
```

3-3 On chrome
``` bash
java -jar app/Resources/selenium/selenium-server-standalone-2.45.0.jar -Dwebdriver.ie.driver=../selenium/drivers/chromedriver.exe
```

4/ Launching tests

4-1 On firefox
``` bash
bin/behat
```

4-2 On chrome
``` bash
bin/behat -p=chrome
```

4-2 On internet explorer
``` bash
bin/behat -p=ie
```
