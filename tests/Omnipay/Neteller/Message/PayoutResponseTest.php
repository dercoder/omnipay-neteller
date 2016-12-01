<?php
namespace Omnipay\Neteller\Message;

use Omnipay\Tests\TestCase;

class PayoutResponseTest extends TestCase
{
    public function testFailure()
    {
        $httpResponse = $this->getMockHttpResponse('PayoutFailure.txt');
        $data = $httpResponse->json();

        $response = new PayoutResponse($this->getMockRequest(), $data);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isPending());
        $this->assertSame('20020', $response->getCode());
        $this->assertSame('Insufficient balance', $response->getMessage());
        $this->assertNull($response->getTransactionId());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getDescription());
        $this->assertNull($response->getStatus());
    }

    public function testSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PayoutSuccess.txt');
        $data = $httpResponse->json();

        $response = new PayoutResponse($this->getMockRequest(), $data);

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isPending());
        $this->assertNull($response->getCode());
        $this->assertNull($response->getMessage());
        $this->assertSame('58401c529be53', $response->getTransactionId());
        $this->assertSame('204480596564510', $response->getTransactionReference());
        $this->assertSame('Test to netellertest_usd@neteller.com', $response->getDescription());
        $this->assertSame('accepted', $response->getStatus());
    }
}
