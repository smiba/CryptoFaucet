<head>
<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
<?php
    //----[Settings]
    $version = "1.3.0";

    $coinname = "";
	
    $rpcusername = "";
    $rpcpassword = "";
    $rpcIP = "127.0.0.1";
    $rpcport = "";

    $sqlIP = "127.0.0.1";
    $sqluser = "root";
    $sqlpassword = "";
    $sqlDB = "faucet";

    $minbalance = 0.005; //Never make this lower then the $randomHigh, it could result in errors on payout.
	
    $randomlow = 5;
    $randomhigh = 50;
	$randomdivide = 10000; //To create numbers lower then 1.0

    $walletaccount = "faucet"; //Keep empty to just use the main wallet
	
    $capatchaSecret = ""; //Your Recapatcha V2 secret
    $capatchaSiteKey = ""; //Your Recapatcha V2 site key

    $dev = false; //Set to TRUE to enable error reporting (Useful for development!)
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
    require_once 'jsonRPCClient.php';
    //----

    $faucet = new jsonRPCClient("http://$rpcusername:$rpcpassword@$rpcIP:$rpcport/");
    print_r($faucet->getbalance("$walletaccount"));
    echo "</b> - Donate to the faucet: ".$faucet->getaccountaddress("$walletaccount")."<br />";

    if(!empty($_POST['address'])) {
		 $username = $_POST['address'];
		$ip = $_SERVER['REMOTE_ADDR'];
		
        if($faucet->getbalance("$walletaccount") < $minbalance){
            echo "Not enough funds.";
        }else{
            $check = $faucet->validateaddress($username); //Make sure the text entered is a valid address
            if($check["isvalid"] == 1){
				$RecapatchaJson = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$capatchaSecret&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']));
				if ($RecapatchaJson->success == true){	
					$link = mysqli_connect($sqlIP, $sqluser, $sqlpassword)or die("cannot connect to server - Sorry");
					mysqli_select_db($link, $sqlDB)or die("cannot select DB");
					$time = time();
					$time_check = $time-43200; //Users can re-get coins every 8 hours (43200 seconds)
					$sql4 = "DELETE FROM users WHERE time<$time_check";
					$result4 = mysqli_query($link, $sql4);
					$sql = "SELECT * FROM users WHERE address='$username' OR ip='$ip'";
					$result = mysqli_query($link, $sql);
					$count = mysqli_num_rows($result);
					if($count == "0"){
						$amount = mt_rand($randomlow,$randomhigh) / $randomdivide;
						$sql1 = sprintf("INSERT INTO users(address, time, ip, amount) VALUES ('$username', '$time', '$ip', '$amount')");
						$result1 = mysqli_query($link, $sql1);
						$faucet->sendfrom("$walletaccount", $username, $amount);
						echo "You've got $amount $coinname to your account at $username";
					}else{
						echo "Too many requests, you can only get new $coinname every 8 hours! (So sorry!)";
					}
				}else{
					echo "Capatcha Failure";
				}
            }else{
				echo "The address you've entered is invalid";
			}
        }
    }
	$faucetlowcalc = $randomlow / 10000;
	$faucethighcalc = $randomhigh / 10000;
	echo "<br/>You can currently get between $faucethighcalc and $faucetlowcalc from this faucet every 8 hours. Good luck!<br/>";
    echo '<br />Your $coinname address: <form Name = "getcoin" Method = "POST" ACTION = ""><INPUT TYPE = "text" VALUE = "" NAME = "address" /><INPUT TYPE = "submit" Name = "submit" VALUE = "Roll!" /><br/><br/><div class="g-recaptcha" data-sitekey="$capatchaSiteKey"></div>';
     ?>
</form>
</table><p><font size="2">Simple faucet base coded by Smiba - version <?php echo $version; //Do not remove the base coded by ?></font></p>
</body>

