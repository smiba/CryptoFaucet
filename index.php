DOGE in faucet:
<?php
//Made by smiba - Feel free to ask support on reddit from me... But no promises, please use google since that is your best friend!
//SQL Database layout: http://screenshots.bartstuff.eu/1387003406-Z14wbpSuaCIvNP9VvV2R.png
    //----[Settings]
    $version = "1.2";
    
    $publickey = ""; //ReCAPCHA Public key here
    $privatekey = ""; //ReCAPCHA Private key here
    
    $rpcusername = "";
    $rpcpassword = "";
    $rpcIP = "127.0.0.1";
    $rpcport = "22555";
    
    $sqlIP = "127.0.0.1";
    $sqluser = "";
    $sqlpassword = "";
    $sqlDB = "";
    
    $minbalance = 10; //Never make this lower then the $randomHigh, it could result in errors on payout.
    $randomlow = 1;
    $randomhigh = 5;
    
    $walletaccount = "facuet";
    
    $dev = false; //Set to TRUE to enable error reporting (Usefull for development!)
    //-----
    
    //----[Debug]
    if($dev==true){ 
        error_reporting(E_ALL); //show errors
        ini_set('display_startup_errors',1); //show errors 
        ini_set('display_errors',1); //show errors
        error_reporting(-1); //show errors    
    }
    //----
    
    //----[Dependencies]
    require_once 'jsonRPCClient.php'; //Get this from http://jsonrpcphp.org/?page=download&lang=en - the light is good enough
    require_once 'recaptchalib.php'; //Get this from ReCapcha's website - V1 ReCaptcha
    //----
    
    $doge = new jsonRPCClient("http://$rpcusername:$rpcpassword@$rpcpassword:$rpcIP/");
    print_r($doge->getbalance("$walletaccount"));
    echo "</b> - Please donate:".$doge->getaccountaddress("$walletaccount")."<br />";
     
    $username = $_POST['address'];
    $ip = $_SERVER['REMOTE_ADDR'];
    
    if(!empty($_POST['address'])) {
            if($doge->getbalance("$walletaccount") < $minbalance){
                    echo "Not enough funds.";
            }else{
                    $check = $doge->validateaddress($username); //Make sure the text enterend is a doge address
                    if($check["isvalid"] == 1){
                        $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
                            if ($resp->is_valid) {
                                $link = mysqli_connect($sqlIP, $sqluser, $sqlpassword)or die("cannot connect to server - Sorry");
                                mysqli_select_db($link, $sqlDB)or die("cannot select DB");
                                $time=time();
                                $time_check=$time-43200; //Users can re-get doge every 8 hours (43200 seconds)
                                $sql4="DELETE FROM users WHERE time<$time_check";
                                $result4=mysqli_query($link, $sql4);
                                $sql=sprintf("SELECT * FROM users WHERE address='%s' OR ip='$ip'",
                                mysqli_real_escape_string($link, $username));
                                $result=mysqli_query($link, $sql);
                                $count=mysqli_num_rows($result);
                                if($count=="0"){
                                    $amount = rand($randomlow,$randomhigh);
                                    $sql1=sprintf("INSERT INTO users(address, time, ip,amount)VALUES('%s', '$time', '$ip', '$amount')",
                                    mysqli_real_escape_string($link, $username));
                                    $result1=mysqli_query($link, $sql1);
                                    $doge->sendfrom("$walletaccount", $username, $amount);
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
</table><p><font size="2">faucet base coded by smiba from reddit - version <?php echo $version; //Do not remove the base coded by ?></font></p>
