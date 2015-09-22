<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 9/22/15
 * Time: 4:26 PM
 */

namespace Favor\Playbook\Interfaces;

use Favor\Playbook\Applicant;

interface ClientInterface
{
    /**
     * searches playbook for a user matching the applicant object criteria
     *
     * @param Applicant $applicant
     *
     * @return Applicant
     * @throws ApplicantNotFoundException
     * @throws \Exception
     */
    public function searchApplicants(Applicant $applicant);

    /**
     * adds a applicant to playbook according to the applicant object values
     *
     * @param Applicant $applicant
     *
     * @return Applicant
     * @throws \Exception
     */
    public function addApplicant(Applicant $applicant);

    /**
     * updates an applicant in playbook. Must pass validations.
     *
     * @param Applicant $applicant
     *
     * @return Applicant
     * @throws \Exception
     */
    public function updateApplicant(Applicant $applicant);

}
