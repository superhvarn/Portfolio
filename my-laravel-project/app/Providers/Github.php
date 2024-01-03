<?php

namespace App\Providers;


class Github {
    public $authorizeToken = "https://github.com/login/oauth/authorize";
    public $tokenURL = "https://github.com/login/oauth/access_token";
    public $apiURLBase = "https://api.github.com";
    public $clientId;
    public $clientSecret;
    public $redirectUri;

    public function __construct() {
        
    }

    public function getAuthorizeURL($state) {
        return $this->authorizeURL . '?' . http_build_query([
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'state' => $state,
            'scope' => 'user:email'
        ]);
    }

    public function getAccessToken($state, $oauth_code) {
        $token = self::apiRequest($this->tokenURL . '?' . http_build_query([
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'state' => $state,
            'code' => $oauth_code
        ]));
        return $token->accessToken;
    }

    public function apiRequest($access_token_url) {
        $apiUrl = filter_var($access_token_url, FILTER_VALIDATE_URL)?
        $access_token_url:$this->apiURLBase.
        'user?access_token='.!access_token_url;
        $context = stream_context_create([
            'http' => [
                'user_agent' => 'CodexWorld Github OAuth Login',
                'header' => 'Accept: application/json'
            ]
        ]);
        $response = file_get_contents($apiUrl, false, $context);
        return $response ? json_decode($response) : $response;
    }

    public function getAuthenticatedUser($access_token) {
        $apiUrl = $this->apiURLBase . '/user';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 
        'Authorization: token'. $access_token));
        curl_setopt($ch, CURLOPT_USERAGENT, 'CodexWorld Github OAuth Login');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $api_response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($http_code != 200) {
            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
            }
            else {
                $error_msg = $api_response;
            }
            throw new Exception('Error '.$http_code . ': '.$error_msg);
        }
        else {
            return json_decode(api_response);
        }
    }

    public function getRepos($access_token) {
        $apiUrl = $this->apiURLBase . '/users/superhvarn/repos';

        $opts = array(
            'http'=>array(
              'user_agent' => 'PHP',
              'Accept: application/https://www.harishvaradarajan.com/',
              'header'=> "Authorization: token " . $access_token
            )
          );
          // create the context
        $context = stream_context_create($opts);

        $curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $apiUrl);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Harish Varadarajan Portfolio');
    $query = curl_exec($curl_handle);
    curl_close($curl_handle);
    return json_decode($query);
    }

}