<?php

use Faker\Factory;
use Playbook\Applicant;

class ApplicantTest extends \PHPUnit_Framework_TestCase {

    /**
     * @dataProvider applicantProvider
     */
    public function testConstructor($name, $email, $zip)
    {

        $applicant = new Applicant(["name"=>$name, 'email' => $email, "address_zip"=> $zip]);

        $this->assertEquals($email, $applicant->email);
        $this->assertEquals($name, $applicant->name);
        $this->assertEquals($zip, $applicant->address_zip);
    }

    /**
     * @dataProvider applicantProvider
     */
    public function testGetters($name, $email, $zip)
    {
        $email = "testEmail@gmail.com";
        $name = "Test Applicant";
        $zip = 78756;
        $applicant = new Applicant(["name"=>$name, 'email' => $email, "address_zip"=> $zip]);

        $this->assertEquals($email, $applicant->getEmail());
        $this->assertEquals($name, $applicant->getName());
        $this->assertEquals($zip, $applicant->getAddressZip());

    }

    /**
     * @dataProvider applicantProvider
     */
    public function testSetters($name, $email, $zip)
    {
        $email = "testEmail@gmail.com";
        $name = "Test Applicant";
        $zip = 78756;
        $applicant = new Applicant(["name"=>$name, 'email' => $email, "address_zip"=> $zip]);

        $this->assertEquals($email, $applicant->email);
        $this->assertEquals($name, $applicant->name);
        $this->assertEquals($zip, $applicant->address_zip);
    }



    /**
     * @dataProvider applicantProvider
     */
    public function testValidators($name, $email, $zip)
    {
        $applicant = new Applicant(["name"=>$name, 'email' => $email, "address_zip"=> $zip]);

        $this->assertEquals(true, $applicant->isValid());

    }

    /**
     * creates fake data
     * @return array
     */
    public function applicantProvider()
    {
        $faker = Factory::create();
        $resp = [];
        for ($i = 0; $i < 10; $i++) {
            $resp[] = [
                $faker->name,
                $faker->email,
                $faker->postcode
            ];
        }

        return $resp;
    }

}
