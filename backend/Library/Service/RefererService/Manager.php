<?php

namespace Backend\Library\Service\RefererService;

use Backend\Library\Service\Auth;
use Backend\Models\MySQL\DAO\Country;
use Backend\Models\MySQL\DAO\Media;
use Backend\Models\MySQL\DAO\Showing;
use Backend\Models\MySQL\DAO\UserLink;
use Backend\Models\MySQL\DAO\Visit;
use Backend\Models\MySQL\DAO\Office;
use Phalcon\Mvc\User\Component;

/**
 * Class Manager
 * @package Backend\Library\Service\RefererService
 * @property Auth $auth
 * @property \Backend\Library\Service\CountryLocatorService\Manager countryLocatorService
 * @property \Backend\Library\Phalcon\Logger\TcpLogger logger
 *
 */
class Manager extends Component
{

    /**@var string */
    protected $domain;

    /**@var string */
    protected $bkLinkKey;

    const SESSION_KEY = 'refererService.partnerId';

    public function __construct($settings)
    {
        $this->domain = trim($settings['domain']);
        $this->bkLinkKey = trim($settings['bkLinkKey']);

    }

    public function getPartnerLink()
    {
        $userId = $this->auth->getIdentity('user_id');

        return "{$this->domain}/referer/{$userId}";
    }

    public function getMediaIFameUrl($mediaId, $siteId)
    {
        $userId = $this->auth->getIdentity('user_id');

        return "{$this->domain}/MV?m={$mediaId}&u={$userId}&s={$siteId}";
    }

    public function getMediaLink($mediaId, $siteId, $userId)
    {
        return "{$this->domain}/M?m={$mediaId}&u={$userId}&s={$siteId}";
    }

    public function setPartnerId($partnerId)
    {
        $this->session->set(self::SESSION_KEY, $partnerId);
    }

    public function getPartnerId()
    {
        if ($this->session->has(self::SESSION_KEY)) {
            return (int)$this->session->get(self::SESSION_KEY);
        }
        return null;
    }


    public function getPartnerLinkById($partnerLinkId)
    {
        return "{$this->domain}/PL?link={$partnerLinkId}";
    }

    public function showing($mediaId, $siteId, $userId)
    {
        $id = uniqid('', true);
        $time = microtime(true);
        $rand1 = rand(1000000, 9999999);
        $rand2 = rand(1000000, 9999999);
        $hash = hash('sha256', "{$userId}@{$mediaId}@{$siteId}@{$id}@{$time}@{$rand1}@$rand2");
        $siteId = empty($siteId) ? null : $siteId;
        (new Showing())
            ->setHash($hash)
            ->setMediaId($mediaId)
            ->setSiteId($siteId)
            ->setUserId($userId)
            ->setDateCreate(date('Y-m-d H:i:s'))
            ->setIp($this->request->getClientAddress())
            ->setUserAgent(mb_substr($this->request->getUserAgent(), 0, 200))
            ->create();
    }

    public function makeRedirectByUserLink(UserLink $userLink)
    {
        $visit = new Visit();
        $visit->setUserId($userLink->getUserId())
            ->setIp($this->request->getClientAddress())
            ->setSiteId($userLink->getSiteId())
            ->setUserAgent(mb_substr($this->request->getUserAgent(), 0, 200))
            ->setDateCreate(date('Y-m-d H:i:s'))
            ->setInstrumentId($userLink->getId())
            ->setInstrumentType(Visit::INSTRUMENT_TYPE_USER_LINK)
            ->setHash(
                $this->generateHash(
                    $visit->getUserId(),
                    $visit->getInstrumentId(),
                    $visit->getInstrumentType()
                )
            );

        return $this->getLinkRedirect($userLink->getUrl(), $visit);
    }

    public function makeRedirectByMedia(Media $media, $userId, $siteId)
    {
        $visit = new Visit();
        $visit
            ->setUserId($userId)
            ->setSiteId(empty($siteId) ? null : $siteId)
            ->setIp($this->request->getClientAddress())
            ->setDateCreate(date('Y-m-d H:i:s'))
            ->setUserAgent(mb_substr($this->request->getUserAgent(), 0, 200))
            ->setInstrumentId($media->getId())
            ->setInstrumentType(Visit::INSTRUMENT_TYPE_MEDIA_SOURCE)
            ->setHash(
                $this->generateHash(
                    $visit->getUserId(),
                    $visit->getInstrumentId(),
                    $visit->getInstrumentType()
                )
            );

        return $this->getLinkRedirect($media->getUrl(), $visit);
    }

    /**
     * @param $path
     * @param Visit $visit
     * @return string|null
     */
    protected function getLinkRedirect($path, $visit)
    {

        $hash = $visit->getHash();
        $countryName = null;
        $ip = $this->request->getClientAddress();
        if (is_string($ip)) {
            $countryName = $this->countryLocatorService->getCountryByIp($ip);
        }
        $defaultOffice = null;
        $officeList = Office::find();
        if (empty(count($officeList))) {
            $this->logger->error('Не добавлены сайты для редиректа таблица office');
            return null;
        }

        /**@var Office[] $officeMap */
        $officeMap = [];
        foreach ($officeList as $office) {
            $officeMap[$office->getId()] = $office;
            if ($office->getIsDefault()) {
                $defaultOffice = $office;
            }
        }

        $countryList = Country::findWhereExistsOffice();
        foreach ($countryList as $country) {
            /**@var Country $country */
            if ($country->getAlias() === $countryName && !empty($country->getOfficeId())) {
                $officeId = $country->getOfficeId();
                $visit->setOfficeId($officeId);
                if (!$visit->create()) {
                    $this->logger->error('Ошибка сохранения клика по ресурсу');
                    return null;
                }
                $domain = $officeMap[$officeId]->getDomain();
                return "{$domain}{$path}?clickid={$hash}&partner={$this->bkLinkKey}";
            }
        }

        if (!empty($defaultOffice)) {
            $visit->setOfficeId($defaultOffice->getId());
            if (!$visit->create()) {
                $this->logger->error('Ошибка сохранения клика по ресурсу');
                return null;
            }
            $domain = $defaultOffice->getDomain();
            return "{$domain}{$path}?clickid={$hash}&partner={$this->bkLinkKey}";
        }

        $this->logger->error('Нет сайта по умолчанию');
        return null;
    }

    protected function generateHash($userId, $instrumentId, $instrumentType)
    {
        $id = uniqid('', true);
        $time = microtime(true);
        $rand = rand(10000, 99999);
        return hash('sha256', "{$userId}@{$instrumentId}@{$instrumentType}@{$id}@{$time}@{$rand}");
    }
}