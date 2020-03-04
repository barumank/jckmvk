<?php

namespace Backend\Library\Service\StatisticService;


use Backend\Library\Service\Auth;
use Backend\Models\MySQL\DAO\Action;
use Backend\Models\MySQL\DAO\Bonus;
use Backend\Models\MySQL\DAO\Media;
use Backend\Models\MySQL\DAO\Showing;
use Backend\Models\MySQL\DAO\TransactionAmount;
use Backend\Models\MySQL\DAO\TransactionOffice;
use Backend\Models\MySQL\DAO\User;
use Backend\Models\MySQL\DAO\UserOffice;
use Backend\Models\MySQL\DAO\UserSite;
use Backend\Models\MySQL\DAO\Visit;
use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\User\Component;

/**
 * Class Manager
 * @package Backend\Library\Service\StatisticService
 * @property Auth $auth
 */
class Manager extends Component
{

    protected $dateStart;
    protected $dateEnd;
    protected $siteId;
    protected $userLinkId;
    protected $mediaId;
    protected $officeUserId;
    protected $mediaType;

    /**
     * Manager constructor.
     */
    public function __construct()
    {
        $this->dateStart = date('Y-m-d 00:00:00');
        $this->dateEnd = date('Y-m-d 23:59:59');
        $this->siteId = 0;
        $this->userLinkId = 0;
    }

    /**
     * @param false|string $dateStart
     * @return Manager
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;
        if (empty($dateStart)) {
            $this->dateStart = date('Y-m-d 00:00:00');
        }
        return $this;
    }

    /**
     * @param false|string $dateEnd
     * @return Manager
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;
        if (empty($dateEnd)) {
            $this->dateEnd = date('Y-m-d 23:59:59');
        }
        return $this;
    }

    /**
     * @param int $siteId
     * @return Manager
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
        if (empty($siteId)) {
            $this->siteId = 0;
        }

        return $this;
    }

    /**
     * @param int $userLinkId
     * @return Manager
     */
    public function setUserLinkId($userLinkId)
    {
        $this->userLinkId = $userLinkId;
        if (empty($userLinkId)) {
            $this->userLinkId = 0;
        }
        return $this;
    }

    /**
     * @param mixed $mediaId
     * @return Manager
     */
    public function setMediaId($mediaId)
    {
        $this->mediaId = $mediaId;
        return $this;
    }

    /**
     * @param mixed $officeUserId
     * @return Manager
     */
    public function setOfficeUserId($officeUserId)
    {
        $this->officeUserId = $officeUserId;
        return $this;
    }

    /**
     * @param mixed $mediaType
     * @return Manager
     */
    public function setMediaType($mediaType)
    {
        $this->mediaType = $mediaType;
        return $this;
    }

    public function formatFloat($number)
    {
        return round($number, 2, PHP_ROUND_HALF_DOWN);
    }


    public function getVisitsData()
    {
        $builder = Visit::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('DATE_FORMAT(v.date_create, "%d/%m/%Y") as dateGroup, COUNT(v.hash) as count')
            ->groupBy('dateGroup')
            ->andWhere('v.date_create >= :dateStart: and v.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ]);
        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->userLinkId)) {
            $builder->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_USER_LINK,
                'instrumentId' => $this->userLinkId
            ]);
        }

        $listMap = $this->getMapByGroupDate($builder);
        $dateList = $this->getDateList();

        return $this->bindDate($listMap, $dateList);
    }

    public function getRegistrationsData()
    {

        $builder = UserOffice::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('DATE_FORMAT(uo.date_create, "%d/%m/%Y") as dateGroup, COUNT(uo.id) as count')
            ->groupBy('dateGroup')
            ->andWhere('uo.date_create >= :dateStart: and uo.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ]);

        if (!empty($this->siteId) || !empty($this->userLinkId)) {
            $builder->leftJoin(Visit::class, 'v.hash = uo.hash', 'v');
        }
        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->userLinkId)) {
            $builder->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_USER_LINK,
                'instrumentId' => $this->userLinkId
            ]);
        }
        $listMap = $this->getMapByGroupDate($builder);
        $dateList = $this->getDateList();
        return $this->bindDate($listMap, $dateList);
    }

    public function getRegistrationsWithDepositData()
    {

        $builder = UserOffice::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('DATE_FORMAT(to.date_create, "%d/%m/%Y") as dateGroup, COUNT(uo.id) as count')
            ->groupBy('dateGroup')
            ->leftJoin(TransactionOffice::class, 'to.user_office_id = uo.id', 'to')
            ->andWhere('to.office_deposit_id is not null and to.status = :status:', [
                'status' => TransactionOffice::STATUS_ACCEPT
            ])
            ->andWhere('to.date_create >= :dateStart: and to.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ])
            ->andWhere('uo.date_create >= :dateStart: and uo.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ]);

        if (!empty($this->siteId) || !empty($this->userLinkId)) {
            $builder->leftJoin(Visit::class, 'v.hash = uo.hash', 'v');
        }
        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->userLinkId)) {
            $builder->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_USER_LINK,
                'instrumentId' => $this->userLinkId
            ]);
        }
        $listMap = $this->getMapByGroupDate($builder);
        $dateList = $this->getDateList();
        return $this->bindDate($listMap, $dateList);
    }

    public function getListIncome()
    {

        $builder = TransactionAmount::getBuilder()
            ->where('ta.user_id = :userId:', [
                'userId' => $this->auth->getIdentity('user_id')
            ])
            ->columns('DATE_FORMAT(ta.date, "%d/%m/%Y") as dateGroup, SUM(ta.amount) as amountSum')
            ->groupBy('dateGroup')
            ->andWhere('ta.date >= :dateStart: and ta.date <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ])->andWhere('ta.status = :taStatus:', ['taStatus' => TransactionAmount::STATUS_ACCEPT]);

        if (!empty($this->siteId)
            || !empty($this->userLinkId)) {
            $builder
                ->leftJoin(TransactionOffice::class, 'to.transaction_amount_id = ta.id', 'to')
                ->leftJoin(UserOffice::class, 'to.user_office_id = uo.id', 'uo')
                ->leftJoin(Visit::class, 'uo.hash = v.hash', 'v')
                ->andWhere('to.status = :toStatus:', ['toStatus' => TransactionOffice::STATUS_ACCEPT]);
        }
        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->userLinkId)) {
            $builder->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_USER_LINK,
                'instrumentId' => $this->userLinkId
            ]);
        }

        $listMap = $this->getMapByGroupDate($builder);
        $dateList = $this->getDateList();

        return $this->bindDate($listMap, $dateList,'amount','amountSum','double');
    }


    public function getCountShowing()
    {
        $builder = Showing::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('COUNT(s.hash) as countShoving')
            ->andWhere('s.date_create >= :dateStart: and s.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ]);
        if (!empty($this->siteId)) {
            $builder->andWhere('s.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('s.media_id = :mediaId:', ['mediaId' => $this->mediaId]);
        }
        return (int)$builder->getQuery()->execute()->getFirst()['countShoving'];
    }

    public function getCountVisitByMedia()
    {
        $builder = Visit::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('COUNT(v.hash) as count')
            ->andWhere('v.date_create >= :dateStart: and v.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ]);
        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere(' and v.instrument_id = :instrumentId:', [
                'instrumentId' => $this->mediaId
            ]);
        }
        return (int)$builder->getQuery()->execute()->getFirst()['count'];
    }

    public function getRegistrationsCount()
    {
        $builder = UserOffice::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('COUNT(uo.id) as count')
            ->andWhere('uo.date_create >= :dateStart: and uo.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ]);
        if (!empty($this->siteId) || !empty($this->mediaId)) {
            $builder->leftJoin(Visit::class, 'v.hash = uo.hash', 'v');
        }
        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
                'instrumentId' => $this->mediaId
            ]);
        }
        return (int)$builder->getQuery()->execute()->getFirst()['count'];
    }

    public function getRegistrationsWithDepositCount()
    {
        $builder = UserOffice::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('uo.id')
            ->andWhere('uo.date_create >= :dateStart: and uo.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ])
            ->leftJoin(TransactionOffice::class, 'to.user_office_id = uo.id', 'to')
            ->andWhere('to.office_deposit_id is not null and to.status = :status:', [
                'status' => TransactionOffice::STATUS_ACCEPT
            ])
            ->groupBy('uo.id');

        if (!empty($this->siteId) || !empty($this->mediaId)) {
            $builder->leftJoin(Visit::class, 'v.hash = uo.hash', 'v');
        }
        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
                'instrumentId' => $this->mediaId
            ]);
        }
        return (int)$builder->getQuery()->execute()->count();
    }

    public function getDepositAmount()
    {
        $builder = TransactionOffice::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('SUM(to.amount) as amount')
            ->andWhere('to.date_create >= :dateStart: and to.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ])->andWhere('to.status = :status: and to.type = :type:', [
                'status' => TransactionOffice::STATUS_ACCEPT,
                'type' => TransactionOffice::TYPE_DEPOSIT_TRANSACTION
            ]);
        if (!empty($this->siteId) || !empty($this->mediaId)) {
            $builder->leftJoin(UserOffice::class, 'to.user_office_id = uo.id', 'uo')
                ->leftJoin(Visit::class, 'v.hash = uo.hash', 'v');
        }
        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
                'instrumentId' => $this->mediaId
            ]);
        }
        return $this->formatFloat($builder->getQuery()->execute()->getFirst()['amount']);
    }

    public function getDepositCount()
    {
        $builder = TransactionOffice::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('COUNT(to.id) as count')
            ->andWhere('to.date_create >= :dateStart: and to.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ])->andWhere(' to.status = :status: and to.type = :type:', [
                'status' => TransactionOffice::STATUS_ACCEPT,
                'type' => TransactionOffice::TYPE_DEPOSIT_TRANSACTION
            ]);
        if (!empty($this->siteId) || !empty($this->mediaId)) {
            $builder->leftJoin(UserOffice::class, 'to.user_office_id = uo.id', 'uo')
                ->leftJoin(Visit::class, 'v.hash = uo.hash', 'v');
        }
        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
                'instrumentId' => $this->mediaId
            ]);
        }
        return (int)$builder->getQuery()->execute()->getFirst()['count'];
    }

    //доход
    public function getIncome()
    {
        $builder = TransactionOffice::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('SUM(to.amount) as amount')
            ->andWhere('to.date_create >= :dateStart: and to.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ])->andWhere(' to.status = :status: and to.type = :type:', [
                'status' => TransactionOffice::STATUS_ACCEPT,
                'type' => TransactionOffice::TYPE_BET_TRANSACTION
            ]);
        if (!empty($this->siteId) || !empty($this->mediaId)) {
            $builder->leftJoin(UserOffice::class, 'to.user_office_id = uo.id', 'uo')
                ->leftJoin(Visit::class, 'v.hash = uo.hash', 'v');
        }
        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
                'instrumentId' => $this->mediaId
            ]);
        }
        return $this->formatFloat($builder->getQuery()->execute()->getFirst()['amount']);
    }

    public function getBonusAmount()
    {
        $builder = Bonus::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('SUM(bo.amount) as amount')
            ->andWhere('bo.date_create >= :dateStart: and bo.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ])
            ->andWhere(' bo.status = :status:', ['status' => Bonus::STATUS_ACCEPT,]);
        if (!empty($this->siteId) || !empty($this->mediaId)) {
            $builder->leftJoin(Visit::class, 'v.hash = uo.hash', 'v');
        }
        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
                'instrumentId' => $this->mediaId
            ]);
        }
        return $this->formatFloat($builder->getQuery()->execute()->getFirst()['amount']);
    }

    public function getCommissionsAmount()
    {
        $builder = TransactionOffice::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('SUM(to.add_amount) as amount')
            ->andWhere('to.date_create >= :dateStart: and to.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ])->andWhere(' to.status = :status: and to.type = :type:', [
                'status' => TransactionOffice::STATUS_ACCEPT,
                'type' => TransactionOffice::TYPE_BET_TRANSACTION
            ])->andWhere('to.transaction_amount_id is not null');
        if (!empty($this->siteId) || !empty($this->mediaId)) {
            $builder->leftJoin(UserOffice::class, 'to.user_office_id = uo.id', 'uo')
                ->leftJoin(Visit::class, 'v.hash = uo.hash', 'v');
        }
        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
                'instrumentId' => $this->mediaId
            ]);
        }
        return $this->formatFloat($builder->getQuery()->execute()->getFirst()['amount']);
    }

    public function getReferralCommissionsAmount()
    {
        $builder = TransactionOffice::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('SUM(to.add_amount) as amount')
            ->andWhere('to.date_create >= :dateStart: and to.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ])->andWhere(' to.status = :status: and to.type = :type:', [
                'status' => TransactionOffice::STATUS_ACCEPT,
                'type' => TransactionOffice::TYPE_PARTNER_TRANSACTION
            ])->andWhere('to.transaction_amount_id is not null');
        if (!empty($this->siteId) || !empty($this->mediaId)) {
            $builder->leftJoin(UserOffice::class, 'to.user_office_id = uo.id', 'uo')
                ->leftJoin(Visit::class, 'v.hash = uo.hash', 'v');
        }
        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
                'instrumentId' => $this->mediaId
            ]);
        }
        return $this->formatFloat($builder->getQuery()->execute()->getFirst()['amount']);
    }


    /**
     * @param Builder $builder
     * @return array
     */
    protected function getMapByGroupDate($builder)
    {
        $out = [];
        $list = $builder->getQuery()->execute();
        foreach ($list as $item) {
            $out[$item['dateGroup']] = $item;
        }
        return $out;
    }

    protected function getDateList()
    {
        $dateStart = new \DateTime($this->dateStart);
        $dateEnd = new \DateTime($this->dateEnd);
        $interval = new \DateInterval('P1D');
        $dateList = [];
        while ($dateStart->getTimestamp() < $dateEnd->getTimestamp()) {
            $dateList[] = $dateStart->format('d/m/Y');
            $dateStart->add($interval);
        }
        return $dateList;
    }

    protected function bindDate(&$listMap, &$dateList, $valueReturnName = 'count',$valueGetName = 'count',$convertTo='int')
    {
        $out = [];
        foreach ($dateList as $date) {
            if (isset($listMap[$date])) {
                $item = $listMap[$date];
                $dataItem = explode('/', $item['dateGroup']);
                $value = $item[$valueGetName];
                if($convertTo ==='int'){
                    $value = (int) $value;
                }elseif ($convertTo ==='double'){
                    $value = (double) $value;
                }
                $out[] = [
                    'date' => [
                        'Y' => $dataItem[2],
                        'm' => $dataItem[1],
                        'd' => $dataItem[0]
                    ],
                    $valueReturnName => $value
                ];
            } else {
                $dataItem = explode('/', $date);
                $out[] = [
                    'date' => [
                        'Y' => $dataItem[2],
                        'm' => $dataItem[1],
                        'd' => $dataItem[0]
                    ],
                    $valueReturnName => 0
                ];
            }
        }
        return $out;
    }


    public function getRegistrationCountGroupSiteIdByIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }
        $builder = UserSite::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('us.id, COUNT(uo.id) as registrationCount')
            ->leftJoin(Visit::class, 'v.site_id = us.id', 'v')
            ->leftJoin(UserOffice::class, 'v.hash = uo.hash', 'uo')
            ->andWhere('us.id in ({idList:array})', [
                'idList' => $idList
            ])->groupBy('us.id')
            ->andWhere('uo.date_create >= :dateStart: and uo.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ]);

        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
                'instrumentId' => $this->mediaId
            ]);
        }
        $query = $builder->getQuery()->execute();
        $out = [];
        foreach ($query as $item) {
            $out[$item['id']] = $item['registrationCount'];
        }
        foreach ($idList as $id) {
            if (!isset($out[$id])) {
                $out[$id] = 0;
            }
        }
        return $out;
    }

    public function getAccountWithDepositsCountGroupSiteIdByIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }
        $query = UserOffice::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('uo.id')
            ->leftJoin(TransactionOffice::class, 'to.user_office_id = uo.id', 'to')
            ->andWhere('to.office_deposit_id is not null and to.status = :status:', [
                'status' => TransactionOffice::STATUS_ACCEPT
            ])
            ->groupBy('uo.id')
            ->andWhere('to.date_create >= :dateStart: and to.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ]);
        if (!empty($this->siteId) || !empty($this->mediaId)) {
            $query->leftJoin(Visit::class, 'v.hash = uo.hash', 'v');
        }
        if (!empty($this->siteId)) {
            $query->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $query->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
                'instrumentId' => $this->mediaId
            ]);
        }
        $userIdList = [];
        $query = $query->getQuery()->execute();
        foreach ($query as $item) {
            $userIdList[] = $item['id'];
        }
        $out = [];
        if (!empty($userIdList)) {
            $query = $builder = UserSite::getBuilderByUserId($this->auth->getIdentity('user_id'))
                ->leftJoin(Visit::class, 'v.site_id = us.id', 'v')
                ->leftJoin(UserOffice::class, 'v.hash = uo.hash', 'uo')
                ->columns('us.id, COUNT(uo.id) as accountWithDepositsCount')
                ->andWhere('us.id in ({idList:array})', [
                    'idList' => $idList
                ])->andWhere('uo.id in ({userIdList:array})', [
                    'userIdList' => $userIdList
                ])->groupBy('us.id')->getQuery()->execute();

            foreach ($query as $item) {
                $out[$item['id']] = $item['accountWithDepositsCount'];
            }
        }
        foreach ($idList as $id) {
            if (!isset($out[$id])) {
                $out[$id] = 0;
            }
        }
        return $out;
    }

    public function getSumDepositsGroupSiteIdByIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }
        $builder = UserSite::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('us.id, SUM(to.amount) as sumDeposits')
            ->leftJoin(Visit::class, 'v.site_id = us.id', 'v')
            ->leftJoin(UserOffice::class, 'v.hash = uo.hash', 'uo')
            ->leftJoin(TransactionOffice::class, 'to.user_office_id = uo.id', 'to')
            ->andWhere('to.office_deposit_id is not null and to.status = :status:', [
                'status' => TransactionOffice::STATUS_ACCEPT
            ])
            ->groupBy('us.id')
            ->andWhere('us.id in ({idList:array})', [
                'idList' => $idList
            ])->andWhere('to.date_create >= :dateStart: and to.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ]);
        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
                'instrumentId' => $this->mediaId
            ]);
        }
        $query = $builder->getQuery()->execute();
        $out = [];
        foreach ($query as $item) {
            $out[$item['id']] = $this->formatFloat($item['sumDeposits']);
        }
        foreach ($idList as $id) {
            if (!isset($out[$id])) {
                $out[$id] = 0;
            }
        }
        return $out;
    }

    public function getSumBonusGroupSiteIdByIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }
        $builder = UserSite::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('us.id, SUM(bo.amount) as sumBonus')
            ->leftJoin(Visit::class, 'v.site_id = us.id', 'v')
            ->leftJoin(UserOffice::class, 'v.hash = uo.hash', 'uo')
            ->leftJoin(Bonus::class, 'bo.user_office_id = uo.id', 'bo')
            ->andWhere('bo.status = :status:', [
                'status' => Bonus::STATUS_ACCEPT
            ])
            ->groupBy('us.id')
            ->andWhere('us.id in ({idList:array})', [
                'idList' => $idList
            ])->andWhere('bo.date_create >= :dateStart: and bo.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ]);
        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
                'instrumentId' => $this->mediaId
            ]);
        }
        $query = $builder->getQuery()->execute();
        $out = [];
        foreach ($query as $item) {
            $out[$item['id']] = $this->formatFloat($item['sumBonus']);
        }
        foreach ($idList as $id) {
            if (!isset($out[$id])) {
                $out[$id] = 0;
            }
        }
        return $out;
    }

    public function getIncomeAmountGroupSiteIdByIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }
        $builder = UserSite::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('us.id, SUM(to.amount) as income')
            ->leftJoin(Visit::class, 'v.site_id = us.id', 'v')
            ->leftJoin(UserOffice::class, 'v.hash = uo.hash', 'uo')
            ->leftJoin(TransactionOffice::class, 'to.user_office_id = uo.id', 'to')
            ->andWhere('to.status = :status: and to.type = :type:', [
                'status' => TransactionOffice::STATUS_ACCEPT,
                'type' => TransactionOffice::TYPE_BET_TRANSACTION
            ])
            ->groupBy('us.id')
            ->andWhere('us.id in ({idList:array})', [
                'idList' => $idList
            ])->andWhere('to.date_create >= :dateStart: and to.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ]);

        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
                'instrumentId' => $this->mediaId
            ]);
        }
        $query = $builder->getQuery()->execute();
        $out = [];
        foreach ($query as $item) {
            $out[$item['id']] = $this->formatFloat($item['income']);
        }
        foreach ($idList as $id) {
            if (!isset($out[$id])) {
                $out[$id] = 0;
            }
        }
        return $out;
    }

    public function getSumCommissionsGroupSiteIdByIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }
        $builder = UserSite::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('us.id, SUM(to.add_amount) as sumCommissions')
            ->leftJoin(Visit::class, 'v.site_id = us.id', 'v')
            ->leftJoin(UserOffice::class, 'v.hash = uo.hash', 'uo')
            ->leftJoin(TransactionOffice::class, 'to.user_office_id = uo.id', 'to')
            ->andWhere('to.status = :status: and to.type in ({typeList:array})', [
                'status' => TransactionOffice::STATUS_ACCEPT,
                'typeList' => [TransactionOffice::TYPE_BET_TRANSACTION, TransactionOffice::TYPE_PARTNER_TRANSACTION]
            ])
            ->groupBy('us.id')
            ->andWhere('us.id in ({idList:array})', [
                'idList' => $idList
            ])->andWhere('to.date_create >= :dateStart: and to.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ]);

        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
                'instrumentId' => $this->mediaId
            ]);
        }
        $query = $builder->getQuery()->execute();
        $out = [];
        foreach ($query as $item) {
            $out[$item['id']] = $this->formatFloat($item['sumCommissions']);
        }
        foreach ($idList as $id) {
            if (!isset($out[$id])) {
                $out[$id] = 0;
            }
        }
        return $out;
    }


    public function getSumDepositsGroupOfficeUserIdByIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }
        $builder = UserOffice::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('uo.id, SUM(to.amount) as sumDeposits')
            ->leftJoin(Visit::class, 'v.hash = uo.hash ', 'v')
            ->leftJoin(TransactionOffice::class, 'to.user_office_id = uo.id', 'to')
            ->andWhere('to.office_deposit_id is not null and to.status = :status:', [
                'status' => TransactionOffice::STATUS_ACCEPT
            ])->groupBy('uo.id')
            ->andWhere('uo.id in ({idList:array})', [
                'idList' => $idList
            ])->andWhere('to.date_create >= :dateStart: and to.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ]);
        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
                'instrumentId' => $this->mediaId
            ]);
        }
        if (!empty($this->officeUserId)) {
            $builder->andWhere('uo.id = :officeUserId:', [
                'officeUserId' => $this->officeUserId,
            ]);
        }
        $query = $builder->getQuery()->execute();
        $out = [];
        foreach ($query as $item) {
            $out[$item['id']] = $this->formatFloat($item['sumDeposits']);
        }
        foreach ($idList as $id) {
            if (!isset($out[$id])) {
                $out[$id] = 0;
            }
        }
        return $out;
    }

    public function getSumBonusGroupOfficeUserIdByIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }

        $builder = UserOffice::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('uo.id, SUM(bo.amount) as sumBonus')
            ->leftJoin(Visit::class, 'v.hash = uo.hash', 'v')
            ->leftJoin(Bonus::class, 'bo.user_office_id = uo.id', 'bo')
            ->andWhere('bo.status = :status:', [
                'status' => Bonus::STATUS_ACCEPT
            ])->groupBy('uo.id')
            ->andWhere('uo.id in ({idList:array})', [
                'idList' => $idList
            ])->andWhere('bo.date_create >= :dateStart: and bo.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ]);

        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
                'instrumentId' => $this->mediaId
            ]);
        }
        if (!empty($this->officeUserId)) {
            $builder->andWhere('uo.id = :officeUserId:', [
                'officeUserId' => $this->officeUserId,
            ]);
        }
        $query = $builder->getQuery()->execute();
        $out = [];
        foreach ($query as $item) {
            $out[$item['id']] = $this->formatFloat($item['sumBonus']);
        }
        foreach ($idList as $id) {
            if (!isset($out[$id])) {
                $out[$id] = 0;
            }
        }
        return $out;
    }

    public function getIncomeAmountGroupOfficeUserIdByIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }
        $builder = UserOffice::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('uo.id, SUM(to.amount) as income')
            ->leftJoin(Visit::class, 'v.hash = uo.hash ', 'v')
            ->leftJoin(TransactionOffice::class, 'to.user_office_id = uo.id', 'to')
            ->andWhere('to.status = :status: and to.type = :type:', [
                'status' => TransactionOffice::STATUS_ACCEPT,
                'type' => TransactionOffice::TYPE_BET_TRANSACTION
            ])->groupBy('uo.id')
            ->andWhere('uo.id in ({idList:array})', [
                'idList' => $idList
            ])->andWhere('to.date_create >= :dateStart: and to.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ]);

        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
                'instrumentId' => $this->mediaId
            ]);
        }
        if (!empty($this->officeUserId)) {
            $builder->andWhere('uo.id = :officeUserId:', [
                'officeUserId' => $this->officeUserId,
            ]);
        }
        $query = $builder->getQuery()->execute();
        $out = [];
        foreach ($query as $item) {
            $out[$item['id']] = $this->formatFloat($item['income']);
        }
        foreach ($idList as $id) {
            if (!isset($out[$id])) {
                $out[$id] = 0;
            }
        }
        return $out;
    }

    public function getSumCommissionsGroupOfficeUserIdByIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }

        $builder = UserOffice::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('uo.id,  SUM(to.add_amount) as sumCommissions')
            ->leftJoin(Visit::class, 'v.hash = uo.hash ', 'v')
            ->leftJoin(TransactionOffice::class, 'to.user_office_id = uo.id', 'to')
            ->andWhere('to.status = :status: and to.type = :type:', [
                'status' => TransactionOffice::STATUS_ACCEPT,
                'type' => TransactionOffice::TYPE_BET_TRANSACTION
            ])->groupBy('uo.id')
            ->andWhere('to.status = :status: and to.type in ({typeList:array})', [
                'status' => TransactionOffice::STATUS_ACCEPT,
                'typeList' => [TransactionOffice::TYPE_BET_TRANSACTION, TransactionOffice::TYPE_PARTNER_TRANSACTION]
            ])->andWhere('to.date_create >= :dateStart: and to.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ]);

        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_type = :instrumentType: and v.instrument_id = :instrumentId:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
                'instrumentId' => $this->mediaId
            ]);
        }
        if (!empty($this->officeUserId)) {
            $builder->andWhere('uo.id = :officeUserId:', [
                'officeUserId' => $this->officeUserId,
            ]);
        }
        $query = $builder->getQuery()->execute();
        $out = [];
        foreach ($query as $item) {
            $out[$item['id']] = $this->formatFloat($item['sumCommissions']);
        }
        foreach ($idList as $id) {
            if (!isset($out[$id])) {
                $out[$id] = 0;
            }
        }
        return $out;
    }

    /*----------------------------------------------media---------------------------------------------------*/
    public function getRegistrationCountGroupMediaIdByIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }
        $builder = Visit::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('v.instrument_id, COUNT(uo.id) as registrationCount')
            ->leftJoin(Media::class, 'v.instrument_id = m.id', 'm')
            ->leftJoin(UserOffice::class, 'v.hash = uo.hash', 'uo')
            ->andWhere('v.instrument_id in ({idList:array})', [
                'idList' => $idList
            ])
            ->groupBy('v.instrument_id')
            ->andWhere('uo.date_create >= :dateStart: and uo.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ])->andWhere('v.instrument_type = :instrumentType:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
            ]);

        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_id = :instrumentId:', [
                'instrumentId' => $this->mediaId
            ]);
        }
        if (!empty($this->mediaType)) {
            $builder->andWhere('m.media_type = :mediaType:', ['mediaType' => $this->mediaType]);
        }

        $query = $builder->getQuery()->execute();
        $out = [];
        foreach ($query as $item) {
            $out[$item['instrument_id']] = $item['registrationCount'];
        }
        foreach ($idList as $id) {
            if (!isset($out[$id])) {
                $out[$id] = 0;
            }
        }
        return $out;
    }

    public function getAccountWithDepositsCountGroupMediaIdByIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }
        $query = Visit::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('uo.id')
            ->leftJoin(Media::class, 'v.instrument_id = m.id', 'm')
            ->leftJoin(UserOffice::class, 'v.hash = uo.hash', 'uo')
            ->leftJoin(TransactionOffice::class, 'to.user_office_id = uo.id', 'to')
            ->andWhere('to.office_deposit_id is not null and to.status = :status:', [
                'status' => TransactionOffice::STATUS_ACCEPT
            ])
            ->groupBy('uo.id')
            ->andWhere('v.instrument_type = :instrumentType:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
            ])
            ->andWhere('uo.date_create >= :dateStart: and uo.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ]);

        if (!empty($this->siteId)) {
            $query->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $query->andWhere('v.instrument_id = :instrumentId:', [
                'instrumentId' => $this->mediaId
            ]);
        }
        if (!empty($this->mediaType)) {
            $query->andWhere('m.media_type = :mediaType:', ['mediaType' => $this->mediaType]);
        }

        $userIdList = [];
        $query = $query->getQuery()->execute();
        foreach ($query as $item) {
            $userIdList[] = $item['id'];
        }
        $out = [];
        if (!empty($userIdList)) {
            $query = Visit::getBuilderByUserId($this->auth->getIdentity('user_id'))
                ->leftJoin(UserOffice::class, 'v.hash = uo.hash', 'uo')
                ->columns('v.instrument_id, COUNT(uo.id) as accountWithDepositsCount')
                ->andWhere('v.instrument_id in ({idList:array})', [
                    'idList' => $idList
                ])->andWhere('uo.id in ({userIdList:array})', [
                    'userIdList' => $userIdList
                ])->groupBy('v.instrument_id')->getQuery()->execute();

            foreach ($query as $item) {
                $out[$item['instrument_id']] = $item['accountWithDepositsCount'];
            }
        }
        foreach ($idList as $id) {
            if (!isset($out[$id])) {
                $out[$id] = 0;
            }
        }
        return $out;
    }

    public function getSumDepositsGroupMediaIdByIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }
        $builder = Visit::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('v.instrument_id, SUM(to.amount) as sumDeposits')
            ->leftJoin(Media::class, 'v.instrument_id = m.id', 'm')
            ->leftJoin(UserOffice::class, 'v.hash = uo.hash', 'uo')
            ->leftJoin(TransactionOffice::class, 'to.user_office_id = uo.id', 'to')
            ->andWhere('to.office_deposit_id is not null and to.status = :status:', [
                'status' => TransactionOffice::STATUS_ACCEPT
            ])
            ->groupBy('v.instrument_id')
            ->andWhere('v.instrument_id in ({idList:array})', [
                'idList' => $idList
            ])->andWhere('to.date_create >= :dateStart: and to.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ])->andWhere('v.instrument_type = :instrumentType:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
            ]);
        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_id = :instrumentId:', [
                'instrumentId' => $this->mediaId
            ]);
        }
        if (!empty($this->mediaType)) {
            $builder->andWhere('m.media_type = :mediaType:', ['mediaType' => $this->mediaType]);
        }

        $query = $builder->getQuery()->execute();
        $out = [];
        foreach ($query as $item) {
            $out[$item['instrument_id']] = $this->formatFloat($item['sumDeposits']);
        }
        foreach ($idList as $id) {
            if (!isset($out[$id])) {
                $out[$id] = 0;
            }
        }
        return $out;
    }

    public function getSumBonusGroupMediaIdByIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }
        $builder = Visit::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('v.instrument_id, SUM(bo.amount) as sumBonus')
            ->leftJoin(UserOffice::class, 'v.hash = uo.hash', 'uo')
            ->leftJoin(Bonus::class, 'bo.user_office_id = uo.id', 'bo')
            ->andWhere('bo.status = :status:', [
                'status' => Bonus::STATUS_ACCEPT
            ])
            ->groupBy('v.instrument_id')
            ->andWhere('v.instrument_id in ({idList:array})', [
                'idList' => $idList
            ])->andWhere('bo.date_create >= :dateStart: and bo.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ])->andWhere('v.instrument_type = :instrumentType:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
            ]);
        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_id = :instrumentId:', [
                'instrumentId' => $this->mediaId
            ]);
        }
        $query = $builder->getQuery()->execute();
        $out = [];
        foreach ($query as $item) {
            $out[$item['instrument_id']] = $this->formatFloat($item['sumBonus']);
        }
        foreach ($idList as $id) {
            if (!isset($out[$id])) {
                $out[$id] = 0;
            }
        }
        return $out;
    }

    public function getIncomeAmountGroupUserIdByIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }
        $builder = Visit::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('v.instrument_id, SUM(to.amount) as income')
            ->leftJoin(Media::class, 'v.instrument_id = m.id', 'm')
            ->leftJoin(UserOffice::class, 'v.hash = uo.hash', 'uo')
            ->leftJoin(TransactionOffice::class, 'to.user_office_id = uo.id', 'to')
            ->andWhere('to.status = :status: and to.type = :type:', [
                'status' => TransactionOffice::STATUS_ACCEPT,
                'type' => TransactionOffice::TYPE_BET_TRANSACTION
            ])
            ->groupBy('v.instrument_id')
            ->andWhere('v.instrument_id in ({idList:array})', [
                'idList' => $idList
            ])->andWhere('to.date_create >= :dateStart: and to.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ]);

        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_id = :instrumentId:', [
                'instrumentId' => $this->mediaId
            ]);
        }
        if (!empty($this->mediaType)) {
            $builder->andWhere('m.media_type = :mediaType:', ['mediaType' => $this->mediaType]);
        }

        $query = $builder->getQuery()->execute();
        $out = [];
        foreach ($query as $item) {
            $out[$item['instrument_id']] = $this->formatFloat($item['income']);
        }
        foreach ($idList as $id) {
            if (!isset($out[$id])) {
                $out[$id] = 0;
            }
        }
        return $out;
    }

    public function getSumCommissionsGroupMediaIdByIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }
        $builder = Visit::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('v.instrument_id, SUM(to.add_amount) as sumCommissions')
            ->leftJoin(UserOffice::class, 'v.hash = uo.hash', 'uo')
            ->leftJoin(TransactionOffice::class, 'to.user_office_id = uo.id', 'to')
            ->andWhere('to.status = :status: and to.type in ({typeList:array})', [
                'status' => TransactionOffice::STATUS_ACCEPT,
                'typeList' => [TransactionOffice::TYPE_BET_TRANSACTION, TransactionOffice::TYPE_PARTNER_TRANSACTION]
            ])
            ->groupBy('v.instrument_id')
            ->andWhere('v.instrument_id in ({idList:array})', [
                'idList' => $idList
            ])->andWhere('v.instrument_type = :instrumentType:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
            ])->andWhere('to.date_create >= :dateStart: and to.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ]);

        if (!empty($this->siteId)) {
            $builder->andWhere('v.site_id = :siteId:', ['siteId' => $this->siteId]);
        }
        if (!empty($this->mediaId)) {
            $builder->andWhere('v.instrument_id = :instrumentId:', [
                'instrumentId' => $this->mediaId
            ]);
        }
        if (!empty($this->mediaType)) {
            $builder->andWhere('m.media_type = :mediaType:', ['mediaType' => $this->mediaType]);
        }
        $query = $builder->getQuery()->execute();
        $out = [];
        foreach ($query as $item) {
            $out[$item['instrument_id']] = $this->formatFloat($item['sumCommissions']);
        }
        foreach ($idList as $id) {
            if (!isset($out[$id])) {
                $out[$id] = 0;
            }
        }
        return $out;
    }

    public function getSiteMapGroupMediaIdByIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }
        $query = Visit::getBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('v.instrument_id, us.*')
            ->leftJoin(UserSite::class, 'v.site_id = us.id', 'us')
            ->andWhere('v.instrument_id in ({idList:array})', [
                'idList' => $idList
            ])->andWhere('v.instrument_type = :instrumentType:', [
                'instrumentType' => Visit::INSTRUMENT_TYPE_MEDIA_SOURCE,
            ])->getQuery()->execute();
        $out = [];
        foreach ($query as $item) {
            $out[$item['instrument_id']] = $item['us'];
        }
        return $out;
    }

    /*---------------------------------------партнёры----------------------------------------------*/

    public function getShowCountBySiteIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }
        $query = UserSite::getBuilder()
            ->columns('us.id, COUNT(s.hash) as showCount')
            ->rightJoin(Showing::class, 'us.id = s.site_id', 's')
            ->groupBy('us.id')
            ->andWhere('s.date_create >= :dateStart: and s.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ])->andWhere('us.id in ({idList:array})', [
                'idList' => $idList
            ])->getQuery()
            ->execute();

        $out = [];
        foreach ($query as $item) {
            $out[$item['id']] = $item['showCount'];
        }
        return $out;
    }

    public function getClickCountBySiteIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }
        $query = UserSite::getBuilder()
            ->rightJoin(Visit::class, 'us.id = v.site_id', 'v')
            ->columns('us.id, COUNT(v.hash) as clickCount')
            ->groupBy('us.id')
            ->andWhere('v.date_create >= :dateStart: and v.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ])->andWhere('us.id in ({idList:array})', [
                'idList' => $idList
            ])->getQuery()
            ->execute();

        $out = [];
        foreach ($query as $item) {
            $out[$item['id']] = $item['clickCount'];
        }
        return $out;
    }

    public function getRegistrationCountBySiteIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }
        $query = UserSite::getBuilder()
            ->rightJoin(Visit::class, 'us.id = v.site_id', 'v')
            ->leftJoin(UserOffice::class, 'v.hash = uo.hash', 'uo')
            ->columns('us.id, COUNT(uo.id) as registrationCount')
            ->groupBy('us.id')
            ->andWhere('uo.date_create >= :dateStart: and uo.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ])->andWhere('us.id in ({idList:array})', [
                'idList' => $idList
            ])->getQuery()
            ->execute();

        $out = [];
        foreach ($query as $item) {
            $out[$item['id']] = $item['registrationCount'];
        }
        return $out;
    }

    public function getAccountWithDepositsCountBySiteIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }
        $query = User::getPartnerBuilderByUserId($this->auth->getIdentity('user_id'))
            ->columns('to.user_office_id')
            ->leftJoin(TransactionOffice::class, 'to.user_id = u.id', 'to')
            ->leftJoin(UserSite::class, 'u.id = us.user_id', 'us')
            ->andWhere('to.office_deposit_id is not null and to.status = :status:', [
                'status' => TransactionOffice::STATUS_ACCEPT
            ])->andWhere('to.date_create >= :dateStart: and to.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ])->andWhere('us.id in ({idList:array})', [
                'idList' => $idList
            ])->groupBy('to.user_office_id')->getQuery()->execute();

        $userIdWidthDepositList = [];
        foreach ($query as $item) {
            $userIdWidthDepositList[] = $item['user_office_id'];
        }

        if (empty($userIdWidthDepositList)) {
            return [];
        }

        $query = UserSite::getBuilder()
            ->rightJoin(Visit::class, 'us.id = v.site_id', 'v')
            ->leftJoin(UserOffice::class, 'v.hash = uo.hash', 'uo')
            ->columns('us.id, COUNT(uo.id) as accountWithDepositsCount')
            ->groupBy('us.id')
            ->andWhere('uo.id in ({idList:array})', [
                'idList' => $userIdWidthDepositList
            ])->getQuery()
            ->execute();

        $out = [];
        foreach ($query as $item) {
            $out[$item['id']] = $item['accountWithDepositsCount'];
        }
        return $out;
    }

    public function getSumDepositsBySiteIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }
        $query = UserSite::getBuilder()
            ->rightJoin(Visit::class, 'us.id = v.site_id', 'v')
            ->leftJoin(UserOffice::class, 'v.hash = uo.hash', 'uo')
            ->leftJoin(TransactionOffice::class, 'to.user_office_id = uo.id', 'to')
            ->andWhere('to.office_deposit_id is not null and to.status = :status:', [
                'status' => TransactionOffice::STATUS_ACCEPT
            ])
            ->columns('us.id, SUM(to.amount) as sumDeposits')
            ->groupBy('us.id')
            ->andWhere('to.date_create >= :dateStart: and to.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ])->andWhere('us.id in ({idList:array})', [
                'idList' => $idList
            ])->getQuery()
            ->execute();

        $out = [];
        foreach ($query as $item) {
            $out[$item['id']] = $this->formatFloat($item['sumDeposits']);
        }
        return $out;
    }

    public function getSumCommissionsBySiteIdList($idList)
    {
        if (empty($idList)) {
            return [];
        }
        $query = UserSite::getBuilder()
            ->rightJoin(Visit::class, 'us.id = v.site_id', 'v')
            ->leftJoin(UserOffice::class, 'v.hash = uo.hash', 'uo')
            ->leftJoin(TransactionOffice::class, 'to.user_office_id = uo.id', 'to')
            ->andWhere('to.type = :type: and to.status = :status:', [
                'status' => TransactionOffice::STATUS_ACCEPT,
                'type' => TransactionOffice::TYPE_PARTNER_TRANSACTION
            ])
            ->columns('us.id, SUM(to.add_amount) as sumCommissions')
            ->groupBy('us.id')
            ->andWhere('to.date_create >= :dateStart: and to.date_create <= :dateEnd:', [
                'dateStart' => $this->dateStart,
                'dateEnd' => $this->dateEnd,
            ])->andWhere('us.id in ({idList:array})', [
                'idList' => $idList
            ])->getQuery()
            ->execute();

        $out = [];
        foreach ($query as $item) {
            $out[$item['id']] = $this->formatFloat($item['sumCommissions']);
        }
        return $out;
    }

}