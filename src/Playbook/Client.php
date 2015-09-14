<?php

namespace Playbook;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Playbook\Exception\ApplicantNotFoundException;
use Playbook\Exception\InvalidApplicantException;


class Client {

    protected $client;
    protected $user;
    protected $api_key;
    protected $base_uri = "https://www.playbookhr.com";

    public function __construct($user, $api_key)
    {
        $this->user = $user;
        $this->api_key = $api_key;

        $this->client = new GuzzleClient([
            "base_uri" => $this->base_uri
        ]);

    }

    /**
     * searches playbook for a user matching the applicant object criteria
     * @param Applicant $applicant
     * @return mixed
     * @throws ApplicantNotFoundException
     * @throws \Exception
     */
    public function searchApplicants(Applicant $applicant)
    {

        if (!$applicant->email) {
            throw new InvalidApplicantException("Applicant is missing a required field");
        }

        try {
            $response = $this->client->request("GET", '/api/v2/search', [
                "auth" => [$this->user, $this->api_key],
                "query" => $applicant->toArray()
            ]);
        } catch (ClientException $e) {
            throw new \Exception($e->getMessage());
        } catch (ServerException $e) {
            throw new \Exception("ERROR: ".$e->getMessage());
        }

        $body = $response->getBody();
        $json = json_decode((string) $body, true);

        if($json['found'] == false){
            throw new ApplicantNotFoundException("Applicant was not found");
        }

        return $applicant->getNewInstance(array_filter($json['applicant']), $this);

    }

    /**
     * adds a applicant to playbook according to the applicant object values
     * @param Applicant $applicant
     * @return mixed
     * @throws \Exception
     */
    public function addApplicant(Applicant $applicant)
    {

        if (!$applicant->isValid()) {
            throw new InvalidApplicantException("Applicant is missing a required field");
        }

        try {
            $response = $this->client->request("POST", '/api/v2/applicants', [
                "auth" => [$this->user, $this->api_key],
                "form_params" => $applicant->toArray()
            ]);
        } catch (ClientException $e) {
            throw new \Exception($e->getMessage());
        } catch (ServerException $e) {
            throw new \Exception("ERROR: ".$e->getMessage());
        }

        $body = $response->getBody();
        $json = json_decode((string) $body, true);
        $applicant->assignProps($json);


        return $applicant->getNewInstance($applicant, $this);
    }

    /**
     * updates an applicant in playbook. Must pass validations.
     * @param Applicant $applicant
     * @return mixed
     * @throws \Exception
     */
    public function updateApplicant(Applicant $applicant)
    {

        if (!$applicant->isValid()) {
            throw new InvalidApplicantException("Applicant is missing a required field");
        }

        try {
            $response = $this->client->request("PUT", '/api/v2/applicants', [
                "auth" => [$this->user, $this->api_key],
                "form_params" => $applicant->toArray()
            ]);
        } catch (ClientException $e) {
            throw new \Exception($e->getMessage());
        } catch (ServerException $e) {
            throw new \Exception("ERROR: ".$e->getMessage());
        }

        $body = $response->getBody();
        $json = json_decode((string) $body, true);
        $applicant->assignProps($json);

        return $applicant->getNewInstance($applicant, $this);
    }

}


