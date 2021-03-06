<?php
use Headzoo\Web\Tools\Utils;

class UtilsTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Headzoo\Web\Tools\Utils::normalizeHeaderName
     * @dataProvider providerNormalizeHeaderName
     */
    public function testNormalizeHeaderName($header, $stripX, $expected)
    {
        $this->assertEquals(
            $expected,
            Utils::normalizeHeaderName($header, $stripX)
        );
    }

    /**
     * @covers Headzoo\Web\Tools\Utils::normalizeHeaderName
     * @dataProvider providerNormalizeHeaderName_InvalidArgument
     * @expectedException Headzoo\Web\Tools\Exceptions\InvalidArgumentException
     */
    public function testNormalizeHeaderName_InvalidArgument($header)
    {
        Utils::normalizeHeaderName($header);
    }

    /**
     * Data provider for test testNormalizeHeaderName
     * 
     * @return array
     */
    public function providerNormalizeHeaderName()
    {
        return [
            ["content-type",    false,  "Content-Type"],
            ["content_type",    false,  "Content-Type"],
            ["CONTENT-TYPE",    false,  "Content-Type"],
            ["CONTENT_TYPE",    false,  "Content-Type"],
            ["content type",    false,  "Content-Type"],
            ["cOnTent-TyPe",    false,  "Content-Type"],
            ["Content-Type",    false,  "Content-Type"],
            [" Content-Type ",  false,  "Content-Type"],
            ["Content-Type:",   false,  "Content-Type"],
            ["Content-Type:  ", false,  "Content-Type"],
            ["Content-Type : ", false,  "Content-Type"],
            ["Content-MD5",     false,  "Content-MD5"],
            ["xss-protection",  false,  "XSS-Protection"],
            ["x-forwarded-for", false,  "X-Forwarded-For"],
            ["x-forwarded-for", true,   "Forwarded-For"],
            ["x-att-deviceid",  false,  "X-ATT-DeviceId"],
            ["x-att-deviceid",  true,   "ATT-DeviceId"],
            ["att-deviceid",    false,  "ATT-DeviceId"],
            
            [
                ["content-type", "xss-protection"],
                false,
                ["Content-Type", "XSS-Protection"]
            ],
            [
                ["content-type", "x-ua-compatible"],
                false,
                ["Content-Type", "X-UA-Compatible"]
            ],
            [
                ["CONTENT_TYPE", "x-ua-compatible"],
                true,
                ["Content-Type", "UA-Compatible"]
            ]
        ];
    }

    /**
     * Data provider for test testNormalizeHeaderName_InvalidArgument
     *
     * @return array
     */
    public function providerNormalizeHeaderName_InvalidArgument()
    {
        return [
            ["Content-Type: text/html"],
            ["Content^Type"]
        ];
    }

    /**
     * @covers Headzoo\Web\Tools\Utils::appendUrlQuery
     */
    public function testAppendUrlQuery()
    {
        $this->assertEquals(
            "http://site.com?name=Sean&job=programmer",
            Utils::appendUrlQuery("http://site.com", "name=Sean&job=programmer")
        );
        $this->assertEquals(
            "http://site.com?action=list&name=Sean&job=programmer",
            Utils::appendUrlQuery("http://site.com?action=list", "name=Sean&job=programmer")
        );
        $this->assertEquals(
            "http://site.com?action=list&name=Sean&job=programmer",
            Utils::appendUrlQuery("http://site.com?action=list", "?name=Sean&job=programmer")
        );
    }
}
