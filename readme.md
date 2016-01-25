DIP Sandmin
=============

## Requirements

- [Nette Framework](https://github.com/nette/nette)
- [Kdyby Doctrine](https://github.com/Kdyby/Doctrine)
- [DIPcom Localization](https://github.com/DIPcom/Localization)
- [DIPcom UserManager](https://github.com/DIPcom/UserManager)
- [DIPcom DIPjs](https://github.com/DIPcom/DIPjs)
- [almasaeed2010 AdminLTE](https://github.com/almasaeed2010/AdminLTE)


How to install
--------------

```sh
$ composer create-project dipcom/sandmin my_project_name
```
How to use [composer?](https://getcomposer.org/doc/00-intro.md)



Create a database called "sandmin" database settings can be changed in the var app/config/config.neon
How to use [doctrine?](https://github.com/Kdyby/Doctrine/blob/master/docs/en/index.md)

```yaml
    doctrine:
        user: root
        password: 
        dbname: sandmin
        metadata:
            App: %appDir%
```


Migrate database tables.
How to use [migrations?](https://github.com/Zenify/DoctrineMigrations)

```sh
$ cd my_project_name
$ php www/index.php migrations:migrate
```
Setting apache folder  my_project_name/www/


