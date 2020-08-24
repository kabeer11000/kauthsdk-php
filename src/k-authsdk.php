<?php
declare(strict_types=1);
namespace Kabeers;
require 'small-http.php';

use Error;
use Exception;

session_start();

class KAuth
{
    /**
     * @var null
     */
    public $tokens = null;
    /**
     * @var null
     */
    private $client_secret = null;
    /**
     * @var null
     */
    private $client_public = null;
    /**
     * @var null
     */
    private $save_dir = null;
    /**
     * @var null
     */
    private $auth_uri = null;
    /**
     * @var array
     */
    private $endPoints = array(
        'UserInfo' => 'https://kabeers-auth.herokuapp.com/user/userinfo',
        'AccessToken' => 'https://kabeers-auth.herokuapp.com/auth/token',
        'RefreshToken' => 'https://kabeers-auth.herokuapp.com/auth/refresh',
        'AuthURI' => 'https://kabeers-auth.herokuapp.com/auth/authorize'
    );
    /**
     * @var bool
     */
    private $session_state = false;

    /**
     * @param String $token
     * @return int|string|string[]|null
     */
    public function getUserInfo(String $token)
    {
        if (!$token || !$this->client_secret || !$this->client_public) return 0;
        return preg_replace("/\s+/", "", SmallHttp::HTTPPost($this->endPoints['UserInfo'],
            array(
                "client_public" => "$this->client_public",
                "client_secret" => "$this->client_secret",
                "token" => "$token"
            )
        ));
    }

    /**
     * @param String $client_public
     * @param String $client_secret
     * @param String $save_dir
     * @param bool $session_state
     * @return bool|int|null
     */
    public function init(String $client_public, String $client_secret, String $save_dir, Bool $session_state = false)
    {
        if (!$client_public || !$client_secret || !$save_dir) return 0;
        $this->client_public = $client_public;
        $this->client_secret = $client_secret;
        $this->save_dir = $save_dir;
        if (isset($_GET['code'])) {
            if ($session_state === false) {
                $token_response = json_decode($this->getAccessTokens(htmlspecialchars($_GET['code'])), true);
                $token_response !== null && gettype($token_response) === 'array' ? $this->tokens = $token_response : null;
            } else {
                if(isset($_SESSION['kauth_state']) && $_GET['state'] !== $_SESSION['kauth_state']) return false;
                $token_response = json_decode($this->getAccessTokens(htmlspecialchars($_GET['code'])), true);
                $token_response !== null && gettype($token_response) === 'array' ? $this->tokens = $token_response : null;
            }
        }
        if (isset($_GET['token'])) {
            function objectToArray($d){if(is_object($d)){$d=get_object_vars($d);}if(is_array($d)){return array_map(__FUNCTION__,$d);}else{return $d;}}
            $token_response = objectToArray(json_decode(urldecode($_GET['token'])));
            if($session_state === false) return $token_response !== null || '' ? $this->tokens = $token_response : null;
            if(isset($_SESSION['kauth_state']) && $_GET['state'] !== $_SESSION['kauth_state']) return 0;
            return $token_response !== null || '' ? $this->tokens = $token_response : null;
        }
        return true;
    }

    /**
     * @param String $refresh_token
     * @return bool|int|string
     */
    public function refreshToken(String $refresh_token)
    {
        if (!$this->client_secret || !$this->client_public || !$refresh_token) return 0;
        $jwt_payload = json_decode(base64_decode(urldecode(explode('.', $refresh_token)[1])));
        if ($jwt_payload->iat > $jwt_payload->exp) throw new Error('Refresh Token Expired');
        return SmallHttp::HTTPPost($this->endPoints['RefreshToken'],
            array(
                "client_public" => "$this->client_public",
                "client_secret" => "$this->client_secret",
                "refresh_token" => "$refresh_token"
            ));
    }

    /**
     * @param String $code
     * @return bool|int|string
     */
    private function getAccessTokens(String $code)
    {
        if (!$this->client_secret || !$this->client_public || !$code) return 0;
        return SmallHttp::HTTPPost($this->endPoints['AccessToken'],
            array(
                "client_public" => "$this->client_public",
                "client_secret" => "$this->client_secret",
                "auth_code" => "$code"
            ));
    }

    /**
     * @param $length
     * @return string
     */
    private function genRandom($length) {
        $char = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_~";
        $char = str_shuffle($char);
        for($i = 0, $rand = '', $l = strlen($char) - 1; $i < $length; $i ++) {
            $rand .= $char{mt_rand(0, $l)};
        }
        return $rand;
    }

    /**
     * @param String $key
     * @param String $value
     * @return bool|int
     */
    public function saveToken(String $key, String $value)
    {
        if (!$value || !$key) return 0;
        $return = false;
        $key = md5($key);
        if (isset($this->save_dir) && $this->save_dir !== '' || null) {
            $save_key = fopen("$this->save_dir/$key.kauth_store", "w") or die("Unable to open file!");
            fwrite($save_key, "$value");
            fclose($save_key);
            $return = true;
        }
        return $return;
    }

    /**
     * @param String $key
     * @return bool|false|int|string|null
     */
    public function getToken(String $key)
    {
        if (!$key) return 0;
        $return = null;
        $key = md5($key);
        if (isset($this->save_dir) && $this->save_dir !== '' || null) {
            $save_contents = null;
            try {
                $save_contents = file_get_contents("$this->save_dir/$key.kauth_store");
                $return = true;
            } catch (Exception $e) {
                $return = false;
            }
            return $save_contents !== null || '' ? $save_contents : $return;
        }
        return true;
    }

    /**
     * @param String $key
     * @return bool|int|null
     */
    public function deleteToken(String $key)
    {
        if (!$key) return 0;
        $key = md5($key);
        if (isset($this->save_dir) && $this->save_dir !== null) {
            try {
                unlink("$this->save_dir/$key.kauth_store");
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $claims
     * @param String $callback
     * @param String $state
     * @param String $response_type
     * @param String $prompt
     * @param Int $nonce_state_length
     */
    public function createAuthURI(Array $claims, String $callback, String $state, String $response_type = 'code', String $prompt = 'consent', Int $nonce_state_length = 8)
    {
        $callback = urlencode($callback);
        $claims = urlencode(join(array_unique($claims), '|'));
        !$state || $state === '' || null ? $state = $this->genRandom($nonce_state_length) : null;
        $nonce = $this->genRandom($nonce_state_length);
        isset($this->session_state) ? $_SESSION['kauth_state'] = $state and $_SESSION['kauth_nonce'] = $nonce : null;
        $response_type_token_extras = $response_type === 'token' ? '&'.http_build_query(array('client_secret'=>$this->client_secret)) : "";
        $endPoint = $this->endPoints['AuthURI'];
        $query = http_build_query(array("client_id" => $this->client_public, "scope" => $claims, "response_type" => $response_type, "redirect_uri" => urldecode($callback), "state" => $state, "nonce" => $nonce, "prompt" => $prompt)).$response_type_token_extras;
        $this->auth_uri = "$endPoint/?$query";
    }

    /**
     * @param String $height
     * @param String $width
     * @param String $theme
     * @return bool|string
     */
    public function render(String $height, String $width, String $theme = 'dark')
    {
        if (!$height || !$width || !$theme || !$this->auth_uri || $this->auth_uri === null) return false;
        if ($theme === 'dark') {
            return "<div class='kauth_btn--container'><a href='$this->auth_uri' class='kauth_btn--anchor'><img alt='Login With Kabeers Network' class='kauth_btn--image' src='https://cdn.jsdelivr.net/gh/kabeer11000/kauthsdk-php/dist/dark.svg' style='width:$width;height:$height'></a></div>";
        }
        return "<div class='kauth_btn--container'><a href='$this->auth_uri' class='kauth_btn--anchor'><img alt='Login With Kabeers Network' class='kauth_btn--image' src='https://cdn.jsdelivr.net/gh/kabeer11000/kauthsdk-php/dist/light.svg' style='width:$width;height:$height'></a></div>";
    }

    /**
     * @return bool|int
     */
    public function redirect()
    {
        if (!$this->auth_uri || $this->auth_uri === null) return false;
        header("Location:$this->auth_uri");
        return 0;
    }
}
?>
