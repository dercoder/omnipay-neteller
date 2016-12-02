<?php
namespace Omnipay\Neteller\Message;

use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'account'          => 'netellertest_USD@neteller.com',
            'verificationCode' => 270955,
            'transactionId'    => 4567890,
            'amount'           => 12.34,
            'currency'         => 'USD'
        ));
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertSame('neteller', $data['paymentMethod']['type']);
        $this->assertSame('netellertest_USD@neteller.com', $data['paymentMethod']['value']);
        $this->assertSame('4567890', $data['transaction']['merchantRefId']);
        $this->assertSame(1234, $data['transaction']['amount']);
        $this->assertSame('USD', $data['transaction']['currency']);
        $this->assertSame('270955', $data['verificationCode']);
    }

    public function testSendDataSuccess()
    {
        $this->setMockHttpResponse(array(
            'OAuth2TokenSuccess.txt',
            'PurchaseSuccess.txt'
        ));

        $data = $this->request->getData();
        $response = $this->request->sendData($data);
        $this->assertSame('Omnipay\Neteller\Message\PurchaseResponse', get_class($response));
    }

    public function testSendDataFailure()
    {
        $this->setMockHttpResponse(array(
            'OAuth2TokenSuccess.txt',
            'PurchaseFailure.txt'
        ));

        $data = $this->request->getData();
        $response = $this->request->sendData($data);
        $this->assertSame('Omnipay\Neteller\Message\PurchaseResponse', get_class($response));
    }
}
