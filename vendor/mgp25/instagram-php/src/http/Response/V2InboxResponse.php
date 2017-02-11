<?php

namespace InstagramAPI;

class V2InboxResponse extends Response
{
    public $pending_requests_total;
    public $seq_id;
    public $pending_requests_users;
    public $inbox;
    public $subscription;

    public function __construct($response)
    {
        if (self::STATUS_OK == $response['status']) {
            $this->pending_requests_total = $response['pending_requests_total'];
            $this->seq_id = $response['seq_id'];
            $this->pending_requests_users = $response['pending_requests_users'];
            $this->inbox = new Inbox($response['inbox']);
            $this->subscription = $response['subscription'];
        } else {
            $this->setMessage($response['message']);
        }
        $this->setStatus($response['status']);
    }

    public function getPendingRequestsTotal()
    {
        return $this->pending_requests_total;
    }

    public function getSeqId()
    {
        return $this->seq_id;
    }

    public function getPendingRequestsUsers()
    {
        return $this->pending_requests_users;
    }

    /**
     * @return Inbox
     */
    public function getInbox()
    {
        return $this->inbox;
    }

    public function getSubscription()
    {
        return $this->subscription;
    }
}
