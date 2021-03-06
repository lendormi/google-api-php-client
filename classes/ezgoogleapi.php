<?php

/**
 * @copyright Copyright (C) 2014 Ralantonisainana Dany All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace eZGoogleApi\Kernel;
require_once 'extension/ezgoogleapi/lib/react/promise/src/functions_include.php';
class eZGoogleApi
{
    public function __construct($authenticationType, $params = array())
    {
        $ini = \eZINI::instance();
        $this->$authenticationType = $authenticationType;
        switch ($this->$authenticationType) {
            case 'oauth2':
                $this->client = static::accessoOAuthService($params);
                break;
            case 'public':
                $this->client = static::accessPublicService($params);
                break;
            case 'user':
                $this->client = static::accessUserService($params);
                break;
            default:
                $this->client = new \Google_Client();
                break;
        }
        if (isset($params['scopes']) && $params['scopes']) {
            $this->scopes = $params['scopes'];
        }
    }

    public function hasClient() {
        if ($this->client) {
            return true;
        }
        return false;
    }

    public function getClient()
    {
        return $this->client;
    }

    public static function accessoOAuthService($params)
    {
        $googleApiIni       = \eZINI::instance('googleapi.ini');

        $clientID          = $googleApiIni->variable('GoogleAPISettings', 'OAuthServiceClientID');
        $emailAddress      = $googleApiIni->variable('GoogleAPISettings', 'OAuthServiceEmailAddress');
        $credentialsJson   = true;
        if ($googleApiIni->hasVariable('GoogleAPISettings', 'OAuthServiceJSONLocation') && $googleApiIni->variable('GoogleAPISettings', 'OAuthServiceJSONLocation')) {
            $keyFile           = $googleApiIni->variable('GoogleAPISettings', 'OAuthServiceJSONLocation');
        } elseif($googleApiIni->hasVariable('GoogleAPISettings', 'OAuthServiceP12Location') && $googleApiIni->variable('GoogleAPISettings', 'OAuthServiceP12Location')) {
           \eZDebug::writeWarning( "P12s are deprecated in favor of service account JSON, which can be generated in the" );
           \eZDebug::writeWarning( "Credentials section of Google Developer Console." );
           return false;
        }
        if(!isset($keyFile) || !file_exists($keyFile)) {
            \eZDebug::writeError( "No Key Client Credentials" );
            return false;
        }
        $client            = new \Google_Client();
        $applicationName   = !empty($params['application_name']) ? $params['application_name'] : "Service Google Ezpublish";
        $scopes            = !empty($params['scopes']) ? $params['scopes'] : "";
        $client->setAuthConfig($keyFile);
        $client->setApplicationName($applicationName);
        $client->setScopes($scopes);
        $client->setAccessType('offline_access');
        return $client;
    }

    public static function accessPublicService($params)
    {
        $googleApiIni    = \eZINI::instance('googleapi.ini');
        $apiKey          = $googleApiIni->variable('GoogleAPISettings', 'PublicApiKey');
        $applicationName = !empty($params['application_name']) ? $params['application_name'] : "Service Google Ezpublish";
        $client          = new \Google_Client();
        $client->setApplicationName($applicationName);

        // Set the API Key
        $client->setDeveloperKey($apiKey);
        return $client;
    }

    public static function accessUserService($params)
    {
    }
}
