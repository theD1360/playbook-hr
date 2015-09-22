<?php

use Faker\Factory;
use Favor\Playbook\Applicant;
use \Favor\Playbook\ExtendedApplicant;

class ApplicantTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider applicantProvider
     */
    public function testConstructor($name, $email, $zip)
    {

        $applicant = new Applicant(["name" => $name, 'email' => $email, "address_zip" => $zip], $this->getClient());

        $this->assertEquals($email, $applicant->email);
        $this->assertEquals($name, $applicant->name);
        $this->assertEquals($zip, $applicant->address_zip);
    }

    /**
     * @dataProvider applicantProvider
     */
    public function testGetters($name, $email, $zip)
    {
        $applicant = new Applicant(["name" => $name, 'email' => $email, "address_zip" => $zip], $this->getClient());

        $this->assertEquals($email, $applicant->getEmail());
        $this->assertEquals($name, $applicant->getName());
        $this->assertEquals($zip, $applicant->getAddressZip());

    }

    /**
     * @dataProvider applicantProvider
     */
    public function testSetters($name, $email, $zip)
    {

        $applicant = new Applicant([], $this->getClient());

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
        $applicant = new Applicant(["name" => $name, 'email' => $email, "address_zip" => $zip], $this->getClient());

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
        $applicant = new Applicant(["name" => $name, 'email' => $email, "address_zip" => $zip], $this->getClient());
        $newApplicant = $applicant->getNewInstance();

        $this->assertInstanceOf('Favor\Playbook\Applicant', $newApplicant);
        $this->assertEquals($name, $newApplicant->name);
        $this->assertEquals($email, $newApplicant->email);
        $this->assertEquals($zip, $newApplicant->address_zip);

    }

    /**
     *
     */
    public function testAccessorMutator()
    {
        $applicant = new ExtendedApplicant([], $this->getClient());

        $applicant->always_upper = "get as upper";
        $this->assertEquals("GET AS UPPER", $applicant->always_upper);

        $applicant->always_lower = "SET AS LOWER";
        $this->assertEquals("set as lower", $applicant->always_lower);
    }

    /**
     * @expectedException Favor\Playbook\Exception\MethodNotFoundException
     */
    public function testMethodNotFound()
    {
        $applicant = new ExtendedApplicant([], $this->getClient());

        $applicant->thisMethodDoesntExist();
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

    private function getClient()
    {
        return new \Favor\Playbook\Client(ClientTest::PLAYBOOK_USER, ClientTest::PLAYBOOK_TOKEN);
    }

}
