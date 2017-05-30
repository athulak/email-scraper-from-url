<?php

/*
 * This code is only for educational purposes. 
 * 
 * Developer: Athul AK
 * athullive@gmail.com
 * fb.com/athullive
 * git: https://github.com/athullive
 * 
 */

//Main code begins here

class Main {      //main class
	
	public $scaperFunc = 'scaperFunc';
	
	
	function scaperFunc() {  //main funtion
		//echo 'inside scraper function. &#13;&#10;';
		//validate input
		if((isset($_POST['scrape'])) && ($_POST['url'] != "")){
			$url = $_POST['url'];
			
			//ensure value is a string.
			//$cleanse_url= filter_var($url, FILTER_SANITIZE_STRING);  //this is not required in this case.
			
			$webData = $this->file_get_contents_curl($url); //get all data from URL
			
			$emails  = $this->extract_email_address ($webData); //extracts emails
			
			//echo $webData; exit();
			
			return $emails;
		}
		else{
			return "Please input url";
		}
	}
	
	function file_get_contents_curl($url) { //This function crawls webpages and return data.
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		
		$data = curl_exec($ch);
		$data = str_replace("<br />", "", $data);
		curl_close($ch);
		
		return $data;
	}
	
	function extract_email_address ($webData) { //This function extracts and stroes all e-mails from collected data
		foreach(preg_split('/\s/', $webData) as $token) {
			$email = filter_var(filter_var($token, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
			if ($email !== false) {
				$emails[] = $email;
			}
		}
		return $emails;
	}
	
}

$mainFunc = new Main;



?>

<html>
<body>
<center>

<form action="" method="POST" style="
    margin-top: 10%;
    
">
<h3><u>E-mail Scraper from URL</u></h3>
<h5>this app will go to a url you provide, search for all emails in it and list all extracted emails from that single page</h5>
URL: <input type="url" name="url"><br>
<br><input type="submit" name="scrape">
</form>
<textarea rows="10" cols="50">emails will be displayed here: &#13;&#10;
<?php 
 if(isset($_POST['scrape'])){
   $emails = $mainFunc->{$mainFunc->scaperFunc}();
   foreach ( $emails as $email) {
   	echo $email. "&#13;&#10;";
   }
 }
?>
</textarea>
<br><br>
<div class="github-card" data-user="athullive"></div>
<script src="http://lab.lepture.com/github-cards/widget.js"></script>
</body>
</html>
