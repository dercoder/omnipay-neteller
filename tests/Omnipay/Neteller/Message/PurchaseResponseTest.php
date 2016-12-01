<?php
namespace Omnipay\Neteller\Message;

use Omnipay\Tests\TestCase;

class PurchaseResponseTest extends TestCase
{
    public function testFailure()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseFailure.txt');
        $data = $httpResponse->json();

        $response = new PurchaseResponse($this->getMockRequest(), $data);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isPending());
        $this->assertSame('20003', $response->getCode());
        $this->assertSame('Invalid merchant account currency', $response->getMessage());
        $this->assertNull($response->getTransactionId());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getDescription());
        $this->assertNull($response->getStatus());
    }

    public function testSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseSuccess.txt');
        $data = $httpResponse->json();

        $response = new PurchaseResponse($this->getMockRequest(), $data);

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isPending());
        $this->assertNull($response->getCode());
        $this->assertNull($response->getMessage());
        $this->assertSame('584019864a0a3', $response->getTransactionId());
        $this->assertSame('343480595848070', $response->getTransactionReference());
        $this->assertSame('netellertest_usd@neteller.com to Test', $response->getDescription());
        $this->assertSame('accepted', $response->getStatus());
    }
}
