<?php

namespace App\Repositories;

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class HanetRepository
{
    protected $url_auth = 'https://oauth.hanet.com';
    protected $url = 'https://partner.hanet.ai';

    public function __construct()
    {

    }

    public function buildUrl(string $clientId) {
        return $this->url_auth. '/oauth2/authorize?'. http_build_query([
            'response_type' => 'code',
            'client_id' => $clientId,
            'redirect_uri' => 'https://547e-113-22-125-183.ap.ngrok.io/api/partner/get_auth_code',
            'scope' => 'full'
        ]);
    }

    public function getDevices(string $accessToken)
    {
        $client = new Client();
        $res = $client->post($this->url. '/device/getListDevice', [
            'form_params' => [
                'token' => $accessToken
            ]
        ]);

        $data = json_decode($res->getBody()->getContents());

        return $data;
    }

    public function getAccessToken($code, $config)
    {
        $client = new Client();

        $setting = $config->setting;

        $res = $client->post($this->url_auth. '/token', [
            'form_params' => [
                'code' => $code,
                'grant_type' => 'authorization_code',
                'client_id' => $setting->client_id,
                'redirect_uri' => 'https://547e-113-22-125-183.ap.ngrok.io/api/partner/get_auth_code',
                'client_secret' => $setting->client_secret
            ]
        ]);

        $data = json_decode($res->getBody()->getContents());

        return $data;
    }

    public function getPlaces($accessToken)
    {
        $client = new Client();
        $res = $client->post($this->url. '/place/getPlaces', [
            'form_params' => [
                'token' => $accessToken
            ]
        ]);

        $data = json_decode($res->getBody()->getContents());

        return $data;
    }

    public function registerPerson(array $data,User $user,string $accessToken)
    {
        $client = new Client();

        $res = $client->post($this->url. '/person/register', [
//            'form_params' => [
//                'token' => $accessToken,
//                'name' => $user->fullname,
//                'file' => $data['file_0'],
//                'aliasId' => $user->id,
//                'placeId' => $data['place_id'],
//                'title' => $user->position
//            ],
            'multipart' => [
                [
                    'name'     => 'token',
                    'contents' => $accessToken,
                ],
                [
                    'name'     => 'name',
                    'contents' => $user->fullname,
                ],
                [
                    'name'     => 'file',
                    'contents' => fopen($data['file']['tmp_name'], "r"),
                ],
                [
                    'name'     => 'aliasId',
                    'contents' => $user->id,
                ],
                [
                    'name'     => 'placeId',
                    'contents' => $data['place_id'],
                ],
                [
                    'name'     => 'title',
                    'contents' => $user->position
                ]
            ]
        ]);

        $data = json_decode($res->getBody()->getContents());
        dd($data);
        return $data;
    }
}
