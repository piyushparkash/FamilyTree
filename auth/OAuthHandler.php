<?php

/**
 * Description of OAuthHandler
 *
 * @author piyush
 */
class OAuthHandler {

    public $oauth, $consumerkey, $consumersecret, $endpoint, $namespace, $urls;

    public function __construct($consumerkey, $consumersecret, $endpoint, $namespace) {
        $this->oauth = new OAuth2\Client($consumerkey, $consumersecret);
        $this->consumerkey = $consumerkey;
        $this->consumersecret = $consumersecret;
        $this->endpoint = $endpoint;
        $this->namespace = $namespace;
    }

    function setUrl($authurl, $accessurl) {
        $this->urls = array(
            "authurl" => $authurl,
            "accessurl" => $accessurl
        );
    }

    function setToken($oauth_token) {
        $this->oauth->setAccessToken($oauth_token);
    }

    public function check_callback($callback) {

        //Make a curl request to auth url to check the output

        $gen_auth_url = $this->oauth->getAuthenticationUrl($this->urls['authurl'], $callback);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $gen_auth_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);

        $lasturl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
//        var_dump($lasturl);

        if (strpos($lasturl, CALLBACK)) {
            //it took us to redirect page which means it is working
            return true;
        }

        $output = json_decode($output, TRUE);

        if (empty($output)) {
            return true;
        }

        if ($output['error'] == 'redirect_uri_mismatch') {
            return FALSE;
        }

        curl_close($ch);

        if (!$output && empty($output)) { //if curl request fails
            return false;
        }
    }

    function init_auth_process() {
        global $vanshavali;
        if (session_status() == PHP_SESSION_ACTIVE) {

            $final_redirect = $vanshavali->hostname . CALLBACK;

            $authurl = $this->oauth->getAuthenticationUrl($this->urls['authurl'], $final_redirect);
            header("Location:$authurl");
            return true;
        } else {
            return false;
        }
    }

    function init_access_process($authverifier) {
        global $vanshavali;
        if (session_status() != PHP_SESSION_ACTIVE)
            return false;

        $callback = $vanshavali->hostname . CALLBACK; //. "?" . http_build_query($redirect);

        $params = array('code' => $authverifier, 'redirect_uri' => $callback);

        $response = $this->oauth->getAccessToken($this->urls['accessurl'], 'authorization_code', $params, array('User-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.98 Safari/537.36'));
        $accessToken = $response['result'];
        if (!$accessToken)
            return false;

        //Set the new token in the session
        $_SESSION['access_token'] = $accessToken['access_token'];

        //Set the new token
        $this->oauth->setAccessToken($accessToken['access_token']);

        //everything done
        return $accessToken;
    }

    public function auth_fetch($directive) {
        //check if previous process have comleted or not
        //Build Url from directive
        $url = $this->endpoint . $this->namespace . "/" . $directive;
        
        $details = $this->oauth->fetch($url);

        if (!$details) {
            return false;
        }
        else
        {
            return $details;
        }
    }

}
