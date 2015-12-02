EmailTrack [![GitHub License](https://img.shields.io/github/license/theshka/EmailTrack.svg)](https://github.com/theshka/EmailTrack/blob/master/LICENSE.md) [![GitHub Release](https://img.shields.io/github/release/theshka/EmailTrack.svg)](https://github.com/theshka/EmailTrack/archive/master.zip)
======

**EmailTrack** This simple PHP class outputs a 'ghost' image/tracking-pixel then
uses PDO & SQLite to track whether or not an email has been seen.

## Download
* [Dev-Master](https://github.com/theshka/EmailTrack/archive/master.zip)
* [Version 0.3.5](https://github.com/theshka/EmailTrack/archive/v.0.3.5.zip)

### Old Versions
* [Version 0.3.0](https://github.com/theshka/EmailTrack/archive/V.0.3.zip)

## Demo
Working Demo: http://tyrexi.us/EmailTrack

## Usage

__Requires__ `PHP 5.3+` & `PDO_SQLITE` driver

- Full instructions and examples are located in the `examples` folder.

- The class file resides in `src/blank.php`

- __You may need to edit paths to the SQLite database/gif in the class settings.__

Note:
> SQLite databases can pose a security risk, and may be downloaded with a direct link! Please take precautions to secure the file. You can so this with Apache `.htaccess` or by making the file hidden.*

This simple PHP class can record opened email messages using tracking pixels. It can serve an image file that would be displayed in an email message when the user opens the message.

The class records each access to the tracking image in a SQLite database accessed using PDO.

It then takes the current request parameters/message-tracking values that are stored in the database like the timestamp, email address and message subject.

Only the first access to the tracking image is recorded. Subsequent access to the same image will be ignored.

## Parameters

```php
$message .= '<img src="/src/blank.php?log=true&subject='.urlencode($subject).'&email='.urlencode($to).'" alt="EmailTrack"/>';
```

|   **Key**   |     **Value**       |
|-------------|---------------------|
| ?log        | true                |
| &subject    | urlencode($subject) |
| &email      | urlencode($to)      |

## License
This project is licensed under the [MIT LICENSE](https://github.com/theshka/EmailTrack/blob/master/LICENSE)

## Contributing
If you would like to help make this software better, please follow our guidelines found in [CONTRIBUTING.md](https://github.com/theshka/EmailTrack/blob/master/CONTRIBUTING.md)

...and a special thank-you to all our  [contributors](https://github.com/theshka/EmailTrack/graphs/contributors)!

## Contact
* Homepage: http://heshka.com
* E-mail: tyler@heshka.com
* KeyBase: https://keybase.io/theshka
