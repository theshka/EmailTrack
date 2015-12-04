<?php namespace theshka\EmailTrack;
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
 * @version    0.3.6
 */

 /**
  * Class Settings
  *
  * This is the class settings. The constants may be changed
  * to suit your websites structure and URLs. Avoid using
  * relative paths, if possible.
  */

 /**
  * SQLITE_PATH is the path to the SQLITE database
  */
 define('SQLITE_PATH',   '../data/_main.db');

 /**
  * IMAGE_PATH is the path to the blank .gif image
  */
 define('IMAGE_PATH',    'images/blank.gif');

 /**
  * REDIRECT_TO is the path to redirect if proper parameters are not supplied.
  */
 define('REDIRECT_TO',   'http://heshka.com');


/**
 * EmailTrack Class
 *
 * This class is responsible for creating or connecting to
 * a SQLite database using PDO. It accepts $_GET parameters
 * logs the parameters in the database, and outputs a blank
 * graphic to be included in an email or other script.
 *
 * @param bool $log log must be set, and must be true
 * @param string $email url encoded string containing email address
 * @param string $subject url encoded string containing the email subject
 * @return resource returns a blank gif to the user
 */
class EmailTrack
{
    /**
     * Class Variables
     *
     * These variables store the current instance of the database
     * and the $_GET parameters supplied in the call to the script.
     */

    /**
     * $db is this instance of the SQLite database
     */
    private $db;

    /**
     * $email is this instance of the $_GET['email'] parameter
     */
    private $email;

    /**
     * $subject is this instance of the $_GET['subject'] parameter
     */
    private $subject;

    /**
     * Constructor Method
     *
     * The constructor calls the connectDB method, which initilizes a
     * PDO connection to the database, and then calls runApplication()
     *
     * @param null this method accepts no parameters
     * @return null this method returns nothing
     */
    public function __construct()
    {
        //Run the application
        $this->runApplication();
    }

    /**
     * Database Connect
     *
     * The connectDB method simply checks for the presence
     * of a SQLite database, if none is found, it creates
     * the database and populates it with our schema.
     *
     * @param null this method accepts no parameters
     * @return resource returns a connection to SQLite DB
     */
    private function connectDB()
    {
        try {
            //Create/connect to SQLite database
            $this->db = new \PDO('sqlite:'.SQLITE_PATH);

            //Set errormode to exceptions
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            //Create tables if they dont' exist
            $this->db->exec('CREATE TABLE IF NOT EXISTS `email_log` (
            `id`		INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `email`		TEXT,
            `subject`	TEXT,
            `opened`	TEXT NOT NULL
            )');
        } catch (\PDOException $e) {
            // Print PDOException message
            echo $e->getMessage();
        }
    }

    /**
     * Assign $_GET Variables
     *
     * The getVars() method simply stores the current $_GET
     * variables in the class instance of the same variables.
     *
     * @param null this method accepts no parameters
     * @return object returns the $_GET variables as object
     */
    private function getVars()
    {
        //Assign the user and subject to variables
        $this->email = $_GET['email'];
        $this->subject = $_GET['subject'];
    }

    /**
     * Validate $_GET Variables
     *
     * The checkVars() method simply checks the supplied $_GET
     * variables for valid input. Returns true if valid.
     *
     * @param string $_GET this method accepts $_GET data
     * @return bool will return true if input is valid
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

    /**
     * Check For Exisitng Entry
     *
     * The checkIfEntryExists() method prepares a PDO statement
     * and queries the SQLite database to determine if this
     * email has already been inserted in the database.
     *
     * @param null this method accepts no parameters
     * @return bool returns true if email has already been tracked
     */
    private function checkIfEntryExists()
    {
        //Prepare the statement
        $duplicate = 'SELECT email, subject
                      FROM   email_log
                      WHERE  email=:email AND subject=:subject
                      LIMIT 1';
        $stmt = $this->db->prepare($duplicate);

        // Bind parameters to statement variables
        $stmt->bindValue(':email', $this->email);
        $stmt->bindValue(':subject', $this->subject);

        //Execute query...
        $stmt->execute();

        //Make sure we are not duplicating the insert!
        $result_row = $stmt->fetchObject();

        if ($result_row) {
            //The email has already been tracked.
            return true;
        }

        //The email has not been tracked.
        return false;
    }

    /**
     * Insert New Entry
     *
     * The insertNewEntry method prepares a PDO statement
     * and inserts a query in to the SQLite database. We use
     * the PHP gmdate function to avoid timezone errors.
     *
     * @param null this method accepts no parameters
     * @return bool returns true if the insert query is executed
     */
    private function insertNewEntry()
    {
        //Prepare the statement
        $sql = 'INSERT INTO email_log (email, subject, opened)
                   VALUES (:email, :subject, :opened)';
        $stmt = $this->db->prepare($sql);

        //Get the date
        $date = gmdate('Y-m-d H:i:s');

        // Bind parameters to statement variables
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':subject', $this->subject);
        $stmt->bindParam(':opened', $date);

        //Execute the query
        if ($stmt->execute()) {
            //The email has been tracked.
            return true;
        }
        //There email has not been tracked.
        return false;
    }

    /**
     * Output Ghost Image
     *
     * The outputHeaders() method calculates the size of our
     * ghost image, prepares the browser-headers, and sends
     * our ghost graphic to the users browser.
     *
     * @param null this method accepts no parameters
     * @return resource this method outputs an image/gif
     */
    private function outputHeaders()
    {
        //Get the absolute/relative path to the image
        $image = IMAGE_PATH;

        //Get the filesize of the image for headers
        $filesize = filesize($image);

        //Now actually output the image.
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

    /**
     * Application Logic
     *
     * The runApplication method is the main controller logic
     * for our application. It will determine if the necessary
     * $_GET parameters are set, and not empty. If validated,
     * it will check if an entry already exists, if not, it will
     * insert it in the database. If so, it will continue on to
     * output the ghost image regardless.
     */
    public function runApplication()
    {
        //Connect the database
        $this->connectDB();

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
