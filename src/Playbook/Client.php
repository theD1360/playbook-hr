<?php

namespace Favor\Playbook;

use GuzzleHttp\Client as GuzzleClient;
use Favor\Playbook\Exception\HttpRequestException;
use Favor\Playbook\Exception\ApplicantNotFoundException;
use Favor\Playbook\Exception\InvalidApplicantException;
use Favor\Playbook\Interfaces\ClientInterface;



class Client implements ClientInterface
{

    protected $client;
    protected $user;
    protected $api_key;
    protected $base_uri = "https://workforce.intuit.com";

    public function __construct($user, $api_key, $options = [])
    {
        $this->user = $user;
        $this->api_key = $api_key;

        $this->client = new GuzzleClient(
            array_merge(
                [
                    'base_uri' => $this->base_uri,
                    "auth" => [$this->user, $this->api_key],
                ],
                $options
            )
        );
    }

    /**
     * searches playbook for a user matching the applicant object criteria
     *
     * @param Applicant $applicant
     *
     * @return mixed
     * @throws ApplicantNotFoundException
     * @throws \Exception
     */
    public function searchApplicants(Applicant $applicant)
    {

        if (!$applicant->email) {
            throw new InvalidApplicantException("Applicant is missing a required field");
        }

        $props = $this->guzzleRequest("GET", '/api/v2/search', [
            "query" => $applicant->toArray(),
        ]);

        if ($props['found'] === false) {
            throw new ApplicantNotFoundException("Applicant was not found");
        }

        return $applicant->getNewInstance(array_filter($props['applicant']), $this);

    }

    /**
     * adds a applicant to playbook according to the applicant object values
     *
     * @param Applicant $applicant
     *
     * @return mixed
     * @throws \Exception
     */
    public function addApplicant(Applicant $applicant)
    {

        $this->validateApplicant($applicant);

        $props = $this->guzzleRequest("POST", '/api/v2/applicants', [
            "form_params" => $applicant->toArray(),
        ]);

        $applicant->assignProps($props);

        return $applicant->getNewInstance($applicant, $this);
    }

    /**
     * updates an applicant in playbook. Must pass validations.
     *
     * @param Applicant $applicant
     *
     * @return mixed
     * @throws \Exception
     */
    public function updateApplicant(Applicant $applicant)
    {

        $this->validateApplicant($applicant);

        $props = $this->guzzleRequest("PUT", '/api/v2/applicants', [
            "form_params" => $applicant->toArray(),
        ]);

        $applicant->assignProps($props);

        return $applicant->getNewInstance($applicant, $this);
    }

    /**
     * check that our applicant is valid or throw our exception
     *
     * @param Applicant $applicant
     */
    private function validateApplicant(Applicant $applicant)
    {
        if (!$applicant->isValid()) {
            throw new InvalidApplicantException("Applicant is missing a required field");
        }
    }

    private function guzzleRequest($method, $url, $options = [])
    {
        try {
            $response = $this->client->request($method, $url, $options);

        } catch (\Exception $e) {
            throw new HttpRequestException($e->getMessage(), $e->getCode(), $e);
        }

        $body = $response->getBody();
        $json = json_decode((string)$body, true);
        return $json;
    }


}


