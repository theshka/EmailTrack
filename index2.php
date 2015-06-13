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
 * Index 2/Example 2
 *
 * This example is a functional script for injecting
 * the tracking graphic, and sending an email using
 * PHP's mail() command (sendmail) and a html form.
 * You can view the result on index/example1...
 */

/**
 * Are we sending the message?
 */
if ($_POST['send'] == true) {
    /**
     * Get your variables.
     */
    $to = $_POST['to'];
    $from = 'example@example.com';
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    /**
     * Validate Input.
     */
    if (!empty($to)
        && !filter_var($to, FILTER_VALIDATE_EMAIL) === false
        && !empty($from)
        && !filter_var($from, FILTER_VALIDATE_EMAIL) === false
        && !empty($subject)
        && !empty($message)) {

        /**
         * Inject the tracking graphic
         */
        $message .= '<img src="static/images/blank.php?log=true&subject='.urlencode($subject).'&email='.urlencode($to).'" alt="EmailTrack"/>';

        /**
         * Setup mail headers
         */
        $headers = 'From: '.$from.'\r\n';
        $headers .= 'MIME-Version: 1.0'.'\r\n';
        $headers .= 'Content-type:text/html;charset=UTF-8'.'\r\n';

        /**
         * Send the message
         *
         * I am commenting out this line, so that the server
         * I host this example on is not actually sending
         * anything. Replace if (true == true) to use sendmail.
         */
        //if(mail($to, $subject, $message, $headers)){
        if (true === true) {
            //Success

            /**
             * Output a fake email
             *
             * Since we're not actually sending mail in the demo,
             * we will output the fake message here so that the
             * tracking graphic is called and shows in the DB.
             */
            echo '<div class="feedback">'.'Success!'.'</div>';
            echo '<pre>';
            echo 'Headers: '.$headers.'<br>';
            echo 'From: "'.$from.'"<br>';
            echo 'To: "'.$to.'"<br>';
            echo 'Subject: "'.$subject.'"<br>';
            echo 'Message: "'.$message.'<br>';
            echo '</pre>';
        } else {
            //Failure
            echo '<div class="feedback">'.'Failure.'.'</div>';
        }
    } else {
        //Validation Error
        echo '<div class="feedback">'.'Complete all fields, and enter a valid email address.'.'</div><br>';
    }
}
?>
<!-- // HTML //-->
<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>EmailTrack - Example 2 - by theshka</title>
    <meta name="description" content="This class will output a ghost image and update a SQLite database.">
    <meta name="author" content="Tyler Heshka <theshka>">

    <style>
    .feedback {
        color: red;
        font-size: 2em;
    }
    form, input, textarea {
        width: 100%;
    }
    </style>
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>

<body>
    <a href="index.php" title="EmailTrack Test">View Database</a>
    <br><br>
    <form action="<?=$_SERVER['PHP_SELF'];?>" method="post">
        <fieldset>
            <legend>Test the EmailTrack class...</legend>
            <div>
                <label for="subject">To:</label>
                <input type="text" name="to" value="<?='test_'.substr(md5(rand()), 0, 7).'@example.com';?>" />
            </div>
            <div>
                <label for="subject">Subject:</label>
                <input type="text" name="subject" placeholder="Type subject here..." />
            </div>
            <div>
                <label for="message">Message:</label>
                <textarea name="message"></textarea>
            </div>

            <input type="hidden" name="send" value="true" />
            <input type="submit" name="submit" value="Submit" />
        </fieldset>
    </form>
</body>
</html>
