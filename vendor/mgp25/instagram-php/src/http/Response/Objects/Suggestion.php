<?php

namespace InstagramAPI;

class Suggestion
{
    public $media_infos;
    public $social_context;
    public $algorithm;
    public $thumbnail_urls;
    public $value;
    public $caption;
    public $user;
    public $large_urls;
    public $media_ids;
    public $icon;

    public function __construct($data)
    {
        $this->media_infos = $data['media_infos'];
        $this->social_context = $data['social_context'];
        $this->algorithm = $data['algorithm'];
        $this->thumbnail_urls = $data['thumbnail_urls'];
        $this->value = $data['value'];
        $this->caption = $data['caption'];
        $this->user = new User($data['user']);
        $this->large_urls = $data['large_urls'];
        $this->media_ids = $data['media_ids'];
        $this->icon = $data['icon'];
    }

    public function getMediaInfo()
    {
        return $this->media_infos;
    }

    public function getSocialContext()
    {
        return $this->social_context;
    }

    public function getalgorithm()
    {
        return $this->algorithm;
    }

    public function getThumbnailUrls()
    {
        return $this->thumbnail_urls;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function getLargeUrls()
    {
        return $this->large_urls;
    }

    public function getMediaIds()
    {
        return $this->media_ids;
    }

    public function getIcon()
    {
        return $this->icon;
    }
}
