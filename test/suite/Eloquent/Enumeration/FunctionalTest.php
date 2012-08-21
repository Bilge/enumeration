<?php

/*
 * This file is part of the Enumeration package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class FunctionalTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test basic setup of HTTPRequestMethod class.
     */
    public function testHTTPRequestMethodSetup()
    {
        $expected = array(
            'OPTIONS' => HTTPRequestMethod::OPTIONS(),
            'GET' => HTTPRequestMethod::GET(),
            'HEAD' => HTTPRequestMethod::HEAD(),
            'POST' => HTTPRequestMethod::POST(),
            'PUT' => HTTPRequestMethod::PUT(),
            'DELETE' => HTTPRequestMethod::DELETE(),
            'TRACE' => HTTPRequestMethod::TRACE(),
            'CONNECT' => HTTPRequestMethod::CONNECT(),
        );

        $this->assertSame($expected, HTTPRequestMethod::multitonInstances());
    }

    /**
     * Test the following statement:
     *
     * "[The HTTPRequestMethod class] can now be used in a type hint to easily
     *  accept any valid HTTP request method."
     */
    public function testHTTPRequestMethodAcceptAll()
    {
        $passedMethods = array();
        $handleHttpRequest = function(HTTPRequestMethod $method, $url, $body) use(&$passedMethods) {
            $passedMethods[] = $method;
        };
        $handleHttpRequest(HTTPRequestMethod::OPTIONS(), 'http://example.org/', NULL);
        $handleHttpRequest(HTTPRequestMethod::GET(), 'http://example.org/', NULL);
        $handleHttpRequest(HTTPRequestMethod::HEAD(), 'http://example.org/', NULL);
        $handleHttpRequest(HTTPRequestMethod::POST(), 'http://example.org/', 'foo');
        $handleHttpRequest(HTTPRequestMethod::PUT(), 'http://example.org/', 'foo');
        $handleHttpRequest(HTTPRequestMethod::DELETE(), 'http://example.org/', NULL);
        $handleHttpRequest(HTTPRequestMethod::TRACE(), 'http://example.org/', NULL);
        $handleHttpRequest(HTTPRequestMethod::CONNECT(), 'http://example.org/', NULL);
        $expected = array(
            HTTPRequestMethod::OPTIONS(),
            HTTPRequestMethod::GET(),
            HTTPRequestMethod::HEAD(),
            HTTPRequestMethod::POST(),
            HTTPRequestMethod::PUT(),
            HTTPRequestMethod::DELETE(),
            HTTPRequestMethod::TRACE(),
            HTTPRequestMethod::CONNECT(),
        );

        $this->assertSame($expected, $passedMethods);
    }

    /**
     * Test the following statement:
     *
     * "[The fact that enumeration members are singleton instances] means that
     *  strict comparison (===) can be used to determine which member has been
     *  passed to a function"
     */
    public function testHTTPRequestMethodStrictComparison() {
        $get = HTTPRequestMethod::GET();
        $post = HTTPRequestMethod::POST();

        $this->assertTrue($get === HTTPRequestMethod::GET());
        $this->assertTrue($post === HTTPRequestMethod::POST());
        $this->assertFalse($get === HTTPRequestMethod::POST());
        $this->assertFalse($post === HTTPRequestMethod::GET());
        $this->assertFalse($get === $post);
    }

    /**
     * Test basic setup of Planet class.
     */
    public function testPlanetSetup()
    {
        $expected = array(
            'MERCURY' => Planet::MERCURY(),
            'VENUS' => Planet::VENUS(),
            'EARTH' => Planet::EARTH(),
            'MARS' => Planet::MARS(),
            'JUPITER' => Planet::JUPITER(),
            'SATURN' => Planet::SATURN(),
            'URANUS' => Planet::URANUS(),
            'NEPTUNE' => Planet::NEPTUNE(),
        );

        $this->assertSame($expected, Planet::multitonInstances());
    }

    /**
     * Test output from the example script for the Planet class.
     */
    public function testPlanetExampleScriptOutput()
    {
        ob_start();
        $earthWeight = 175;
        $mass = $earthWeight / Planet::EARTH()->surfaceGravity();

        foreach (Planet::multitonInstances() as $planet) {
            // modified slightly to avoid floating point precision issues causing failing test
            echo sprintf('Your weight on %s is %0.0f' . PHP_EOL, $planet, $planet->surfaceWeight($mass));
        }
        $actual = ob_get_clean();
        $expected =
            'Your weight on MERCURY is 66' . PHP_EOL
            . 'Your weight on VENUS is 158' . PHP_EOL
            . 'Your weight on EARTH is 175' . PHP_EOL
            . 'Your weight on MARS is 66' . PHP_EOL
            . 'Your weight on JUPITER is 443' . PHP_EOL
            . 'Your weight on SATURN is 187' . PHP_EOL
            . 'Your weight on URANUS is 158' . PHP_EOL
            . 'Your weight on NEPTUNE is 199' . PHP_EOL
        ;

        $this->assertSame($expected, $actual);
    }
}
