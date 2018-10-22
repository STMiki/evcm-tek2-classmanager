<?php

if (!defined('ROOT_DIR')) define('ROOT_DIR', '/home/etvoilacfy/www/');

require_once(ROOT_DIR.'../vendor/autoload.php');
require_once(ROOT_DIR.'refresh.php');
require_once(ROOT_DIR.'refreshInvoice.php');

require_once('log.php');

class Zoho {

    private $api_domain;
    private $defaultHeader;
    private $db;
    private $token;

    /* ----- CRM ----- */
    const HELPER  = 'Helper';
    const CLIENT  = 'Contacts';
    const MISSION = 'Prestations';
    const FINANCE = 'Zoho_Finance';
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
        if (!empty($this->token))
            return;
        $data = $db->query('SELECT access_token_zoho, api_domain_zoho FROM CLE;')->fetch();
        $this->token = $data['access_token_zoho'];
        $this->api_domain = $data['api_domain_zoho'];
        $this->defaultHeader = [
            'headers' => [
                'Authorization' => 'Bearer '.$this->token,
                'Cache-Control' => 'no-cache'
            ]
        ];
    }

    public function getConstants()
    {
        $reflection = new ReflectionClass($this);
        return ($reflection->getConstants());
    }

    public function isModule($module)
    {
        foreach ($this->getConstants() as $value) {
            if ($module == $value) {
                return (true);
            }
        }
        return (false);
    }

    /* --------------- C  R  M --------------- */
    public function getFromCRM($module, string $criteria, string $valueOfCriteria)
    {
        if (!$this->isModule($module))
            throw new ZohoException('Module '.$module.' not found.');
        $this->getCleApi();
        $url = $api_domain.'crm/v2/'.$module.'/search?'.$criteria.'='.$valueOfCriteria.')';

        $client = new \GuzzleHttp\Client();

        try {
            $res = $client->request('GET', $url, $this->defaultHeader);
        } catch (Exception $e) {
            throw new ZohoException('Url invalid.');
        }

        if ($res->getStatusCode() == 204)
            throw new ZohoException('Value "'.$valueOfCriteria.'" not found for "'.$value'".');
        else if ($res->getStatusCode() != 200)
            throw new ZohoException('Unexpected Error. id: 01');

        return (json_decode($res->getBody(), true)['data'][0]);
    }

    public function updateToCrm($module, string $critere, string $value, array $data)
    {
        $id = getFromCRM($module, $critere, $value)['id'];
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

        $res = $client->request('POST', $url, $header);

        if ($res->getStatusCode() != 200) {
            //
        }
        return (json_decode($res->getBody(), true)['data'][0]['code']);
    }
}
