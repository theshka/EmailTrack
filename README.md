# EmailTrack
## This simple PHP class outputs a ghost image and uses PDO & SQLite to track whether or not an email has been seen.


## REQUIREMENTS
- PHP 5.3+
- PDO_SQLITE driver


## CONCEPT
Wherever you send an email that needs to be tracked, we’ll just inject an image that ACTUALLY loads a PHP file on our server. The PHP class that is requested in the `<img...` tag, takes `$_GET` parameters, logs them in the SQLite database, then serves up an actual image.


## PARAMETERS
```php
$message .= '<img src="/src/blank.php?log=true&subject='.urlencode($subject).'&email='.urlencode($to).'" alt="EmailTrack"/>';
```
-------------------------------------
|   **Key**   |     **Value**       |
|-------------|---------------------|
| ?log        | true                |
| &subject    | urlencode($subject) |
| &email      | urlencode($to)      |
-------------------------------------


## INSTALLATION
- Full instructions and examples are located in the `examples` folder.
- The class file resides in `src/blank.php`
- **You may need to edit paths to the SQLite database/gif in the class settings.**
- *SQLite databases can pose a security risk, and may be downloaded with a direct link! Please take precautions to secure the file. You can so this with Apache `.htaccess` or by making the file hidden.*


## DEMO
Working Demo: http://tyrexi.us/EmailTrack


## LICENSE
This work is licensed by The MIT License (MIT)
http://opensource.org/licenses/MIT

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.


## Contributing
* Create a Fork of the main branch.
* Clone the repository `$ git clone http://github.com/yourusername/EmailTrack`
* Add a connection to the repository.`$ git remote add origin http://github.com/theshka/EmailTrack`
* Make changes to files.
* `git add` and `git commit` those changes
* `git push` them back to github. These will go to your version of the repository.
* Submit a pull-request
