<?php
namespace Omnipay\Neteller\Message;

use Omnipay\Tests\TestCase;

class FetchTransactionResponseTest extends TestCase
{
    public function testFailure()
    {
        $httpResponse = $this->getMockHttpResponse('FetchTransactionFailure.txt');
        $data = $httpResponse->json();

        $response = new FetchTransactionResponse($this->getMockRequest(), $data);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isPending());
        $this->assertSame('5269', $response->getCode());
        $this->assertSame('Entity not found', $response->getMessage());
        $this->assertNull($response->getTransactionId());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getDescription());
        $this->assertNull($response->getStatus());
    }

    public function testSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('FetchTransactionSuccess.txt');
        $data = $httpResponse->json();

        $response = new FetchTransactionResponse($this->getMockRequest(), $data);

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isPending());
        $this->assertNull($response->getCode());
        $this->assertNull($response->getMessage());
        $this->assertSame('583ec5f62c29b', $response->getTransactionId());
        $this->assertSame('136480508920650', $response->getTransactionReference());
        $this->assertSame('netellertest_usd@neteller.com to Test', $response->getDescription());
        $this->assertSame('accepted', $response->getStatus());
    }
}
