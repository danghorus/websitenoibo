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
            'redirect_uri' => 'http://work.horusvn.com/api/partner/get_auth_code',
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
                'redirect_uri' => 'http://work.horusvn.com/api/partner/get_auth_code',
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

    public function getCheckinByPlaceIdInDay($accessToken, $date, $devicesArr, User $user)
    {
        $client = new Client();
        $res = $client->post($this->url. '/person/getCheckinByPlaceIdInDay', [
            'form_params' => [
                'token' => $accessToken,
                'placeID' => $user->place_id,
                'date' => $date,
                'type' => 0,
                'aliasID' => $user->user_code,
                'devices' => implode(',', $devicesArr),
            ]
        ]);

        $data = json_decode($res->getBody()->getContents());

        return $data;
    }

    public function getCheckinByPlaceIdInTimestamp($accessToken, $placeId, $devicesArr)
    {
        $client = new Client();
        $res = $client->post($this->url. '/person/getCheckinByPlaceIdInTimestamp', [
            'form_params' => [
                'token' => $accessToken,
                'placeID' => $placeId,
                'type' => 0,
                'devices'=> implode(',', $devicesArr),
                'from'=> "1617235200000",
                'to'=> "1619740800000",
            ]
        ]);

        $data = json_decode($res->getBody()->getContents());

        return $data;
    }

    public function registerPerson(array $data, User $user,string $accessToken)
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

    public function getAllUsers($accessToken, $placeId)
    {
        $client = new Client();
        $res = $client->post($this->url. '/person/getListByPlace', [
            'form_params' => [
                'token' => $accessToken,
                'placeID' => $placeId,
                'type' => 0
            ]
        ]);

        $data = json_decode($res->getBody()->getContents());

        return $data;
    }

    
}
