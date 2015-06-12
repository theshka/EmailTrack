<?php

/**
 * A simple PHP class to track whether or not an email was opened.
 *
 * EmailTrack
 *
 * LICENSE: THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
 * FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @category   HTML,PHP5,Databases,Tracking
 * @author     Tyler Heshka <tyler@heshka.com>
 * @see        http://keybase.io/theshka
 * @license    http://opensource.org/licenses/MIT
 * @version    0.00.20
 */

// Settings
define('SQLITE_PATH',   '../../application/data/_main.db');
define('IMAGE_PATH',    'blank.gif');
define('REDIRECT_TO',   'https://heshka.com');

//Begin Class
class EmailTrack
{
    private $db;
    private $email;
    private $subject;

    /*
    * The constructor initilizes a PDO connection to the database, and
    * then calls the runApplication method, which calls the necessary
    * functions to track when a user downloads the ghost image.
    *
    * @param: null this method accepts no parameters.
    * @return null this method returns nothing.
    */
    public function __construct()
    {
        //Connect the database
        $this->connectDB();
        //Run the application
        $this->runApplication();
    }

    /*
    * The connectDB method simply checks for the presence
    * of a SQLite database, if none is found, it creates
    * the database, and populates it with our schema.
    *
    * @param: null this method accepts no parameters.
    * @return object returns a connection to SQLite DB.
    */
    private function connectDB()
    {
        try {
            //Create/connect to SQLite database
            $this->db = new PDO('sqlite:'.SQLITE_PATH);

            //Set errormode to exceptions
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //Create tables if they dont' exist
            $this->db->exec('CREATE TABLE IF NOT EXISTS `email_log` (
            `id`		INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `email`		TEXT,
            `subject`	TEXT,
            `opened`	TEXT NOT NULL
            )');
        } catch (PDOException $e) {
            // Print PDOException message
            echo $e->getMessage();
        }
    }

    /*
    * The getVars method simply stores the current $_GET
    * variables in the class instance of the same variables.
    *
    * @param: null this method accepts no parameters.
    * @return string returns the $_GET variables as object
    */
    private function getVars()
    {
        //Assign the user and subject to variables
        $this->email = $_GET['email'];
        $this->subject = $_GET['subject'];
    }

    /*
    * The checkVars method simply checks the supplied $_GET
    * variables for valid input. Retuens true if valid.
    *
    * @param: $_GET this method accepts get data.
    * @return bool returns true if input is valid.
    */
    private function checkVars()
    {
        if (($_GET['log'] == 'true')
            && !empty($_GET['log'])
            && !empty($_GET['email'])
            && !empty($_GET['subject'])) {
            //Valid input
            return true;
        }
        //Invalid input
        return false;
    }

    /*
    * The checkIfEntryExists method prepares a PDO statement
    * and queries the SQLite database to determine if this
    * email has already been inserted in the database.
    *
    * @param: null this method accepts no parameters.
    * @return bool returns true if email has already been tracked.
    */
    private function checkIfEntryExists()
    {
        //Prepare the statement
        $duplicate = 'SELECT email, subject
                      FROM email_log
                      WHERE  email=:email AND subject=:subject
                      LIMIT 1';
        $stmt = $this->db->prepare($duplicate);

        // Bind parameters to statement variables
        $stmt->bindValue(':email', $this->email);
        $stmt->bindValue(':subject', $this->subject);

        //Execute query...
        $stmt->execute();

        //Make sure we aren't duplicating the insertion!
        $result_row = $stmt->fetchObject();

        if ($result_row) {
            //The email has already been tracked.
            return true;
        }

        //The email has not been tracked.
        return false;
    }

    /*
    * The insertNewEntry method prepares a PDO statement
    * and inserts a query in to the SQLite database. We use
    * the PHP gmdate function to avoid timezone errors.
    *
    * @param: null this method accepts no parameters.
    * @return bool returns true if the insert query is executed.
    */
    private function insertNewEntry()
    {
        //Prepare the statement
        $insert = 'INSERT INTO email_log (email, subject, opened)
        VALUES (:email, :subject, :opened)';
        $stmt = $this->db->prepare($insert);

        // Bind parameters to statement variables
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':subject', $this->subject);
        $stmt->bindParam(':opened', gmdate('Y-m-d H:i:s'));

        //Execute the query
        if ($stmt->execute()) {
            //The email has been tracked.
            return true;
        }
        //There email has not been tracked.
        return false;
    }

    /*
    * The outputHeaders method calculates the size of our
    * ghost image, prepares the browser-headers, and sends
    * our ghost graphic to the users browser.
    *
    * @param: null this method accepts no parameters.
    * @return bool returns true if the insert query is executed.
    */
    private function outputHeaders()
    {
        //Get the absolute/relative path to the image
        $image = IMAGE_PATH;

        //Get the filesize of the image for headers
        $filesize = filesize($image);

        //Now actually output the image requested, while disregarding if the database was affected
        header('Content-Type: image/gif');
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Disposition: attachment; filename="blank.gif"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: '.$filesize);
        readfile($image);
    }

    /*
    * The runApplication method is the main controller logic
    * for our application. It will determine if the necessary
    * $_GET parameters are set, and not empty. If validated,
    * it will check if an entry already exists, if not, it will
    * insert it in the database. If so, it will continue on to
    * output the ghost image regardless.
    *
    * @param: null this method accepts no parameters.
    * @return bool returns true if the insert query is executed.
    */
    public function runApplication()
    {
        //Check for valid GET parameters
        if ($this->checkVars() === true) {
            //Get the variables
            $this->getVars();

            //Check for duplicate entry.
            if (!$this->checkIfEntryExists()) {
                //Insert the new entry.
                $this->insertNewEntry();
            }

            //Output the headers
            $this->outputHeaders();
        } else {
            //Insufficent $_GET parameters supplied.
            header('Location: '.REDIRECT_TO);
        }
    }
}

//Create an instance of the class.
$application = new EmailTrack();
