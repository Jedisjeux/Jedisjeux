Adding ssh authorized keys for server on your local computer

``` bash
cat ~/.ssh/id_rsa.pub | ssh jedisjeux@92.243.10.152 "cat - >> ~/.ssh/authorized_keys"
```

and enter the correct password for username "jedisjeux" on server


To deploy the application on a server

``` bash
cd app/
```

To install dependencies

``` bash
bundle install
```

To deploy the staging environment

``` bash
bundle exec "cap staging deploy"
```