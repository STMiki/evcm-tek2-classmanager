<?php

if (!defined('ROOT_DIR')) define('ROOT_DIR', '/var/www/html/class/log/');
// if (!defined('ROOT_DIR')) define('ROOT_DIR', '/home/etvoilacfy/www/');
//
require_once(ROOT_DIR.'../vendor/autoload.php');
// require_once(ROOT_DIR.'refresh.php');
// require_once(ROOT_DIR.'refreshInvoice.php');

require_once('log.php');

/*
 * Class Zoho
 *
 * This class is something that get or update from zoho.
 * The api take some time to respond, so do not overuse it.
 *
 * for all update, we need the id (the one generated by zoho) integred in the data
 * exemple: array(data) ['id'=> 99037000000000453, 'etc' => '...'];
 *
 */
final class Zoho {

    private $api_domain;
    private $defaultHeader;
    private $db;
    private $token;
    private $historic = array();

    /* ----- CRM ----- */
    const HELPER      = 'Helpers';
    const CLIENT      = 'Contacts';
    const MISSION     = 'Prestations';
    const WEB_CONTACT = 'Contacts-web';

    public function __construct($db)
    {
        $this->db = $db;
        $this->token = null;
    }

    public function __destruct()
    {
        $this->token = null;
        $this->db = null;
    }

    private function getCleApi()
    {
        return (true); // TODO: remove this for test
        if (!empty($this->token))
            return;
        $req = $this->db->query('SELECT `access_token_zoho`, `api_domain_zoho` FROM `CLE`;');
        $data = $req->fetch();
        $this->token = $data['access_token_zoho'];
        $this->api_domain = $data['api_domain_zoho'];
        $this->defaultHeader = [
            'headers' => [
                'Authorization' => 'Bearer '.$this->token,
                'Cache-Control' => 'no-cache'
            ]
        ];
    }

    private function getConstants()
    {
        $reflection = new ReflectionClass($this);
        return ($reflection->getConstants());
    }

    public function isModule($module)
    {
        return (array_search($module, $this->getConstants()) === false ? false : true);
    }

    public function rollBack()
    {
        $last = null;
        $result = true;
        foreach ($entry as $historic) {
            if ($entry['method'] == 'POST') {
                if ($last === null)
                    $result = false;
                else
                    $this->updateToCRM($last['module'], $last['data']);
            }
            $last = $entry;
        }
        return ($result);
    }

    /* --------------- C  R  M --------------- */
    public function getFromCRM($module, string $criteria, string $valueOfCriteria, bool $mandatory=false)
    {
        return (true); // TODO: remove this for test
        if (!$this->isModule($module)) {
            printLog(__METHOD__, 'the module "'.$module.'" is not found.', true);
            throw new ZohoException('Module "'.$module.'" not found.');
        }
        $this->getCleApi();
        if ($mandatory === true)
            $url = $this->api_domain.'crm/v2/'.$module.'/search?'.$criteria.'='.$valueOfCriteria;
        else
            $url = $this->api_domain.'crm/v2/'.$module.'/search?criteria=%28'.$criteria.':equals:'.$valueOfCriteria.'%29';

        $client = new \GuzzleHttp\Client();

        try {
            $res = $client->request('GET', $url, $this->defaultHeader);
        } catch (Exception $e) {
            if ($res->getStatusCode() == 404) {
                printLog(__METHOD__, 'URL invalid: "'.$url.'".', true);
                throw new ZohoException('Url invalid.');
            }
        }

        if ($res->getStatusCode() == 204) {
            printLog(__METHOD__, 'Value "'.$valueOfCriteria.'" not found for "'.$value.'".', true);
            throw new ZohoException('Value "'.$valueOfCriteria.'" not found for "'.$value.'".');
        } else if ($res->getStatusCode() != 200) {
            printLog(__METHOD__, 'Unexpected error (id: 01):'.
                                'module : "'.$module.'"\n\t'.
                                'critere: "'.$criteria.'"\n\t'.
                                'value  : "'.$valueOfCriteria.'"\n\t'.
                                'URL    : "'.$url.'"\n\t'.
                                'code   : "'.$res->getStatusCode().'"', true);
            throw new ZohoException('Unexpected Error. id: 01');
        }

        $this->historic[] = ['method' => 'GET',
                             'url' => $url,
                             'module' => $module,
                             'header' => $this->defaultHeader,
                             'result' => $res->getbody()];

        return (json_decode($res->getBody(), true)['data'][0]);
    }

    /* /!\ ----------= DO NOT OVERUSE THIS METHOD =---------- /!\ */
    /* /!\ --------= IT TAKE AN ETERNITY TO RESPOND =-------- /!\ */
    public function getAllFromCRM($module)
    {
        return (true); // TODO: remove this for test
        if (!$this->isModule($module)) {
            printLog(__METHOD__, 'the module "'.$module.'" is not found.', true);
            throw new ZohoException('Module "'.$module.'" not found.');
        }
        $this->getCleApi();

        $url = $this->api_domain.'/crm/v2/'.$module;

        $client = new \GuzzleHttp\Client();

        try {
            $res = $client->request('GET', $url, $this->defaultHeader);
        } catch (Exception $e) {
            if ($res->getStatusCode() == 404) {
                printLog(__METHOD__, 'URL invalid: "'.$url.'".', true);
                throw new ZohoException('Url invalid.');
            }
        }

        if ($res->getStatusCode() != 200) {
            printLog(__METHOD__, 'Unexpected error (id: 01):'.
                                'module : "'.$module.'"\n\t'.
                                'URL    : "'.$url.'"\n\t'.
                                'code   : "'.$res->getStatusCode().'"', true);
            throw new ZohoException('Unexpected Error. id: 02');
        }

        $this->historic[] = ['method' => 'GET',
                             'url' => $url,
                             'module' => $module,
                             'header' => $this->defaultHeader,
                             'result' => $res->getbody()];

        return (json_decode($res->getBody(), true)['data']);
    }

    public function updateToCRM($module, array $data)
    {
        return (true); // TODO: remove this for test
        if (!isset($data['id'])) {
            printLog(__METHOD__, 'unknow id: '.$data['id'], true);
            throw new ZohoException('Id not found');
        }
        if (!$this->isModule($module)) {
            printLog(__METHOD__, 'the module "'.$module.'" is not found.', true);
            throw new ZohoException('Module "'.$module.'" not found.');
        }
        $this->getCleApi();

        $client = new \GuzzleHttp\Client();
        $url = $api_domain.'crm/v2/'.$module;
        $header = [
            'headers' => [
                'Authorization' => 'Bearer '.$this->token,
                'Cache-Control' => 'no-cache',
                'Content-Type'  => 'application/json'
            ],
            'json' => [
                'data' => [ $data ],
                'trigger' => [ 'approval' ],
                'wf_trigger' => true
            ]
        ];

        try {
           $res = $client->request('PUT', $url, $header);
        } catch (Exception $e) {
            if ($res->getStatusCode() == 404) {
                printLog(__METHOD__, 'URL invalid: "'.$url.'".', true);
                throw new ZohoException('Url invalid.');
            }
        }

        if ($res->getStatusCode() != 200) {
            printLog(__METHOD__, 'Unexpected error (id: 03):'.
                                'module : "'.$module.'"\n'.
                                'critere: "'.$criteria.'"\n'.
                                'value  : "'.$value.'"\n'.
                                'URL    : "'.$url.'"\n'.
                                'code   : "'.$res->getStatusCode().'"\n'.
                                'body   : "'.$res->getBody().'"', true);
            throw new ZohoException('Unexpected Error (id: 02)');
        }

        $this->historic[] = ['method' => 'PUT',
                             'module' => $module,
                             'url' => $url,
                             'header' => $this->defaultHeader,
                             'data' => $data];

        if (json_decode($res->getBody(), true)['data'][0]['code'] == 'SUCCESS')
            return (true);
        return (false);
    }

    public function insertToCRM($module, array $data)
    {
        return (true); // TODO: remove this for test
        if (!$this->isModule($module)) {
            printLog(__METHOD__, 'the module "'.$module.'" is not found.', true);
            throw new ZohoException('Module "'.$module.'" not found.');
        }
        $this->getCleApi();

        $client = new \GuzzleHttp\Client();
        $url = $api_domain.'crm/v2/'.$module;
        $header = [
            'headers' => [
                'Authorization' => 'Bearer '.$this->token,
                'Cache-Control' => 'no-cache',
                'Content-Type'  => 'application/json'
            ],
            'json' => [
                'data' => [ $data ],
                'trigger' => [ 'approval' ],
                'wf_trigger' => true
            ]
        ];

        try {
           $res = $client->request('POST', $url, $header);
        } catch (Exception $e) {
            if ($res->getStatusCode() == 404) {
                printLog(__METHOD__, 'URL invalid: "'.$url.'".', true);
                throw new ZohoException('Url invalid.');
            }
        }

        if ($res->getStatusCode() != 200) {
            printLog(__METHOD__, 'Unexpected error (id: 03):'.
                                'module : "'.$module.'"\n'.
                                'critere: "'.$criteria.'"\n'.
                                'value  : "'.$value.'"\n'.
                                'URL    : "'.$url.'"\n'.
                                'code   : "'.$res->getStatusCode().'"\n'.
                                'body   : "'.$res->getBody().'"', true);
            throw new ZohoException('Unexpected Error (id: 02)');
        }

        $this->historic[] = ['method' => 'PUT',
                             'module' => $module,
                             'url' => $url,
                             'header' => $this->defaultHeader,
                             'data' => $data];

        if (json_decode($res->getBody(), true)['data'][0]['code'] == 'SUCCESS')
            return (true);
        return (false);
    }
}
