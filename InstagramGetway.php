<?php

class InstagramGetway
{
    private $isAuthenticateUser = false;

    //for getting Instagram User Access Tokens
    const url_connect = "https://api.instagram.com";
    //for getting Instagram user profiles and media
    const url_media = "https://graph.instagram.com";
    //for access token
    const url_accesstoken = "https://api.instagram.com/oauth/access_token/";
    //for get username in instagram
    const url_username = "https://graph.instagram.com/me";

    public $param = array();
    protected static $accesstoken = "";


    public function __construct($appID, $redirect, $secretKey = "")
    {
        //create db file
        isdbinsta();
        $this->param['appid'] = trim($appID);
        $this->param['redirect'] = trim($redirect);
        $this->param['secretkey'] = trim($secretKey);
        $this->AuthenticateUser();
        $this->accesstoken();
        $this->get_user();



    }

    // validation the Test User
    public function AuthenticateUser()
    {

        //check for validate redirect URL
        if (!empty($this->param["redirect"]) && !filter_var($this->param['redirect'], FILTER_SANITIZE_URL)) {
            throw new Exception("redirect url is empty or not valid");
        }

        //url request
        $url = self::url_connect . DIRECTORY_SEPARATOR . "oauth/authorize?";

        //params send {GET}
        $paramsSend = [
            'client_id' => $this->param["appid"],
            'redirect_uri' => $this->param["redirect"],
            'scope' => "user_profile,user_media",
            'response_type' => "code"
        ];
        $url = $url . http_build_query($paramsSend);
        $this->isAuthenticateUser();
        if ($this->isAuthenticateUser() === false) {
            header("location: $url");
        }
    }

    public function isAuthenticateUser()
    {
        $code = readJsonedb("access_token");
        $code = isset($_GET['code']) ? $_GET['code'] : $code;
        if (!empty($code)) {
            $this->isAuthenticateUser = true;
        }
        return $this->isAuthenticateUser;
    }

    private function savecode()
    {
        if (empty(readJsonedb("access_token"))) {
            if (isset($_GET['code']) && !empty($_GET['code'])) {
                $data['code'] = trim($_GET['code']);
                //set code in arr param
                return $data['code'];
            }

        }
    }

    private function accesstoken()
    {
        $this->param['code'] = $this->savecode();
        //send request

        if (empty(readJsonedb('access_token'))) {
            $paramsSend = [
                'client_id' => $this->param['appid'],
                'client_secret' => $this->param['secretkey'],
                'grant_type' => 'authorization_code',
                'redirect_uri' => $this->param['redirect'],
                'code' => $this->param['code']
            ];
            $response = post(self::url_accesstoken, $paramsSend);
            if (isset($response['error_type'])) {
                $this->param["error_type"] = $response['error_type'];
                $this->param["error_code"] = $response['code'];
                die("error_type = {$this->param['error_type']}  error_code = {$this->param['error_code']} ");
            }
            if (isset($response['access_token']) && isset($response['user_id'])) {
                writeJsonedb($response);
                $this->param['access_token'] = $response['access_token'];
                $this->param['user_id'] = $response['user_id'];

            } else if (!empty('access_token') && !empty('user_id')) {
                $this->param['access_token'] = readJsonedb('access_token');
                $this->param['access_token'] = readJsonedb('user_id');
            }

        }else{
            $this->param['access_token'] = readJsonedb('access_token');
            $this->param['user_id'] = readJsonedb('user_id');
        }

    }

    public function get_user()
    {
        $paramSend = [
            'fields' => "id,username",
            'access_token' => $this->param["access_token"]
        ];
        if (isset($this->param["access_token"])) {
            $token = $this->param['access_token'];
            get(self::url_username, $paramSend);
        }
    }


}