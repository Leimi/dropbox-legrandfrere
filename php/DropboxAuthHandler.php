<?php
class DropboxAuthHandler {
    protected $state;
    protected $Oauth;
    protected $authed;

    public function __construct(Dropbox_OAuth $oauth, $host) {
        if (!session_id()) {
          session_start();
        }
        $this->Oauth = $oauth;
        $this->authed = false;
        $this->state = isset($_SESSION['state']) ? $_SESSION['state'] : 1;
        switch ($this->state) {
            case 1: $this->_acquireTokens(); break;
            case 2: $this->_getAccessToken();
            case 3: $this->_setToken(); break;
            default: break;
        }

        $this->host = $host;
    }

    public function isAuthed() {
        return $this->authed;
    }

    /* In this phase we grab the initial request tokens
       and redirect the user to the 'authorize' page hosted
       on dropbox */
    protected function _acquireTokens() {
        // echo "Step 1: Acquire request tokens\n";
        echo 'blou';
        $tokens = $this->Oauth->getRequestToken();

        // Note that if you want the user to automatically redirect back, you can
        // add the 'callback' argument to getAuthorizeUrl.
        $_SESSION['state'] = 2;
        $_SESSION['oauth_tokens'] = $tokens;
        // echo "Step 2: You must now redirect the user to:\n";
        // echo $this->Oauth->getAuthorizeUrl() . "\n";
        header('Location:'.$this->Oauth->getAuthorizeUrl($this->host));
    }

    protected function _getAccessToken() {
        /* In this phase, the user just came back from authorizing
       and we're going to fetch the real access tokens */
        // echo "Step 3: Acquiring access tokens\n";
        $this->Oauth->setToken($_SESSION['oauth_tokens']);
        $tokens = $this->Oauth->getAccessToken();
        // print_r($tokens);
        $_SESSION['state'] = 3;
        $_SESSION['oauth_tokens'] = $tokens;
        // There is no break here, intentional
    }

    protected function _setToken() {
        /* This part gets called if the authentication process
       already succeeded. We can use our stored tokens and the api
       should work. Store these tokens somewhere, like a database */
        $this->authed = true;
        //echo "The user is authenticated\n";
        //echo "You should really save the oauth tokens somewhere, so the first steps will no longer be needed\n";
        //print_r($_SESSION['oauth_tokens']);
        $this->Oauth->setToken($_SESSION['oauth_tokens']);
    }
}