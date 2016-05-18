<?php

/*
 * This file is part of the Ivory Base64 File package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Base64FileBundle\Tests\Model;

use Ivory\Base64FileBundle\Model\Base64File;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class Base64FileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var resource
     */
    private static $base64;

    /**
     * @var resource
     */
    private static $binary;

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        self::$base64 = fopen(__DIR__.'/../Fixtures/Model/base64', 'rb');
        self::$binary = fopen(__DIR__.'/../Fixtures/Model/binary', 'rb');
    }

    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterClass()
    {
        if (is_resource(self::$base64)) {
            fclose(self::$base64);
        }

        if (is_resource(self::$binary)) {
            fclose(self::$binary);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        rewind(self::$base64);
        rewind(self::$binary);
    }

    public function testInheritance()
    {
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\File\File', new Base64File(self::$base64));
    }

    public function testFromBase64ResourceToBase64Resource()
    {
        $file = new Base64File(self::$base64);

        $this->assertSame($this->getBase64String(), $this->getStreamContent($file->getData()));
    }

    public function testFromBase64ResourceToBase64String()
    {
        $file = new Base64File(self::$base64);

        $this->assertSame($this->getBase64String(), $file->getData(true, false));
    }

    public function testFromBase64ResourceToBinaryResource()
    {
        $file = new Base64File(self::$base64);

        $this->assertSame($this->getBinaryString(), $this->getStreamContent($file->getData(false)));
    }

    public function testFromBase64ResourceToBinaryString()
    {
        $file = new Base64File(self::$base64);

        $this->assertSame($this->getBinaryString(), $file->getData(false, false));
    }

    public function testFromBase64StringToBase64Resource()
    {
        $file = new Base64File($original = $this->getBase64String());

        $this->assertSame($original, $this->getStreamContent($file->getData()));
    }

    public function testFromBase64StringToBase64String()
    {
        $file = new Base64File($original = $this->getBase64String());

        $this->assertSame($original, $file->getData(true, false));
    }

    public function testFromBase64StringToBinaryResource()
    {
        $file = new Base64File($this->getBase64String());

        $this->assertSame($this->getBinaryString(), $this->getStreamContent($file->getData(false)));
    }

    public function testFromBase64StringToBinaryString()
    {
        $file = new Base64File($this->getBase64String());

        $this->assertSame($this->getBinaryString(), $file->getData(false, false));
    }

    public function testFromBinaryResourceToBase64Resource()
    {
        $file = new Base64File(self::$binary, false);

        $this->assertSame($this->getBase64String(), $this->getStreamContent($file->getData()));
    }

    public function testFromBinaryResourceToBase64String()
    {
        $file = new Base64File(self::$binary, false);

        $this->assertSame($this->getBase64String(), $file->getData(true, false));
    }

    public function testFromBinaryResourceToBinaryResource()
    {
        $file = new Base64File(self::$binary, false);

        $this->assertSame($this->getBinaryString(), $this->getStreamContent($file->getData(false)));
    }

    public function testFromBinaryResourceToBinaryString()
    {
        $file = new Base64File(self::$binary, false);

        $this->assertSame($this->getBinaryString(), $file->getData(false, false));
    }

    public function testFromBinaryStringToBase64Resource()
    {
        $file = new Base64File($this->getBinaryString(), false);

        $this->assertSame($this->getBase64String(), $this->getStreamContent($file->getData()));
    }

    public function testFromBinaryStringToBase64String()
    {
        $file = new Base64File($this->getBinaryString(), false);

        $this->assertSame($this->getBase64String(), $file->getData(true, false));
    }

    public function testFromBinaryStringToBinaryResource()
    {
        $file = new Base64File($original = $this->getBinaryString(), false);

        $this->assertSame($original, $this->getStreamContent($file->getData(false)));
    }

    public function testFromBinaryStringToBinaryString()
    {
        $file = new Base64File($original = $this->getBinaryString(), false);

        $this->assertSame($original, $file->getData(false, false));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The base64 file value must be a string or a resource, got "boolean".
     */
    public function testInvalidTypeValue()
    {
        new Base64File(true);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage An error occurred while copying the value (stream_copy_to_stream(): stream filter (convert.base64-decode): invalid byte sequence).
     */
    public function testInvalidBase64ResourceValue()
    {
        new Base64File(self::$binary);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage An error occurred while copying the value (fwrite(): stream filter (convert.base64-decode): invalid byte sequence).
     */
    public function testInvalidBase64StringValue()
    {
        new Base64File(stream_get_contents(self::$binary));
    }

    /**
     * @return string
     */
    private function getBase64String()
    {
        return $this->getStreamContent(self::$base64);
    }

    /**
     * @return string
     */
    private function getBinaryString()
    {
        return $this->getStreamContent(self::$binary);
    }

    /**
     * @param resource $resource
     *
     * @return string
     */
    private function getStreamContent($resource)
    {
        if (!is_resource($resource)) {
            $this->fail();
        }

        return stream_get_contents($resource);
    }
}
