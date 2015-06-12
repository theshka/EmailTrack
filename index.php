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


/**
 * Get your variables.
 *
 * These could come from a form, database, hardcoded, or elsewhere..
 * this example will just generate a random test email address.
 * each time you refresh the page you can simulate someone opening
 * the email containing the ghost graphic.
 */
$subject = 'Testing...';
$email = 'test'.rand().'@example.com';

/**
 * Build the ghost graphic.
 *
 * Ideally, you would inlcude the graphic in a script that is emailing
 * something using sendmail, phpMailer, or another transport class.
 */
$trackingGraphic = '<img src="static/images/blank.php?log=true&subject='.urlencode($subject).'&email='.urlencode($email).'" alt="EmailTrack"/>';

/**
 * Output the tracking graphic.
 * Ideally, this graphic would have been inlucded in the body of an email.
 * the loading of the graphic will trigger the class and insert the $_GET
 * data in the database. Thus, knowing the recipient has seen the message.
 */
echo $trackingGraphic;

/**
 * Output the database table...
 * Ideally, you would have a view in your application where the tracking
 * information would be output. The possibilites here are endless, however,
 * I will just quickly output the data from the database.
 */
function outputHTML()
{
    //Output the HTML table...
    echo '<table>';
    echo '<caption>SQLite Database Output</caption>';
    echo '<thead>';
    echo '<th>Email</th>';
    echo '<th>Subject</th>';
    echo '<th>Opened</th>';
    echo '</thead>';
    echo '<tbody>';

    $db = new PDO('sqlite:./application/data/_main.db');
    $result = $db->query('SELECT email, subject, opened FROM email_log');
    foreach ($result as $row) {
        echo '<tr>';
        echo '<td>'.$row['email'].'</td>';
        echo '<td>'.$row['subject'].'</td>';
        echo '<td>'.$row['opened'].'</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';

    //Reset the database
    resetDatabase($db);
}

/**
 * Used in the example.
 * ...You do not need to keep this function.
 *
 * @param resource $db the database connection
 */
function resetDatabase($db)
{
    //if database > 20 , drop table.
    $result = $db->query('SELECT COUNT(*) FROM email_log');
    foreach ($result as $row) {
        if ($row[0] > 20) {
            //Reset the table
            $delete = $db->exec('DELETE FROM email_log');
            $vacuum = $db->exec('VACUUM');
        }
    }
}
?>

<!-- // HTML //-->
<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>EmailTrack by theshka</title>
    <meta name="description" content="This class will output a ghost image and update a SQLite database.">
    <meta name="author" content="Tyler Heshka <theshka>">

    <style>
    table {
        width: 100%;
    }
    table, th, td {
        border: 1px solid black;
    }
    </style>
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>

<body>
    <a href="index2.php" title="EmailTrack Test">Send Mail</a>
    <br>
    <?php outputHTML(); ?>
</body>
</html>
