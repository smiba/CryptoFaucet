    DOGE in faucet:
    <?php
 error_reporting(E_ALL); //show errors
ini_set('display_startup_errors',1); //show errors 
ini_set('display_errors',1); //show errors
error_reporting(-1); //show errors    

//Made by smiba - Feel free to ask support on reddit from me... But no promises, please use google since that is your best friend!
    //Make your SQL database like this: http://screenshots.bartstuff.eu/1387003406-Z14wbpSuaCIvNP9VvV2R.png
    $version = "1.1.1";
    require_once 'jsonRPCClient.php'; //Get this from http://jsonrpcphp.org/?page=download&lang=en - the light is good enough
    require_once 'recaptchalib.php'; //Get this from ReCapcha's website
    $publickey = ""; //ReCAPCHA Public key here
    $privatekey = ""; //ReCAPCHA Private key here
    $doge = new jsonRPCClient('http://dogecoinrpc:DOGE_COIN_PW_HERE@127.0.0.1:22555/');
    print_r($doge->getbalance(""));
    echo "</b> - Please donate:".$doge->getaccountaddress("")."<br /><font size='2'>Set text here or something</font><br />";
     
    $username = $_POST['address'];
    $ip = $_SERVER['REMOTE_ADDR'];
    if(!empty($_POST['address'])) {
            if($doge->getbalance("") < 10){
                    echo "Dry faucet, please donate";
            }else{
                    $check = $doge->validateaddress($username); //Make sure the text enterend is a doge address
                    if($check["isvalid"] == 1){
                                    $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
                    if ($resp->is_valid) {
                                    $link = mysqli_connect("127.0.0.1", "root", "DATABASE_PW_HERE")or die("cannot connect to server - Sorry");
                                    mysqli_select_db($link, "dogecoin")or die("cannot select DB");
                                    $time=time();
                                    $time_check=$time-43200; //Users can re-get doge every 8 hours (43200 seconds)
                                    $sql4="DELETE FROM users WHERE time<$time_check";
                                    $result4=mysqli_query($sql4);
                                    $sql=sprintf("SELECT * FROM users WHERE address='%s' OR ip='$ip'",
                                    mysqli_real_escape_string($username));
                                    $result=mysqli_query($sql);
                                    $count=mysqli_num_rows($result);
                                    if($count=="0"){
                                            $amount = rand(1,5);
                                            $sql1=sprintf("INSERT INTO users(address, time, ip,amount)VALUES('%s', '$time', '$ip', '$amount')",
                                            mysqli_real_escape_string($username));
                                            $result1=mysqli_query($sql1);
                                            #$doge->sendfrom("", $username, $amount);
                                            echo "You've got ";
                                            echo $amount;
                                            echo " DOGE!";
                                    }else{
                                            echo "Much request, plz wait. You can get new DOGE every 12 hours!";
                                    }
                            }else{
                                    echo "reCAPTCHA invalid!";
                            }
     
                    }
            }
    }
    echo '<br /><form Name = "getcoin" Method = "POST" ACTION = "doge.php"><INPUT TYPE = "text" VALUE = "" NAME = "address" /><INPUT TYPE = "submit" Name = "submit" VALUE = "Send" />';
    echo recaptcha_get_html($publickey); ?>
    </form>
    <br/>
     
    </table><p><font size="2">faucet base coded by smiba from doges.org - version <?php echo $version; //Do not remove the base coded by ?></font></p>


