# MEDIUM
## Environment
defining the environment

## Configuration
The application handles two environments, `development` and `production`.
Normally the configuration file `config.php` is used.
When not using `production` as environment, the application searches for a file named `config.{ENVIRONMENT}.php` e.g. `config.development.php` 

## Code Style
Following `PSR-1`/`PSR-2` except the following ticks
* using tabs instead of 4 spaces
* UPPERCASE booleans and null
* forcing short array declaration style
* variable names in lowercase with underscore separators
* variable names starts with a 2-char prefix (described below)
* opening braces are always kept on the same line
* opening braces are kept on new line when wrapping
* binary expressions: keep operation sign in new line when wrapping 

## Variable prefixes `$aa_` what ??
There is always a 2-char variable prefix e.g. `as` (except class properties):
```php
function (string $as_name, array $aa_data){
    $ls_name = strtlower($as_name);
```
The first prefix char indicates the source or the scope of the var

| Char | Meaning   | Description                             |
|------|-----------|-----------------------------------------|
| a    | attribute | Var source is a method attribute        |
| l    | local     | Var scope is local e.g. inside a method |
| g    | global    | Var scope is global                     |

The second prefix char indicates the default [type](http://us1.php.net/manual/de/language.types.php) of the variable content (if a method returns `int|bool` (and `bool` only in case of failure)) the type char is `integer`, not `unknown`.

| Char | Meaning  | Description                                                                                      |
|------|----------|--------------------------------------------------------------------------------------------------|
| x    | unknown  | This is only used in a few cases e.g. when a method accepts a array of id's or just a single one |
| b    | boolean  |                                                                                                  |
| i    | integer  |                                                                                                  |
| f    | float    |                                                                                                  |
| s    | string   |                                                                                                  |
| a    | array    |                                                                                                  |
| o    | object   |                                                                                                  |
| c    | callable |                                                                                                  |
