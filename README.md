## WordPress users password recovery

#### How to use:

Copy `passwordrecovery.php` to wordpress site folder.

##### Change authorisation password

Open file and change your authorisation **login** and **password** here:

```php
/** Change login data here */
/** authorisation login */
define('PS_LOGIN', 'admin');
/** authorisation password */
define('PS_PASSWORD', 'password');
```

Default login is **admin** and password is **password**

##### Run
Open link `{your site url}/passwordrecovery.php` and have fun!
