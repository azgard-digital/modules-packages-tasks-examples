<?php
/**
*
The task:
 Our advertising platform promotes mobile applications , it contains a campaign for each such application
 our publishers bring users that install and then use these applications
 the platform is reported about the install event and other application usage events of these users
 for example "app_open", "registration" and "purchase" events
 this stream of events is saved in a database

 To achieve quality goals we optimize campaigns by blacklisting publishers who do not qualify  the campaign's expections

 For example, a campaign may expect the number of "purchase" events a publisher brings to be equal or
 greater than 10% of the number of installs that publishers brought,
 or else the publisher should be blacklisted on that campaign

 To maintain these publisher blacklists we have a job process (OptimizationJob) runs every hour

 Campaign objects contain an optimizationProps object that includes the following properties:
 * sourceEvent and measuredEvent - in the above example sourceEvent would be "install" and measuredEvent
   would be "purchase"
 * threshold - the minimum of occurrences of sourceEvent, if a publisher has less sourceEvents that the threshold ,
   then she should not be blacklisted
 * ratioThreshold - the minimum ratio of sourceEvent occurrences to measuredEvent occurrences

 Event objects contain their type, the campaignId and publisherId

 Below is the begining of the implementation of the OptimizationJob class,
 A. complete the implementation maintaining campaigns' publishers blacklists
    Keep in mind that blacklisted publishers can only be removed from the blacklist if they cross the ratio

 B. make sure publishers are notified with an email whenever they are added or removed from a campaign's blacklist
    Please do not implement the email mechanism - we assume you know how to send an email

 */
interface IBlackListEmailSender
{
    const REMOVE_FROM_BLACKLIST = 1;
    const ADD_TO_BLACKLIST = 2;

    /**
     * Add mail event to queue
     * @param int $publisherId
     * @param int $eventId
     * @return bool
     */
    public function addToMailList(int $publisherId, int $eventId):bool;

    /**
     * Send emails from queue
     * @return void
     */
    public function send():void;
}

class BlackListEmailSender implements IBlackListEmailSender
{
    private $queue = [];

    /**
     * @inheritdoc
     */
    public function addToMailList(int $publisherId, int $eventId):bool
    {
        $this->queue[] = ['publisher' => $publisherId, 'event' => $eventId];
    }

    /**
     * @inheritdoc
     */
    public function send():void
    {
        //send emails functionality was not implement according task description
    }
}

abstract class BlackListHelper
{
    public static function calculateRatio(int $events, int $ratioThreshold):int
    {
       return (int)round($events * ($ratioThreshold / 100));
    }
}

class CampaignDataSource
{
    private $campaigns = [];

    /**
     * @param Campaign $campaign
     */
    public function addCampaign(Campaign $campaign):void
    {
        $this->campaigns[] = $campaign;
    }

    /**
     * @return array
     */
    public function getCampaigns():array
    {
        return $this->campaigns;
    }
}

class EventsDataSource
{
    private $events = [];

    /**
     * @param Event $event
     */
    public function addEvent(Event $event):void
    {
        $this->events[] = $event;
    }

    /**
     * @return array
     */
    public function getEventsSince(string $condition = ''):array
    {
        return $this->events;
    }
}


class OptimizationJob
{
    /**
     * @var CampaignDataSource
     */
    private $campaign;

    /**
     * @var EventsDataSource
     */
    private $events;

    /**
     * OptimizationJob constructor.
     * @param CampaignDataSource $campaign
     * @param EventsDataSource $events
     */
    public function __construct(CampaignDataSource $campaign, EventsDataSource $events)
    {
        $this->events = $events;
        $this->campaign = $campaign;
    }

    /**
     * Group events by company, publisher and event name
     * @return array
     */
    protected function getGroupedEvents():array
    {
        $eventsGroupByCompany = [];

        /** @var Event $event */
        foreach($this->events->getEventsSince("2 weeks ago") as $event) {

            if (!isset($eventsGroupByCompany[$event->getCampaignId()][$event->getPublisherId()][$event->getType()])) {
                $eventsGroupByCompany[$event->getCampaignId()][$event->getPublisherId()][$event->getType()] = 1;
                continue;
            }

            $eventsGroupByCompany[$event->getCampaignId()][$event->getPublisherId()][$event->getType()] += 1;
        }

        return $eventsGroupByCompany;
    }

    public function run()
    {
        $eventsGroupByCompany = $this->getGroupedEvents();

        if (empty($eventsGroupByCompany)) {
            return;
        }

        /** @var Campaign $campaign */
        foreach ($this->campaign->getCampaigns() as $campaign) {
            $optimizationProps = $campaign->getOptimizationProps();
            $blackList = $campaign->getBlackList();
            $emailSender = new BlackListEmailSender();

            if (isset($eventsGroupByCompany[$campaign->getId()]) && is_array($eventsGroupByCompany[$campaign->getId()])) {

                foreach ($eventsGroupByCompany[$campaign->getId()] as $publisherId => $item) {

                    if (isset($item[$optimizationProps->sourceEvent])) {

                        if ($item[$optimizationProps->sourceEvent] < $optimizationProps->threshold) {
                            array_push($blackList, $publisherId);
                            $emailSender->addToMailList($publisherId, IBlackListEmailSender::ADD_TO_BLACKLIST);
                            continue;
                        }

                        if (isset($item[$optimizationProps->measuredEvent])) {
                            $sourceEvents = (int)$item[$optimizationProps->sourceEvent];
                            $measuredEvents = (int)$item[$optimizationProps->measuredEvent];

                            $ratio = BlackListHelper::calculateRatio($sourceEvents, $optimizationProps->ratioThreshold);

                            if ($measuredEvents >= $ratio) {
                                //remove from blacklist
                                if (($key = array_search($publisherId, $blackList)) !== false) {
                                    unset($blackList[$key]);
                                    $emailSender->addToMailList($publisherId, IBlackListEmailSender::REMOVE_FROM_BLACKLIST);
                                }

                            } else {
                                array_push($blackList, $publisherId);
                                $emailSender->addToMailList($publisherId, IBlackListEmailSender::ADD_TO_BLACKLIST);
                            }
                        }
                    }
                }
            }

            if (count($blackList)) {
                $campaign->saveBlacklist($blackList);
                $emailSender->send();
            }
        }

    }
}


class Campaign {
    /** @var  OptimizationProps $optProps */
    private $optProps;

    /** @var  int */
    private $id;

    /** @var  array */
    private $publisherBlacklist;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getOptimizationProps() {
        return $this->optProps;
    }
    public function getBlackList() {
        return $this->publisherBlacklist;
    }
    public function saveBlacklist($blacklist) {
        // dont implement
    }
}

class OptimizationProps {
    public $threshold, $sourceEvent, $measuredEvent, $ratioThreshold;
}

class Event {
    private $type;
    private $campaignId;
    private $publisherId;

    public function getType() {
        // for example "install"
        return $this->type;
    }
    public function getTs() {
        return $this->ts;
    }
    public function getCampaignId() {
        return $this->campaignId;
    }
    public function getPublisherId() {
        return $this->publisherId;
    }
}
