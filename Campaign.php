<?php //-->

/*
 * This file is part of the Core package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Eden\Foursquare;

/**
 * Four square campaign
 *
 * @package Eden
 * @category four square
 * @author Christian Blanquera cblanquera@openovate.com
 */
class Campaign extends Base
{
    const URL_CAMPAIGN_ADD = 'https://api.foursquare.com/v2/campaigns/add';
    const URL_CAMPAIGN_GET_LIST = 'https://api.foursquare.com/v2/campaigns/list';
    const URL_CAMPAIGN_TIME_SERIES = 'https://api.foursquare.com/v2/campaigns/%s/timeseries';
    const URL_CAMPAIGN_DELETE = 'https://api.foursquare.com/v2/campaigns/%s/delete';
    const URL_CAMPAIGN_END = 'https://api.foursquare.com/v2/campaigns/%s/end';
    const URL_CAMPAIGN_START = 'https://api.foursquare.com/v2/campaigns/%s/start';
    
    /**
     * Construct - Storing Tokens
     * 
     * @param string
     * @return void
     */
    public function __construct($token)
    {
        //argument test
        Argument::i()->test(1, 'string');
        $this->token = $token;
    }
    
    /**
     * DateTime when the campaign is to be started (seconds since epoch). 
     * If this parameter is not specified, the campaign will be in a pending 
     * state until the campaign is actually started via the start action. 
     * If this parameter is specified and is in the past, the campaign 
     * will be started as of the current time.
     * 
     * @param string YYYY-MM-DD
     * @return Eden\Foursquare\Campaign
     */
    public function setStartTime($startTime)
    {
        //argument 1 must be a string
        Argument::i()->test(1, 'string');
        $this->query['startAt'] = strtotime($startTime);
        
        return $this;
    }
    
    /**
     * DateTime when the campaign is to be automatically deactivated.
     * 
     * @param string YYYY-MM-DD
     * @return Eden\Foursquare\Campaign
     */
    public function setEndTime($endTime)
    {
        //argument 1 must be a string
        Argument::i()->test(1, 'string');
        $this->query['endAt'] = strtotime($endTime);
        
        return $this;
    }
    
    /**
     * ID of an existing campaign to copy. 
     * 
     * @param string
     * @return Eden\Foursquare\Campaign
     */
    public function setCampaignId($campaignId)
    {
        //argument 1 must be a string
        Argument::i()->test(1, 'string');
        $this->query['campaignId'] = $campaignId;
        
        return $this;
    }
    
    /**
     * If specified, limits response to campaigns involving the given special
     * 
     * @param string
     * @return Eden\Foursquare\Campaign
     */
    public function setSpecialId($specialId)
    {
        //argument 1 must be a string
        Argument::i()->test(1, 'string');
        $this->query['specialId'] = $specialId;
        
        return $this;
    }
    
    /**
     * If specified, limits response to campaigns involving the given group
     * 
     * @param string
     * @return Eden\Foursquare\Campaign
     */
    public function setGroupId($groupId)
    {
        //argument 1 must be a string
        Argument::i()->test(1, 'string');
        $this->query['groupId'] = $groupId;
        
        return $this;
    }
    
    /**
     * Accepted fields are pending, scheduled, active, 
     * expired, depleted, stopped, notStarted, ended, all
     * 
     * @param string
     * @return Eden\Foursquare\Campaign
     */
    public function setStatus($status)
    {
        //argument 1 must be a string
        Argument::i()->test(1, 'string');
        
        //if the input value is not allowed
        if (!in_array($status, array('pending', 'scheduled', 'active', 'expired', 'depleted', 'stopped', 'notStarted', 'ended', 'all'))) {
            //throw error
            Argument::i()
                ->setMessage(Argument::INVALID_CAMPAIGN_STATUS)
                ->addVariable($status)
                ->trigger();
        }
        
        $this->query['status'] = $status;
        
        return $this;
    }
    
    /**
     * Create a campaign. The special must be started in order
     * for it to be visible to users.
     *  
     * @param string|null required (unless campaignId has been provided). Special ID
     * @param string|null required (unless venueId has been provided)
     * @param string|null required (unless groupId has been provided)
     * @return array
     */
    public function createCampaign($specialId = null, $groupId = null, $venueId = null)
    {
        //argument test
        Argument::i()
            ->test(1, 'string', 'null')     //argument 1 must be a string or null
            ->test(2, 'string', 'null')     //argument 2 must be a string or null
            ->test(3, 'string', 'null');    //argument 3 must be a string or null
        
        $this->query['specialId'] = $specialId;
        $this->query['groupId'] = $groupId;
        $this->query['venueId'] = $venueId;
        
        return $this->post(self::URL_CAMPAIGN_ADD, $this->query);
    }
    
    /**
     * List all campaigns matching the given criteria.
     *  
     * @return array
     */
    public function getList()
    {
        return $this->getResponse(self::URL_CAMPAIGN_GET_LIST, $this->query);
    }
    
    /**
     * Get daily campaign stats over a given time range.
     *  
     * @param string The campaign id to retrieve stats for.
     * @return array
     */
    public function getTimeSeries($campaignId)
    {
        //argument 1 must be a string
        Argument::i()->test(1, 'string');
        
        return $this->post(sprintf(self::URL_CAMPAIGN_TIME_SERIES, $campaignId), $this->query);
    }
    
    /**
     * Delete a campaign that has never been activated.
     *  
     * @param string The ID of the campaign to delete.
     * @return array
     */
    public function deleteCampaign($campaignId)
    {
        //argument 1 must be a string
        Argument::i()->test(1, 'string');
        
        return $this->post(sprintf(self::URL_CAMPAIGN_DELETE, $campaignId));
    }
    
    /**
     * End a campaign.
     *  
     * @param string The ID of the campaign to end.
     * @return array
     */
    public function endCampaign($campaignId)
    {
        //argument 1 must be a string
        Argument::i()->test(1, 'string');
        
        return $this->post(sprintf(self::URL_CAMPAIGN_END, $campaignId));
    }
    
    /**
     * Start a campaign.
     *  
     * @param string The ID of the campaign to start.
     * @return array
     */
    public function startCampaign($campaignId)
    {
        //argument 1 must be a string
        Argument::i()->test(1, 'string');
        
        return $this->post(sprintf(self::URL_CAMPAIGN_START, $campaignId), $this->query);
    }
}
