<?php

use Playbook\Client;
use Playbook\Applicant;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    const PLAYBOOK_TOKEN = "31jpll6ll6qgAMI0MkOU4A4okQyW6qs2";
    const PLAYBOOK_USER = "favortest";

    protected static $fakeApplicants = [
            "Mueller.Melisa@Mraz.com" => [
                    "name" => "Freeda O'Keefe",
                    "email" => "Mueller.Melisa@Mraz.com",
                    "address" => "267 Welch Ville Suite 309",
                    "phone" => "(206)798-6233",
            ],
            "Columbus23@gmail.com" =>  [
                "name" => "Vicenta Murray",
                "email" => "Columbus23@gmail.com",
                "address" => "214 Turcotte Rue",
                "phone" => "470-519-6679x40315",
            ],
            "Trystan.Pfannerstill@Oberbrunner.com" =>  [
                "name" => "Dr. Candido Reichel",
                "email" => "Trystan.Pfannerstill@Oberbrunner.com",
                "address" => "57832 Huel Cove",
                "phone" => "01200755871",
            ],
            "Alek21@Gerlach.com" => [
                "name" => "Chaya Glover",
                "email" => "Alek21@Gerlach.com",
                "address" => "69373 Florida Junction",
                "phone" => "929-167-0619x9496",
            ],
            "tMcLaughlin@Corwin.com" => [
                "name" => "Miss Mercedes Schumm",
                "email" => "tMcLaughlin@Corwin.com",
                "address" => "4962 Luigi Road",
                "phone" => "820-352-0092",
            ],
            "qFarrell@hotmail.com" => [
                "name" => "Ryder Stokes PhD",
                "email" => "qFarrell@hotmail.com",
                "address" => "661 Wolff Turnpike",
                "phone" => "1-197-874-9978x7263",
            ]
        ];


    /**
     * Create test applicants
     * @dataProvider applicantInfoProvider
     */
    public function testAddApplicant($name, $email, $address, $phone)
    {
        $client = new Client(self::PLAYBOOK_USER, self::PLAYBOOK_TOKEN);

        $applicant = new Applicant([
            'email' => $email,
            'name'  => $name,
            'address' => $address,
            'phone' => $phone,
            'status' => 'created'
        ], $client);

        $applicant = $applicant->create();
        $this->assertInstanceOf('Playbook\Applicant', $applicant);
        $this->assertNotEmpty($applicant->id);


    }

    public function applicantInfoProvider(){
        return  self::$fakeApplicants;
    }

    /**
     * @dataProvider validEmailProvider
     */
    public function testSearchValidApplicantByEmail($email)
    {
        $client = new Client(self::PLAYBOOK_USER, self::PLAYBOOK_TOKEN);

        $params = new Applicant([
            'email' => $email
        ]);

        $applicant = $client->searchApplicants($params);
        $this->assertInstanceOf('Playbook\Applicant', $applicant, json_encode($applicant));
        $this->assertEquals(self::$fakeApplicants[$email]['name'], $applicant->name);

    }

    /**
     * @dataProvider validEmailProvider
     */

    public function testSearchValidApplicantByEmailViaModel($email)
    {
        $client = new Client(self::PLAYBOOK_USER, self::PLAYBOOK_TOKEN);

        $applicant = new Applicant([
            'email' => $email
        ],
        $client);

        $applicant = $applicant->fetch();
        $this->assertInstanceOf('Playbook\Applicant', $applicant, json_encode($applicant));
    }

    public function validEmailProvider()
    {
        return array_map(function($applicant){
            return [ $applicant['email'] ];
        }, self::$fakeApplicants);
    }

    /**
     * @expectedException Playbook\Exception\InvalidApplicantException
     * @dataProvider invalidEmailProvider
     */

    public function testSearchInvalidApplicantByEmail($email)
    {
        $client = new Client(self::PLAYBOOK_USER, self::PLAYBOOK_TOKEN);

        $params = new Applicant([
            'email' => $email
        ]);

        $client->searchApplicants($params);
    }

    public function invalidEmailProvider()
    {
        return [[null], [""], [false]];
    }

    /**
     * @expectedException Playbook\Exception\ApplicantNotFoundException
     * @dataProvider nonexistentEmailProvider
     */

    public function testSearchNonexistentApplicantByEmail($email)
    {
        $client = new Client(self::PLAYBOOK_USER, self::PLAYBOOK_TOKEN);

        $params = new Applicant([
            'email' => $email
        ]);

        $client->searchApplicants($params);
    }

    public function nonexistentEmailProvider()
    {
        return [["&"], ["*"], ["!"], ["#"]];
    }



    /**
     * @dataProvider validEmailProvider
     */
    public function testUpdateApplicant($email)
    {
        $client = new Client(self::PLAYBOOK_USER, self::PLAYBOOK_TOKEN);

        $applicant = new Applicant([
            'email' => $email
        ], $client);

        $applicant = $applicant->fetch();

        $applicant->status = "edited"; // set a new value after fetch

        $applicant = $applicant->save(); // save object with new value

        $this->assertInstanceOf('Playbook\Applicant', $applicant);
        $this->assertNotEmpty($applicant->id);
        $this->assertEquals("edited", $applicant->status);
    }

    /**
     * clean up and move users to a stage where they become archived in test env
     */
    public static function tearDownAfterClass()
    {

        $client = new Client(self::PLAYBOOK_USER, self::PLAYBOOK_TOKEN);

        foreach (self::$fakeApplicants as $v){
            $applicant = new Applicant($v, $client);
            $applicant->status = 'deleted';
            $applicant->save();
        }

    }
}
