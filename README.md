# playbook-hr
PlaybookHR Client

[![Code Climate](https://codeclimate.com/github/favordelivery/playbook-hr/badges/gpa.svg)](https://codeclimate.com/github/favordelivery/playbook-hr)
[![Test Coverage](https://codeclimate.com/github/favordelivery/playbook-hr/badges/coverage.svg)](https://codeclimate.com/github/favordelivery/playbook-hr/coverage)

## Set up API client
This will be passed to the applicant object to communicate with the API
```php
$client = new Client(self::PLAYBOOK_USER, self::PLAYBOOK_TOKEN);
```

## Add an Applicant
```php
$applicant = new Applicant([
    'email' => $email,
    'name'  => $name,
    'address' => $address,
    'phone' => $phone,
    'status' => 'created'
], $client);

$applicant = $applicant->create(); // creates the applicant on playbook
```

## Find Applicant
```php
$applicant = new Applicant([
    'email' => $email
],
$client);

$applicant = $applicant->fetch(); // $applicant now has the applicant information from playbook
```

## Update Applicant
```php
$applicant = new Applicant([
    'email' => $email
], $client);

$applicant = $applicant->fetch(); // ensure that the record actually exists

$applicant->status = "edited"; // set a new value after fetch

$applicant = $applicant->save(); // save object with new value
```

## Extending Applicant
```php
class CustomApplicant extends \Playbook\Applicant {
  protected $props = [
    "new_field" => "some default"
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
  public function getNewFieldAttribute($value)
  {
    return strtolower($value);
  }

  /**
   * Mutator for new_field
   * used for when we want data to be formatted on set
   */
  public function setNewFieldAttribute($key, $value)
  {
    $this->props[$key] = strtolower($value);
  }

}
```
