<?php

/**
 * Description of OAuthHandler
 *
 * @author piyush
 */
class OAuthHandler {

    public $oauth, $consumerkey, $consumersecret, $endpoint, $namespace, $urls;

    public function __construct($consumerkey, $consumersecret, $endpoint, $namespace) {
        $this->oauth = new OAuth($consumerkey, $consumersecret, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_FORM);
        $this->consumerkey = $consumerkey;
        $this->consumersecret = $consumersecret;
        $this->endpoint = $endpoint;
        $this->namespace = $namespace;
    }

    function setUrl($requesturl, $authurl, $accessurl) {
        $this->urls = array(
            "requrl" => $requesturl,
            "authurl" => $authurl,
            "accessurl" => $accessurl
        );
    }

    public function init_request_process($callback) {
        try {
            $requestToken = $this->oauth->getRequestToken($this->urls['requrl'], $callback, "POST");
            if (!$requestToken)
                return false;

            //Set the variables here in session and proceed in auth process
            if (session_status() == PHP_SESSION_ACTIVE) {
                $_SESSION['oauthstate'] = 'auth';
                $_SESSION['oauth_token'] = $requestToken['oauth_token'];
                $_SESSION['oauth_token_secret'] = $requestToken['oauth_token_secret'];
                return true;
            } else {
                return false;
            }
        } catch (OAuthException $ex) {
            return false;
        }
    }

    function init_auth_process() {
        if (session_status() == PHP_SESSION_ACTIVE && $_SESSION['oauthstate'] == "auth") {
            header("Location:" . $this->urls['authurl'] .
                    "?oauth_token=" . $_SESSION['oauth_token'] .
                    "&oauth_token_secret=" . $_SESSION['oauth_token_secret']);
        } else
            return false;
    }

    function init_access_process($authverifier) {
        if (session_status() != PHP_SESSION_ACTIVE)
            return false;

        //set the previous tokens
        $this->oauth->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

        try {
            $accessToken = $this->oauth->getAccessToken(
                    $this->urls['accessurls'], null, $authverifier);
            if (!$accessToken)
                return false;

            //Set the new token
            $this->oauth->setToken($accessToken['oauth_token'], $accessToken['oauth_token_secret']);
            $this->oauth->setAuthType(OAUTH_AUTH_TYPE_URI);

            //everything done
            return TRUE;
        } catch (OAuthException $ex) {
            return false;
        }
    }

}
