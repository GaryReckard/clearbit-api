<?php

namespace wlbrough\clearbit;

use wlbrough\clearbit\Abstracts\Api;

class EnrichmentApi extends Api
{
    private static $apiUrlTemplate = 'https://pk:@%s%s.clearbit.com/v2/%s/find?%s=%s';
    private $httpClient = null;

    public function __construct($key, $client = null)
    {
        self::$apiUrlTemplate = preg_replace('(pk)', $key, self::$apiUrlTemplate);
        $this->httpClient = $client;
    }

    public function combined($email)
    {
        $apiUrl = $this->composeUrl('person', 'combined', 'email', $email);
        return $this->call($apiUrl, $this->httpClient);
    }

    public function person($email, $subscribe = false)
    {
        $apiUrl = $this->composeUrl('person', 'people', 'email', $email);

        if ($subscribe) {
            $apiUrl .= '&subscribe=true';
        }

        return $this->call($apiUrl, $this->httpClient);
    }

    public function company($domain)
    {
        $apiUrl = $this->composeUrl('company', 'companies', 'domain', $domain);
        return $this->call($apiUrl, $this->httpClient);
    }

    private function composeUrl($subdomain, $path, $query, $parameter)
    {
        $streaming = $this->useStreaming ? '' : '-streaming';
        return sprintf(self::$apiUrlTemplate, $subdomain, $streaming, $path, $query, $parameter);
    }
}