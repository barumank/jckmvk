<?php


namespace Backend\Library\Service\CountryLocatorService\Library;


class GeopluginLocator extends BaseLocator
{
    public function getCountryByIp($ip)
    {
        $timeout = 3;
        $responseJson = null;
        $client = new \GuzzleHttp\Client(['base_uri' => 'http://www.geoplugin.net',]);
        try {
            $response = $client->get("/json.gp?ip={$ip}", [
                'timeout' => $timeout,
                'connect_timeout' => $timeout,
            ]);
            $content = $response->getBody()->getContents();
            $responseJson = json_decode($content, true);
        } catch (\Exception $exception) {
        }
        if (empty($responseJson)
            || !isset($responseJson['geoplugin_countryName'])
            || empty($responseJson['geoplugin_countryName'])) {

            if (!empty($this->next)) {
                return $this->next->getCountryByIp($ip);
            }
            return null;
        }
        return $responseJson['geoplugin_countryName'];
    }
}