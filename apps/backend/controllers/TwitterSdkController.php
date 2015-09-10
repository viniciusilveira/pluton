<?php
namespace Multiple\Backend\Controllers;

class TwitterSdkController extends \Phalcon\Mvc\Controller
{

    /**
     * Gera o token de acesso a API do twitter através do APPID e APPSECRET informados pelo usuário
     * @param  string $app_id     app id da api do twitter
     * @param  string $app_secret app secret da api do twitter
     * @return string             token para acesso a api do twittter
     */
    public function generateBearerToken($app_id, $app_secret) {
        $encoded_consumer_key = urlencode($app_id);

        $encoded_consumer_secret = urlencode($app_secret);

        $bearer_token = $encoded_consumer_key . ':' . $encoded_consumer_secret;
        $base64_consumer_key = base64_encode($bearer_token);
        $url = "https://api.twitter.com/oauth2/token";
        $headers = array("POST /oauth2/token HTTP/1.1", "Host: api.twitter.com", "User-Agent: Twitter Application-only OAuth App", "Authorization: Basic " . $base64_consumer_key, "Content-Type: application/x-www-form-urlencoded;charset=UTF-8", "Content-Length: 29");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        $header = curl_setopt($ch, CURLOPT_HEADER, 1);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response = curl_exec($ch);
        curl_close($ch);
        $output = explode("\n", $response);
        $bearer_token = '';
        foreach ($output as $line) {
            if ($line !== false) {
                $bearer_token = $line;
            }
        }
        $bearer_token = json_decode($bearer_token);
        $bearer_token = $bearer_token->{'access_token'};

        return $bearer_token;
    }

    /**
     * Retorna informações sobre o profile do twitter solicitado
     * @param  string $bearer_token     token de acesso a api do twitter
     * @param  string $twitter_username nome de usuário do perfil do twitter para verifcar dados
     * @return array                   array contendo informações do perfil do twitter verificado
     */
    public function getLookupTwitterProfileBlog($bearer_token, $twitter_username) {
        $url = "https://api.twitter.com/1.1/users/lookup.json";
        $formed_url = '?screen_name=' . $twitter_username;
        $headers = array("GET /1.1/users/lookup.json" . $formed_url . " HTTP/1.1", "Host: api.twitter.com", "User-Agent: Twitter Application-only OAuth App", "Authorization: Bearer " . $bearer_token,);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . $formed_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
