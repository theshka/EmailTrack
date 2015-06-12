# EmailTrack
## This simple PHP class outputs a ghost image and uses PDO & SQLite to track whether or not an email has been seen.

## REQUIREMENTS
- PHP 5.3+
- PDO_SQLITE driver

## INSTALLATION
- Full instructions and examples for how to use the class are located in `index.php` & `index2.php`
- The actual class file resides in `static/images/blank.php`
- Upload the `blank.gif` and `blank.php` to the same directory.
- You may need to edit the paths to the SQLite database/gif.
- *SQLite databases can pose a security risk, and may be downloaded with a direct link! Please take precautions to secure the file. You can so this with Apache `.htaccess` or by making the file hidden.*

Working Demo: http://tyrexi.us/EmailTrack

Working Demo: http://tyrexi.us/EmailTrack2

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
