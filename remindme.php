<?php
session_start();
include 'config.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sets the default character set to be used when sending data from and to the database server
$conn->set_charset("utf8");

// All datetime data is stored in the UTC
$default_tz = date_default_timezone_set('UTC');

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {
        case 'register' : 
			register(); 
			break;
        case 'profile' : 
			profile(); 
			break;
        case 'save' : 
			save(); 
			break;
        case 'time_zones' :
            time_zones();
            break;
        case 'login' : 
			login(); 
			break;
        case 'logout' : 
			logout(); 
			break;
    }
}

// Registration
function register()
{
	global $conn;

	$sql = "SELECT id, username, email 
			FROM users
			WHERE username='".$_POST['username']."' OR email='".$_POST['email']."'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
        $json = array('success'=>0);
		echo json_encode($json);
	} 
	else {
        // Random confirmation code 
        $confirm_code = md5(uniqid(mt_rand(), true));
        $date_registered = date('Y-m-d H:i:s');

        $sql = "INSERT INTO users (first_name, last_name, email, username, password, confirm_code, date_registered)
                VALUES ('".$_POST['firstname']."', '".$_POST['lastname']."', '".$_POST['email']."', '".$_POST['username']."', '".md5($_POST['password'])."', '".$confirm_code."', '".$date_registered."')";
        if ($conn->query($sql) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        else {
            // Send mail form      
            $link = 'http'.(($_SERVER['SERVER_PORT'] == '443') ? 's' : '').'://'. $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']);
            $link .= '/confirmation.php?email='.$_POST['email'].'&code='.$confirm_code;

            $to = $_POST['email'];
            $subject = "remindMe account verification";
            $message = '<html>
                            <head>
                                <title>remindMe account verification</title>
                            </head>
                            <body>
                            <table style="width: 100%;">
                              <tr>
                                <td>
                                  <center>
                                    <!--[if mso]>
                                    <table style="width: 600px;"><tr><td>
                                    <![endif]-->
                                    <div style="max-width: 700px; margin: 0 auto;">
                                      <table style="text-align: left;">
                                        <tr>
                                          <td>              
                                            <table style="background:#f2f8ea; border-collapse:collapse; border-spacing:0; border:1px solid #426514; padding:0; margin:0 auto; text-align:left; vertical-align:top">
	                                            <tbody>
		                                            <tr style="padding:0; text-align:left; vertical-align:top" align="left">
                                                        <td style="border-collapse:collapse!important; color:#333333; font-family:Helvetica,Arial,sans-serif; font-size:14px; line-height:20px; margin:0; padding:0; text-align:left; vertical-align:top; word-break:break-word" align="left" valign="top">
				                                            <div style="font-size:14px; font-weight:normal; line-height:20px; margin:20px">
					                                            <p style="color:#426514; margin:0 0 10px; padding:0">
						                                            Hi <strong>'.$_POST['firstname'].' '.$_POST['lastname'].'</strong>!
					                                            </p>
					                                            <p style="color:#333333; margin:0 0 10px; padding:0;">
						                                            Please, verify your email address (<a href="mailto:'.$_POST['email'].'" style="color:#333333; text-decoration:none" target="_blank">'.$_POST['email'].'</a>).
					                                            </p>

					                                            <div style="color:#ffffff;padding:20px 0 33px;text-align:center" align="center">
                                                                    <span style="display:inline-block; background-color:#3A521B; color:#ffffff;">
                                                                        <a href="'.$link.'" style="display:inline-block; text-decoration:none; font-family: Helvetica, Arial, sans-serif; font-size:17px; font-weight:bold; color:#ffffff; border-color: #3A521B; border-width:12px 22px; border-style:solid; white-space:nowrap;" target="_blank">Verify email address</a>
                                                                    </span>						                                
					                                            </div>

					                                            <hr style="background:#dddddd; border:none; color:#dddddd; min-height:1px; margin:10px 0 20px">

					                                            <p style="color:#777777; font-size:12px; line-height:16px; margin:0 0 10px; padding:0;">
					                                                Button not working? Paste the following link into your browser:<br>
					                                                <a href="'.$link.'" style="color:#4183c4;text-decoration:underline" target="_blank">'.$link.'</a>
					                                            </p>

					                                            <p style="color:#777777; font-size:12px; line-height:16px; margin:0; padding:0">
					                                                You\'re receiving this email because you recently created a new remindMe account or add a new email address. If this wasn\'t you, please ignore this email.
					                                            </p>
                                                            </div>
                                                        </td>
                                                    </tr>
	                                            </tbody>
                                            </table>
                                          </td>
                                        </tr>
                                      </table>
                                    </div>
                                  </center>
                                  <!--[if mso]>
                                  </td></tr></table>
                                  <![endif]--> 
                                </td>
                              </tr>
                            </table>
                            </body>
                        </html>';
        
            // To send HTML mail, the Content-type header must be set
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            // Additional headers
            $headers .= 'From: remindME <milos@evropesma.org>' . "\r\n";
            //$headers = "From:" . $from;
            mail($to, $subject, $message, $headers);

            $json = array('success'=>1);
            echo json_encode($json);       
            
            // Close the connection
            $conn->close();
        }

	}

}

function profile()
{
	global $conn;

	$sql = "SELECT *
			FROM users
			WHERE username='".$_SESSION["username"]."'
            LIMIT 1";
	$result = $conn->query($sql);

    // Close the connection
    $conn->close();

	if ($result->num_rows == 1) {
        while($row = $result->fetch_assoc()) {
            //$date = date("d.m.Y, H:i:s", strtotime($row['date_registered']));
            $date = $row['date_registered'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $email = $row['email'];
            $username = $row['username'];
            $myTimeZone = $row["time_zone"];
        }

        $date_reg = new DateTime($date);
        // Convert to user timezone
        $date_reg->setTimezone(new DateTimeZone($myTimeZone));
        // Show the time in user timezone
        $user_time = $date_reg->format('d.m.Y, H:i:s');

        $json = array('date'=>$user_time, 'first_name'=>$first_name, 'last_name'=>$last_name, 'email'=>$email, 'username'=>$username);
		echo json_encode($json);
	}             
}


function save()
{
	global $conn;

	$sql = "SELECT id, username, email, password
			FROM users
			WHERE username='".$_SESSION["username"]."'
            LIMIT 1";
	$result = $conn->query($sql);

	if ($result->num_rows == 1) {
		while($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $current_email = $row["email"];
            $current_password = $row["password"];
		}

        if ($_POST['email'] == $current_email) {
            $sql = "UPDATE users
                    SET first_name='".$_POST['firstname']."', last_name='".$_POST['lastname']."', email='".$_POST['email']."', password='".(empty($_POST['password'])? $current_password : md5($_POST['password']))."', time_zone='".$_POST['timezone']."'
                    WHERE id='".$id."'";
            $conn->query($sql);

            $json = array('success'=>1);
            echo json_encode($json); 

        } else {
            $sql = "SELECT id, username, email 
			        FROM users
			        WHERE email='".$_POST['email']."'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $json = array('success'=>0);
                echo json_encode($json);
            } 
            else {
                $confirm_code = md5(uniqid(mt_rand(), true));
                $sql2 = "UPDATE users
                         SET first_name='".$_POST['firstname']."', last_name='".$_POST['lastname']."', email='".$_POST['email']."', password='".(empty($_POST['password'])? $current_password : md5($_POST['password']))."', is_activated='0', confirm_code='".$confirm_code."', time_zone='".$_POST['timezone']."'
                         WHERE id='".$id."'";
                $conn->query($sql2);

                $json = array('success'=>2);
                echo json_encode($json); 

                // Send mail form      
                $link = 'http'.(($_SERVER['SERVER_PORT'] == '443') ? 's' : '').'://'. $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']);
                $link .= '/confirmation.php?email='.$_POST['email'].'&code='.$confirm_code;

                $to = $_POST['email'];
                $subject = "remindMe account verification";
                $message = '<html>
                                <head>
                                    <title>remindMe account verification</title>
                                </head>
                                <body>
                                <table style="width: 100%;">
                                  <tr>
                                    <td>
                                      <center>
                                        <!--[if mso]>
                                        <table style="width: 600px;"><tr><td>
                                        <![endif]-->
                                        <div style="max-width: 700px; margin: 0 auto;">
                                          <table style="text-align: left;">
                                            <tr>
                                              <td>              
                                                <table style="background:#f2f8ea; border-collapse:collapse; border-spacing:0; border:1px solid #426514; padding:0; margin:0 auto; text-align:left; vertical-align:top">
	                                                <tbody>
		                                                <tr style="padding:0; text-align:left; vertical-align:top" align="left">
                                                            <td style="border-collapse:collapse!important; color:#333333; font-family:Helvetica,Arial,sans-serif; font-size:14px; line-height:20px; margin:0; padding:0; text-align:left; vertical-align:top; word-break:break-word" align="left" valign="top">
				                                                <div style="font-size:14px; font-weight:normal; line-height:20px; margin:20px">
					                                                <p style="color:#426514; margin:0 0 10px; padding:0">
						                                                Hi <strong>'.$_POST['firstname'].' '.$_POST['lastname'].'</strong>!
					                                                </p>
					                                                <p style="color:#333333; margin:0 0 10px; padding:0;">
						                                                Please, verify your email address (<a href="mailto:'.$_POST['email'].'" style="color:#333333; text-decoration:none" target="_blank">'.$_POST['email'].'</a>).
					                                                </p>

					                                                <div style="color:#ffffff;padding:20px 0 33px;text-align:center" align="center">
                                                                        <span style="display:inline-block; background-color:#3A521B; color:#ffffff;">
                                                                            <a href="'.$link.'" style="display:inline-block; text-decoration:none; font-family: Helvetica, Arial, sans-serif; font-size:17px; font-weight:bold; color:#ffffff; border-color: #3A521B; border-width:12px 22px; border-style:solid; white-space:nowrap;" target="_blank">Verify email address</a>
                                                                        </span>						                                
					                                                </div>

					                                                <hr style="background:#dddddd; border:none; color:#dddddd; min-height:1px; margin:10px 0 20px">

					                                                <p style="color:#777777; font-size:12px; line-height:16px; margin:0 0 10px; padding:0;">
					                                                    Button not working? Paste the following link into your browser:<br>
					                                                    <a href="'.$link.'" style="color:#4183c4;text-decoration:underline" target="_blank">'.$link.'</a>
					                                                </p>

					                                                <p style="color:#777777; font-size:12px; line-height:16px; margin:0; padding:0">
					                                                    You\'re receiving this email because you recently created a new remindMe account or add a new email address. If this wasn\'t you, please ignore this email.
					                                                </p>
                                                                </div>
                                                            </td>
                                                        </tr>
	                                                </tbody>
                                                </table>
                                              </td>
                                            </tr>
                                          </table>
                                        </div>
                                      </center>
                                      <!--[if mso]>
                                      </td></tr></table>
                                      <![endif]--> 
                                    </td>
                                  </tr>
                                </table>
                                </body>
                            </html>';
            
                // To send HTML mail, the Content-type header must be set
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                // Additional headers
                $headers .= 'From: remindME <milos@evropesma.org>' . "\r\n";
                //$headers = "From:" . $from;
                mail($to, $subject, $message, $headers);
            }
        }
    }
    // Close the connection
    $conn->close();
}

// Time zones
function time_zones() 
{
    global $conn;

    $sql = "SELECT id, time_zone
		    FROM users
		    WHERE username='".$_SESSION["username"]."'
            LIMIT 1";
	$result = $conn->query($sql);

    // Close the connection
    $conn->close();

	if ($result->num_rows == 1) {
		while($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $myTimeZone = $row["time_zone"];
		}
    }

    $timezone_identifiers = DateTimeZone::listIdentifiers();    $options = '';    foreach ($timezone_identifiers as $tz) {        // Calculate time zone offset        $offset = getTimeZoneOffset($tz);
        $offset = (substr($offset,0,1) == "-" ? "(GMT" : " (GMT+") . $offset . ")";        $displayValue = htmlentities( str_replace('_', ' ', $tz).' '.$offset );        $selected = ( ($tz == $myTimeZone) ? ' selected="selected"' : null );        $options .= '<option value="' .$tz. '"' . $selected . '>'.$displayValue. '</option>';    }
    $json = array('options'=>$options);
    echo json_encode($json);  
}

// Calculates the offset from UTC for a given timezone
function getTimeZoneOffset($timeZone) {
    global $default_tz;

    $dateTimeZoneUTC = new DateTimeZone(date_default_timezone_get());
    $dateTimeZoneCurrent = new DateTimeZone($timeZone);

    $dateTimeUTC = new DateTime("now",$dateTimeZoneUTC);
    $dateTimeCurrent = new DateTime("now",$dateTimeZoneCurrent);

    $offset = ($dateTimeZoneCurrent->getOffset($dateTimeUTC))/3600;
    return $offset;
}

// Login function
function login()
{
	global $conn;

	$sql = "SELECT * 
			FROM users
			WHERE username='".$_POST['username']."' AND password='".md5($_POST['password'])."'";
	$result = $conn->query($sql);

    // Close the connection
    $conn->close();

	if ($result->num_rows == 1) {
        while($row = $result->fetch_assoc()) {
            if ($row['is_activated'] == 1)
            {
                $_SESSION["username"] = $row['username'];
                $_SESSION["password"] = $row['password'];

                $json = array('success'=>1);
                echo json_encode($json);  
            } else {
                $json = array('message'=>'Your email has not yet verified. Check your inbox to find verification link.', 'success'=>0);
                echo json_encode($json);
            }
        }   
	} 
	else {
        $json = array('message'=>'Wrong username or password', 'success'=>0);
		echo json_encode($json);
    }
}

// Logout function
function logout()
{
    // remove all session variables
    session_unset(); 

    // destroy the session 
    session_destroy(); 

    $json = array('success'=>1);
    echo json_encode($json);
}

// Email verification
function verify()
{
	global $conn;

    if (isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['code']) && !empty($_GET['code'])) {
        // Verify data
        $sql = "SELECT *
			    FROM users
			    WHERE email='".$_GET['email']."' AND confirm_code='".$_GET['code']."' AND is_activated=0";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $sql = "UPDATE users
                    SET is_activated='1'
                    WHERE email='".$_GET['email']."' AND confirm_code='".$_GET['code']."' AND is_activated=0";
            $result = $conn->query($sql);

            // Close the connection
            $conn->close();

            return '<div class="alert alert-success">
                        <strong>Success!</strong> You have successfully verified the e-mail. Now you can log in to your account.
                    </div>';
        } else {
            // Close the connection
            $conn->close();

            return '<div class="alert alert-danger">
                        <strong>Danger!</strong> Verification failed. The url is either invalid or you already have activated your account. Check your email again and click on the activation link.
                    </div>';
        }
    } else {
        // Invalid approach
        return '<div class="alert alert-danger">
                        <strong>Danger!</strong> Verification failed. The required data for verification missing.
                </div>';
    }


}

?>