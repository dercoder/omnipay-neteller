<?php
namespace Omnipay\Neteller\Message;

use Omnipay\Tests\TestCase;

class PayoutRequestTest extends TestCase
{
    /**
     * @var PayoutRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();
        $this->request = new PayoutRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'account'          => 'netellertest_USD@neteller.com',
            'verificationCode' => 270955,
            'transactionId'    => 4567890,
            'description'      => 'Free Text Description',
            'amount'           => 12.34,
            'currency'         => 'USD'
        ));
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertSame('netellertest_USD@neteller.com', $data['payeeProfile']['email']);
        $this->assertSame('4567890', $data['transaction']['merchantRefId']);
        $this->assertSame(1234, $data['transaction']['amount']);
        $this->assertSame('USD', $data['transaction']['currency']);
        $this->assertSame('Free Text Description', $data['message']);

        $this->request->setAccount(454651018446);
        $data = $this->request->getData();
        $this->assertSame('454651018446', $data['payeeProfile']['accountId']);

        $this->request->setAccount('SomeInvalidAccount');
        $this->setExpectedException('Omnipay\Common\Exception\InvalidRequestException', 'The account parameter must be an email or numeric value');
        $this->request->getData();
    }

    public function testSendDataSuccess()
    {
        $this->setMockHttpResponse(array(
            'OAuth2TokenSuccess.txt',
            'PayoutSuccess.txt'
        ));

        $data = $this->request->getData();
        $response = $this->request->sendData($data);
        $this->assertSame('Omnipay\Neteller\Message\PayoutResponse', get_class($response));
    }

    public function testSendDataFailure()
    {
        $this->setMockHttpResponse(array(
            'OAuth2TokenSuccess.txt',
            'PayoutFailure.txt'
        ));

        $data = $this->request->getData();
        $response = $this->request->sendData($data);
        $this->assertSame('Omnipay\Neteller\Message\PayoutResponse', get_class($response));
    }
}
