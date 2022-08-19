<?php

namespace Dyce\Pvit;

class Response
{
    private string $statut;
    private string $message;
    private string $token;
    private string $telClient;
    private string $operateur;
    private int $type;
    private string $ref;
    private string $transaction;
    private string $agent;

    public static function fromArray(array $data): Response
    {
        $response = new Response();

        $response->setStatut(static::getValue($data, 'STATUT'));
        $response->setRef(static::getValue($data, 'REF'));
        $response->setTransaction(static::getValue($data, 'NUM_TRANSACTION'));
        $response->setType(static::getValue($data, 'TYPE'));
        $response->setOperateur(static::getValue($data, 'OPERATEUR'));
        $response->setTelClient(static::getValue($data, 'TEL_CLIENT'));
        $response->setToken(static::getValue($data, 'TOKEN'));
        $response->setMessage(static::getValue($data, 'MESSAGE'));
        $response->setAgent(static::getValue($data, 'AGENT'));

        return $response;
    }

    private static function getValue(array $arr, string $key): string
    {
        return (!isset($arr[$key]) || gettype($arr[$key]) === 'array') ? '' : $arr[$key];
    }

    /**
     * @return string
     */
    public function getAgent(): string
    {
        return $this->agent;
    }

    /**
     * @param string $agent
     */
    private function setAgent(string $agent)
    {
        $this->agent = $agent;
    }

    /**
     * @return string
     */
    public function getTelClient(): string
    {
        return $this->telClient;
    }

    /**
     * @param string $telClient
     */
    private function setTelClient(string $telClient)
    {
        $this->telClient = $telClient;
    }

    /**
     * @return string
     */
    public function getOperateur(): string
    {
        return $this->operateur;
    }

    /**
     * @param string $operateur
     */
    private function setOperateur(string $operateur)
    {
        $this->operateur = $operateur;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    private function setType(int $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getRef(): string
    {
        return $this->ref;
    }

    /**
     * @param string $ref
     */
    private function setRef(string $ref)
    {
        $this->ref = $ref;
    }

    /**
     * @return string
     */
    public function getTransaction(): string
    {
        return $this->transaction;
    }

    /**
     * @param string $transaction
     */
    private function setTransaction(string $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @return string
     */
    public function getStatut(): string
    {
        return $this->statut;
    }

    /**
     * @param string $statut
     */
    private function setStatut(string $statut)
    {
        $this->statut = $statut;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    private function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    private function setToken(string $token)
    {
        $this->token = $token;
    }


}
