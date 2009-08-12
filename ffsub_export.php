<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>FF Subscription Exporter</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
</head>
<html>
<body>
<p>In order to save this you must use your browser's, "Save As" function.</p>
<br />
<?php
require_once("php/ffriendfeed.php");
require_once("php/JSON.php");

$user= $_POST['username'];
$key = $_POST['remotekey'];
$length =$_POST['subscriptions'];

$session = new FriendFeed($user, $key);
do{

	//Creates a session to access users Subscriptions 
	$feed = $session->fetch_user_profile($user,null,$length-100,$length);
	
	foreach($feed->subscriptions as $sub)
	{
		$subscriptions = null;
		
		$username = $sub->nickname;
		
		//Accesses your Subscriptions info to extract services
		$profiles = $session->fetch_user_profile($username, null, 0, 30);
		
		//Super Sloppy Validation  prevents private feeds from displaying if RemoteKey was left blank.
		if($profiles->status == 'public' || $key!=null)
		{
			print $username." --  ";
			
			//Next line originally existed on line 32 and causes a foreach() error on occasion at http://hiphs.com/ff/friends
			foreach ($profiles->services as  $service)
			{
				$subServices = null;
				
				$subServices .= '<img src="' . $service->iconUrl . '" alt="' . $service->name . '" />';
				
				//Provides hyperlink of the users profile on the selected service.
				$subServices.= '<a href="'.$service->profileUrl.'">'.$service->name.'</a>'.'   -   ';
 
 
				print $subServices; 
			}
			print "<br/><br/>";
		}
	}
$length= $length-100;
} while($length>0);
?> 
</body>
</html>