<?php
require_once('Github.php');

if (isset($accessToken)) {
    $gitUser = $gitClient->getAuthenticatedUser();

    if (!empty($gitUser)) {
        $gitUserData = array();
        $gitUserData['oauth_uid'] = !empty($gitUser->id)?$gitUser->id:'';
        $gitUserData['name'] = !empty($gitUser->name)?$gitUser->name:'';
        $gitUserData['username'] = !empty($gitUser->login)?$gitUser->login:'';
        $gitUserData['email'] = !empty($gitUser->email)?$gitUser->email:'';
        $gitUserData['location'] = !empty($gitUser->location)?$gitUser->location:'';
        $gitUserData['picture'] = !empty($gitUser->avatar_url)?$gitUser->avatar_url:'';
        $gitUserData['link'] = !empty($gitUser->html_url)?$gitUser->html_url:'';

        $gitUserData['oauth_provider'] = 'github';
        $userData = $user->checkUser($gitUserData);

        $_SESSION['userData'] = $userData;

        $output = '<h2>Github Account Details</h2>';
        $output .= '<div class ="ac-data">';
        $output .= '<img src = "'.$userData['picture'].'">';
        $output .= '<p><b>ID:</b> '.$userData['oauth_uid'].'"</p>';
        $output .= '<p><b>Name:</b> '.$userData['name'].'"</p>';
        $output .= '<p><b>Login Username:</b> '.$userData['username'].'"</p>';
        $output .= '<p><b>Email:</b> '.$userData['email'].'"</p>';
        $output .= '<p><b>Location:</b> '.$userData['location'].'"</p>';
        $output .= '<p><b>Profile Link:</b> <a href="'.$userData['link'].'" 
        target="_blank">Click to visit GitHub page</a></p>';
        $output .= '<p>Logout from <a href="logout.php">Github</a></p>';
        $output .= '</div>';
    }
    else {
        $output = '<h3> style = "color: red">Something went wrong, please try again! </h3>';

    }
}
elseif(isset($_GET['code'])) {
    if(!$_GET['state'] || $_SESSION['state'] != $_GET['state']) {
        header("Location: ".$_SERVER['PHP_SELF']);
    }

    $accessToken = $gitClient->getAccessToken($_GET['state'], $_GET['code']);
    $_SESSION['access_token'] = $accessToken;

    header('Location: ./');
}
else {
    $_SESSION['state'] = hash('sha256', microtime(TRUE) . rand() . $_SERVER['REMOTE_ADDR']);
    unset($_SESSION['access_token']);
    $authUrl = $gitClient->getAuthorizeURL($_SESSION['state']);
    $output = '<a href ="'.htmlspecialchars($authUrl).'"><img src="images/github-login.png"></a>';
}
?>