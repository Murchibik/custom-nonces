<?php

use CustomNonces\CustomNonce;

class NonceTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testCreate() {
        $this->tester->expectException("Exception", function () {
            new CustomNonce("");
        });
    }

    public function testCreateNonce() {
        $example = new CustomNonce("frtie3456fder3243344fdve2345923434");

        $string = "createcomment_587";
        $this->tester->assertInternalType("string",$example->createNonce($string));

        $this->expectException("Exception");
        $example->setCustomLabel("");
        $this->expectException("Exception");
        $example->setCustomLabel(" ");
        $this->tester->assertRegexp("/^custom-nonce%3D.*/",$example->createNonceForUrl($string));

        $customLabel = "custom-label";
        $result = $example->setCustomLabel($customLabel);
        $this->tester->assertTrue($result);

        $this->tester->assertNotRegexp("/^custom-nonce%3D.*/",$example->createNonceForUrl($string));
        $this->tester->assertRegexp("/^custom-label%3D.*/",$example->createNonceForUrl($string));
    }

    public function testCheckNonce() {
        $example = new CustomNonce("frtie3456fder3243344fdve2345923434");
        $string = "createcomment_587";

        $nonce = $example->createNonce($string);
        $result = $example->checkNonce($string, $nonce);
        $this->tester->assertInternalType("boolean", $result);
        $this->tester->assertTrue($result);

        $nonce = "foo";
        $result = $example->checkNonce($string, $nonce);
        $this->tester->assertInternalType("boolean", $result);
        $this->tester->assertFalse($result);
    }
}