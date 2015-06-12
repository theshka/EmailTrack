# EmailTrack
## This simple PHP class outputs a ghost image and uses PDO & SQLite to track whether or not an email has been seen.

## INSTALLATION
- Create a SQLite database using the supplied table schema `_main.db.sqlite`
- Full instructions and examples for how to use the class are in `index.php` & `index2.php`
- The class resides in `static/images/blank.php`
- Upload the `blank.gif` and `blank.php` to the same directory.
- You may need to edit the path to the SQLite database.
- SQLite Databases can be downloaded with a direct link if you do not take precautions
to secure the file. You can so this with `.htaccess` or by making the file hidden.

Working Demo: http://tyrexi.us/EmailTrack
Working Demo: http://tyrexi.us/EmailTrack2

## LICENSE
This work is licensed under a Creative Commons Attribution-ShareAlike 4.0 International License.
http://creativecommons.org/licenses/by-sa/4.0/

THIS SOFTWARE IS BEING PROVIDED "AS IS", WITHOUT ANY EXPRESS OR IMPLIED WARRANTY.  
IN PARTICULAR, THE AUTHOR DOES NOT MAKE ANY REPRESENTATION OR WARRANTY OF ANY KIND
CONCERNING THE MERCHANTABILITY OF THIS SOFTWARE OR ITS FITNESS FOR ANY PARTICULAR PURPOSE.


## Contributing
* Create a Fork of the main branch.
* Clone the repository `$ git clone http://github.com/yourusername/EmailTrack`
* Add a connection to the repository.`$ git remote add origin http://github.com/theshka/EmailTrack`
* Make changes to files.
* `git add` and `git commit` those changes
* `git push` them back to github. These will go to your version of the repository.
* Submit a pull-request
