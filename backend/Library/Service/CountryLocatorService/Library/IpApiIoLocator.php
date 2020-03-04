<?php


namespace Backend\Library\Service\CountryLocatorService\Library;


class IpApiIoLocator extends BaseLocator
{
    public function getCountryByIp($ip)
    {
        $timeout = 3;
        $responseJson = null;
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://ip-api.io',]);
        try {
            $response = $client->get("/json/{$ip}", [
                'timeout' => $timeout,
                'connect_timeout' => $timeout,
            ]);
            $content = $response->getBody()->getContents();
            $responseJson = json_decode($content, true);
        }catch (\Exception $exception){
        }
        if (empty($responseJson)
        || !isset($responseJson['country_name'])
            || empty($responseJson['country_name'])) {

            if(!empty($this->next)){
                return $this->next->getCountryByIp($ip);
            }
            return null;
        }
        return $responseJson['country_name'];

    }

}