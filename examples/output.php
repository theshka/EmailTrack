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
 * @version    0.00.30
 */

 /**
  * Example 1
  *
  * This example demonstrates how the class is to be used.
  * It will output the tracking graphic, and display all
  * database results in an html table.
  */


/**
 * Get your variables.
 *
 * These could come from a form, database, hardcoded, or elsewhere..
 * this example will just generate a random test email address and subject.
 * each time you refresh the page you can simulate someone opening
 * the email containing the ghost graphic.
 */
$subject = randomSubject();
$email = 'test_'.substr(md5(rand()), 0, 7).'@example.com';

/**
 * Build the ghost graphic.
 *
 * Ideally, you would inlcude the graphic in a script that is emailing
 * something using sendmail, phpMailer, or another transport class.
 */
$trackingGraphic = '<img src="../src/blank.php?log=true&subject='.urlencode($subject).'&email='.urlencode($email).'" alt="EmailTrack"/>';

/**
 * Output the tracking graphic.
 *
 * Ideally, this graphic would have been inlucded in the body of an email.
 * the loading of the graphic will trigger the class and insert the $_GET
 * data in the database. Thus, knowing the recipient has seen the message.
 */
echo $trackingGraphic;

/**
 * Output the database table...
 *
 * Ideally, you would have a view in your application where the tracking
 * information would be output. The possibilites here are endless, however,
 * I will just quickly output the data from the database.
 */
function outputHTML()
{
    //Output the HTML table...
    echo '<table class="u-full-width">';
    echo '<caption><h2>SQLite Database Output</h2></caption>';
    echo '<thead>';
    echo '<th>Email</th>';
    echo '<th>Subject</th>';
    echo '<th>Opened</th>';
    echo '</thead>';
    echo '<tbody>';

    $db = new PDO('sqlite:../data/_main.db');
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
 * Reset Database
 *
 * Used in the example to reset the example database,
 * you do not need to keep this function in your app.
 *
 * @param resource $db the database connection
 */
function resetDatabase($db)
{
    //if database > 15 , drop table.
    $result = $db->query('SELECT COUNT(*) FROM email_log');
    foreach ($result as $row) {
        if ($row[0] > 15) {
            //Reset the table
            $delete = $db->exec('DELETE FROM email_log');
            $vacuum = $db->exec('VACUUM');
        }
    }
}
/**
 * Generate Random Subject
 *
 * Used in the example to create a random subject,
 * you do not need to keep this function in your app.
 *
 * @param string a short sentence is returned
 */
function randomSubject()
{
	//Parts of Speach & Words
    $articles = array("my", "the", "their", "a", "this", "that", "his", "her");
	$adjective = array("lame", "giant", "good", "bad", "angry", "scary", "magnificient", "interesting");
    $noun = array("dog", "bear", "cat", "dude", "bird", "girl", "boy", "person");
    $verb = array("fell", "walked", "lived", "went", "flew", "ran", "hustled", "hid");
	$preposition= array("through", "around", "behind", "near", "over", "below", "beneath", "above");

	//Randomize the array
    $random_article = array_rand($articles, 2);
    $random_adjective = array_rand($adjective, 2);
    $random_noun = array_rand($noun, 2);
    $random_verb = array_rand($verb, 2);
	$random_perposition = array_rand($preposition, 2);

	//Build the sentence.
	$sentence = ucfirst($articles[$random_article[0]]) . " "
	. $adjective[$random_adjective[0]] . " "
	. $noun[$random_noun[0]] . " "
	. $verb[$random_verb[0]] . " "
	. $preposition[$random_perposition[0]] . " "
	. $articles[$random_article[1]] . " "
	. $noun[$random_noun[1]] . ".";

	//Return the sentence.
	return $sentence;
}
?>

<!-- // HTML //-->
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title> EmailTrack by tHeshka - Output Example</title>
    <meta name="description" content="This class will output a ghost image and update a SQLite database.">
    <meta name="author" content="Tyler Heshka <theshka>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css">
    <link rel="stylesheet" href="assets/custom.css">

    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="twelve columns">
                <div class="row">
                    <div class="twelve columns">
                        <a href="form.php" class="button" title="EmailTrack Test">Send Mail</a>
                        <a href="../index.html" class="button button-primary u-pull-right" title="EmailTrack Demo">Main</a>
                    </div>
                </div>
                <div class="row">
                    <?php outputHTML(); ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
