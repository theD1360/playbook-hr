<?php

namespace Playbook;

use Playbook\Exception\ApplicantNotFoundException;

class Applicant {

    protected $client;

    protected $props = [];
    protected $__defaultProps = [
        "name"   => null,
        "email"  => null,
        "market" => null,
        "file"   => null
    ];

    protected $validation = [];
    protected $__defaultValidation = [
        'email' => "required|valid_email",
        'name'  => "required|max_len,100|min_len,6"
    ];

    public $validationErrors;

    public function __construct($props, Client $client = null)
    {
        $this->assignProps($props);

        if ($client) {
            $this->client = $client;
        }

    }

    /**
     * create dynamic setters and getters 'methods' for properties
     * @param $method
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function __call($method, $params)
    {
        if(preg_match("/^(set|get)([\w]*)$/", $method, $matches)){
            switch ($matches[1]) {
                case "set":
                    return $this->{$matches[2]} = $params[0];
                break;
                case "get":
                    return $this->{$matches};
                break;
            }
        }

        throw new \Exception("Method not found: $method");
    }

    /**
     * dynamic getter to use $props and pass through
     * @param $key
     * @return mixed
     */
    public function __get($key){
        $accessor_name = self::toCamelCase("get_{$key}_attribute");
        if(method_exists($this, $accessor_name)) {
            $value = $this->{$accessor_name}($this->props[$key]);
        } else {
            $value = array_key_exists($key, $this->props) ? $this->props[$key] : null;
        }

        return $value;
    }

    public function __set($key, $value){
        $mutator_name =self::toCamelCase("set_{$key}_attribute");
        if(method_exists($this, $mutator_name)) {
            $value = $this->{$mutator_name}($key, $value);
        } else {
            $this->props[$key] = $value;
        }

        return $value;
    }

    /**
     * helper method to return camelcase string
     * @param $word
     * @return mixed
     */
    public static function toCamelCase($val)
    {
        $val = str_replace(' ', '', ucwords(str_replace('_', ' ', $val)));
        $val = strtolower(substr($val,0,1)).substr($val,1);
        return $val;
    }

    public function fetch(){
        if ($this->client) {
            return $this->client->searchApplicants($this);
        }
    }

    /**
     * add an applicant to playbook via model
     * @throws Exception\ApplicantNotFoundException
     * @throws \Exception
     */
    public function create()
    {
        if ($this->client) {
            return $this->client->addApplicant($this);
        }
    }

    /**
     * save the applicant that is being held in model
     * @throws Exception\ApplicantNotFoundException
     * @throws \Exception
     */
    public function save()
    {
        if ($this->client) {
            return $this->client->updateApplicant($this);
        }
    }

    /**
     * checks to see if a model is valid to save to playbook
     * @return bool
     */
    public function isValid()
    {
        $validations = array_merge($this->validation, $this->__defaultValidation);
        $valid = \GUMP::is_valid($this->props, $validations);
        if ($valid !== true){
            $this->validationErrors = $valid;
            return false;
        }
        return true;
    }

    /**
     * applies properties to the model.
     * @param $props
     * @return $this
     */
    public function assignProps($props)
    {
        if ($props instanceof Applicant) {
            $props = $props->toArray();
        }

        $props = array_merge($this->__defaultProps, $this->props, $props);

        if (is_array($props) && !empty($props)) {
            foreach($props as $k=>$v){
                $this->{$k} = $v;
            }
        }
        return $this;
    }

    /**
     * gets a new instance of this object with new properties and client
     * @param array $newProps
     * @param Client|null $client
     * @return mixed
     */
    public function getNewInstance($newProps = [], Client $client = null)
    {
        $class_name = get_called_class();
        if (empty($newProps)) {
            $newProps = $this->props;
        }
        if (!$client) {
            $client = $this->client;
        }

        return new $class_name($newProps, $client);
    }

    /**
     * returns props
     * @return array
     */
    public function toArray(){
        return $this->props;
    }


}
