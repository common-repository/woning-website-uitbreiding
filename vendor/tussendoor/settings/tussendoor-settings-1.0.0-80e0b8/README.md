# Settings

Een simpele manier om instellingen van een plugin te managen.

## Installatie

Via Composer

``` bash
$ composer require Tussendoor/Settings
```

## Gebruik

Om instellingen te bewerken of uit te lezen, maak je gebruik van de Manager class.

``` php
$manager = new Tussendoor\Settings\Manager();

// De instelling bestaat nog niet
echo $manager->get('foo'); //null

// Maak de instelling aan
$manager->save('foo', 'bar');
echo $manager->get('option_name'); //bar

// Verwijder instelling
$manager->delete('option_name'); //true
$manager->get('option_name'); //null
```
De standaard plek waar instellingen worden opgeslagen, is in de `WP_Options` tabel van WordPress. Op de achtergrond worden de `get_option()`, `update_option()` en `delete_option()` functies hier voor gebruikt.

### SettingsProviders
De Settings lib heeft ondersteuning voor meerdere 'SettingsProviders'. Providers zijn plekken waar instellingen opgeslagen staan. Standaard heeft de Settings lib ondersteuning voor twee Providers:

 - De WP_Options tabel van WordPress
 - Een jSON tekstbestand

Om van `SettingsProvider` te wisselen, geef je een provider mee in de constructor van de `Manager` class.

``` php
use Tussendoor\Settings\Providers\SettingsProviderJson;

$jsonProvider = new SettingsProviderJson('/pad/naar/bestand/', 'bestandsnaam.json');

$jsonManager = new Tussendoor\Settings\Manager($jsonProvider);
```
**Let op:** Het jSON bestand wordt automatisch aangemaakt als het nog niet bestaat.

Het is mogelijk om meerdere SettingsProviders gelijktijdig te gebruiken.


### Eigen provider
Om een eigen provider aan te maken, maak je een nieuwe class aan welke de `Tussendoor\Settings\Providers\SettingsProviderInterface` interface implementeert. Implementeer vervolgens de methods zoals aangegeven in de interface.

## Change log

Bekijk de [CHANGELOG](CHANGELOG.md) voor meer informatie over wat er is veranderd.

## Testing

``` bash
$ composer test
```
**Let op:** Vereist PHPUnit 6.4+

## Security

If you discover any security related issues, please email sander@tussendoor.nl instead of using the issue tracker.

## Credits

- [Sander de Kroon][link-author]


[ico-version]: https://img.shields.io/packagist/v/Tussendoor/Settings.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/Tussendoor/Settings/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/Tussendoor/Settings.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Tussendoor/Settings.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/Tussendoor/Settings.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/Tussendoor/Settings
[link-travis]: https://travis-ci.org/Tussendoor/Settings
[link-scrutinizer]: https://scrutinizer-ci.com/g/Tussendoor/Settings/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/Tussendoor/Settings
[link-downloads]: https://packagist.org/packages/Tussendoor/Settings
[link-author]: https://github.com/sanderdekroon
[link-contributors]: ../../contributors
