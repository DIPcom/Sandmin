#UserManager

```sh
$ composer require dipcom/localization:@dev
```

```yaml  
localization:
        local_dir: %appDir%\Localization
        default_lang: cs

extensions:
	localization: DIPcom\Localization\DI\LocalizationExtension
```

Language file template
----------------------

cs.nenon

```yaml  
config: 
    name: 'Čeština'
    date: 'd.m.Y'

errors: 
    img: 'Nahraný soubor musí být obrázek ve formátu JPG, GIF nebo PNG'
local:
    name: 'Jméno'
```
