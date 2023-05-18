<?php

namespace Dyce\Pvit;

use Exception;
use Illuminate\Support\Facades\Log;

class Payment
{
    private string $codeMarchand;
    private string $telMarchand;
    private int $montant;
    private string $ref;
    private string $telClient;
    private string $token;
    private int $action;
    private string $service;
    private string $operateur;
    private string $redirect;
    private string $agent;
    private string $otp;

    private string $pvitUrl = 'https://mypvitapi.pro/api/pvit-secure-full-api-v3.kk';
    private array $actionValues = [1, 2, 3, 5, 7];
    private array $serviceValues = ['REST', 'WEB'];
    private array $operateurValues = ['AM', 'MC', 'VM', 'BP'];

    public function __construct(string $codeMarchand, string $token)
    {
        $this->codeMarchand = $codeMarchand;
        $this->telMarchand = '';
        $this->montant = 0;
        $this->ref = '';
        $this->telClient = '';
        $this->token = $token;
        $this->action = 1;
        $this->service = 'REST';
        $this->operateur = 'AM';
        $this->redirect = '';
        $this->agent = 'DycePvit';
        $this->otp = '';
    }

    /**
     * @throws Exception
     */
    private function validate()
    {
        // Required parameters
        if (empty($this->codeMarchand)) throw new Exception("'codeMarchand' parameter is required");
        if (empty($this->montant)) throw new Exception("'montant' parameter is required");
        if (empty($this->ref)) throw new Exception("'ref' parameter is required");
        if (empty($this->telClient)) throw new Exception("'telClient' parameter is required");
        if (empty($this->action)) throw new Exception("'action' parameter is required");
        if (empty($this->service)) throw new Exception("'service' parameter is required");
        if (empty($this->operateur)) throw new Exception("'operateur' parameter is required");
        if ($this->service === 'WEB' && empty($this->redirect)) throw new Exception("'redirect' parameter is required");

        if (!empty($this->telMarchand) && !$this->isValidNumber($this->telMarchand)) throw new Exception("telMarchand is not a correct phone number");
        if (!$this->isValidNumber($this->telClient)) throw new Exception("telClient is not a correct phone number");

        if (strlen($this->ref) > 13) throw new Exception("the 'ref' value must be 13 characters maximum");

        if (!in_array($this->action, $this->actionValues)) throw new Exception("the 'action' value is incorrect. Possible values are : " . implode(", ", $this->actionValues));
        if (!in_array($this->service, $this->serviceValues)) throw new Exception("the 'service' value is incorrect. Possible values are : " . implode(", ", $this->serviceValues));
        if (!in_array($this->operateur, $this->operateurValues)) throw new Exception("the 'operateur' value is incorrect. Possible values are : " . implode(", ", $this->operateurValues));

        if ($this->montant < 100 || $this->montant > 490000) throw new Exception("the 'montant' value must be between 100 and 490000");
    }

    private function isValidNumber(string $number): bool
    {
        return ($number && strlen($number) === 9 && preg_match("/(0|9){1}(6|7|9){1}([0-9]){7}/", $number));
    }

    /**
     * @throws Exception
     */
    public function send(): Response
    {
        $this->validate();

        $xml = $this->request();

        return $this::parse($xml);
    }

    /**
     * @throws Exception
     */
    private function request(): string
    {
        $params = 'code_marchand=' . $this->codeMarchand . '&montant=' . $this->montant . '&numero_client=' . $this->telClient . '&reference_marchand=' . $this->ref . '&token=' . $this->token . '&action=' . $this->action . '&service=' . $this->service . '&operateur=' . $this->operateur . '&redirect=' . $this->redirect . '&agent=' . $this->agent . '&otp=' . $this->otp;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $this->pvitUrl);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        Log::debug('PVIT - Payment Request', ['data' => $params]);
        $result = curl_exec($ch);
        Log::debug('PVIT - Payment Result', ['result' => $result]);

        if ($result === false) throw new Exception("Error while executing the query");

        return $result;
    }

    /**
     * @throws Exception
     */
    public static function parse(string $xml): Response
    {
        libxml_use_internal_errors(true);

        $xmlObj = simplexml_load_string("$xml");
        if ($xmlObj === false) throw new Exception("The data is not correctly formatted in XML.");

        $json = json_encode($xmlObj, JSON_UNESCAPED_UNICODE);
        $resArray = json_decode($json, true);

        return Response::fromArray($resArray);
    }

    /**
     * @param string $codeMarchand
     */
    public function setCodeMarchand(string $codeMarchand)
    {
        $this->codeMarchand = $codeMarchand;
    }

    /**
     * @param string $telMarchand
     */
    public function setTelMarchand(string $telMarchand)
    {
        $this->telMarchand = $telMarchand;
    }

    /**
     * @param int $montant
     */
    public function setMontant(int $montant)
    {
        $this->montant = $montant;
    }

    /**
     * @param string $ref
     */
    public function setRef(string $ref)
    {
        $this->ref = $ref;
    }

    /**
     * @param string $telClient
     */
    public function setTelClient(string $telClient)
    {
        $this->telClient = $telClient;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token)
    {
        $this->token = $token;
    }

    /**
     * @param int $action
     */
    public function setAction(int $action)
    {
        $this->action = $action;
    }

    /**
     * @param string $service
     */
    public function setService(string $service)
    {
        $this->service = $service;
    }

    /**
     * @param string $operateur
     */
    public function setOperateur(string $operateur)
    {
        $this->operateur = $operateur;
    }

    /**
     * @param string $redirect
     */
    public function setRedirect(string $redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * @param string $agent
     */
    public function setAgent(string $agent)
    {
        $this->agent = $agent;
    }

    /**
     * @param string $otp
     */
    public function setOtp(string $otp)
    {
        $this->otp = $otp;
    }

}
