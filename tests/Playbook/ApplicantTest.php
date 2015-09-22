<?php

use Faker\Factory;
use Favor\Playbook\Applicant;

class ApplicantTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider applicantProvider
     */
    public function testConstructor($name, $email, $zip)
    {

        $applicant = new Applicant(["name" => $name, 'email' => $email, "address_zip" => $zip]);

        $this->assertEquals($email, $applicant->email);
        $this->assertEquals($name, $applicant->name);
        $this->assertEquals($zip, $applicant->address_zip);
    }

    /**
     * @dataProvider applicantProvider
     */
    public function testGetters($name, $email, $zip)
    {
        $applicant = new Applicant(["name" => $name, 'email' => $email, "address_zip" => $zip]);

        $this->assertEquals($email, $applicant->getEmail());
        $this->assertEquals($name, $applicant->getName());
        $this->assertEquals($zip, $applicant->getAddressZip());

    }

    /**
     * @dataProvider applicantProvider
     */
    public function testSetters($name, $email, $zip)
    {

        $applicant = new Applicant();

        $applicant->setName($name);
        $applicant->setEmail($email);
        $applicant->setAddressZip($zip);

        $this->assertEquals($email, $applicant->email);
        $this->assertEquals($name, $applicant->name);
        $this->assertEquals($zip, $applicant->address_zip);
    }


    /**
     * @dataProvider applicantProvider
     */
    public function testValidators($name, $email, $zip)
    {
        $applicant = new Applicant(["name" => $name, 'email' => $email, "address_zip" => $zip]);

        $this->assertEquals(true, $applicant->isValid());

        $applicant->name = null;
        $this->assertEquals(false, $applicant->isValid());

        $applicant->name = $name;
        $applicant->email = null;
        $this->assertEquals(false, $applicant->isValid());

        $applicant->name = null;
        $applicant->email = null;
        $this->assertEquals(false, $applicant->isValid());
    }

    /**
     * @dataProvider applicantProvider
     */
    public function testGetNewInstance($name, $email, $zip)
    {
        $applicant = new Applicant(["name" => $name, 'email' => $email, "address_zip" => $zip]);
        $newApplicant = $applicant->getNewInstance();

        $this->assertInstanceOf('Favor\Playbook\Applicant', $newApplicant);
        $this->assertEquals($name, $newApplicant->name);
        $this->assertEquals($email, $newApplicant->email);
        $this->assertEquals($zip, $newApplicant->address_zip);

    }

    /**
     * @dataProvider applicantProvider
     */
    public function testFetchNoClient($name, $email, $zip)
    {
        $applicant = new Applicant(["name" => $name, 'email' => $email, "address_zip" => $zip]);

        $this->assertEquals(false, $applicant->fetch());

    }

    /**
     * @dataProvider applicantProvider
     */
    public function testSaveNoClient($name, $email, $zip)
    {
        $applicant = new Applicant(["name" => $name, 'email' => $email, "address_zip" => $zip]);

        $this->assertEquals(false, $applicant->save());

    }

    /**
     * @dataProvider applicantProvider
     */
    public function testCreateNoClient($name, $email, $zip)
    {
        $applicant = new Applicant(["name" => $name, 'email' => $email, "address_zip" => $zip]);

        $this->assertEquals(false, $applicant->create());

    }

    /**
     * creates fake data
     *
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
                $faker->postcode,
            ];
        }

        return $resp;
    }

}
