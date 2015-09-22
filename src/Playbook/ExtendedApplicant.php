<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 9/22/15
 * Time: 5:53 PM
 */

namespace Favor\Playbook;


class ExtendedApplicant extends Applicant
{
    protected $props = [
        "always_upper" => "",
        "always_lower" => "",
    ];

    /**
     * using GUMP validation
     */
    protected $validation = [
        'new_field' => "required|alpha_space"
    ];

    /**
     * Accessor for new_field
     * used for when we want data to be formatted on access
     */
    public function getAlwaysUpperAttribute($value)
    {
        return strtoupper($value);
    }

    /**
     * Mutator for new_field
     * used for when we want data to be formatted on set
     */
    public function setAlwaysLowerAttribute($key, $value)
    {
        $this->props[$key] = strtolower($value);
    }

}
