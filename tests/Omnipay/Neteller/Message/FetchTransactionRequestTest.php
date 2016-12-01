<?php
namespace Omnipay\Neteller\Message;

use Omnipay\Tests\TestCase;

class FetchTransactionRequestTest extends TestCase
{
    /**
     * @var FetchTransactionRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();
        $this->request = new FetchTransactionRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'transactionId' => 4567890
        ));
    }

    public function testGetData()
    {
        $this->request->initialize(array(
            'transactionId' => 4567890
        ));

        $data = $this->request->getData();
        $this->assertSame('4567890', $data['id']);
        $this->assertSame('merchantRefId', $data['refType']);

        $this->request->initialize(array(
            'transactionReference' => 4567890
        ));

        $data = $this->request->getData();
        $this->assertSame('4567890', $data['id']);
        $this->assertArrayNotHasKey('refType', $data);

        $this->request->initialize(array());
        $this->setExpectedException('Omnipay\Common\Exception\InvalidRequestException', 'The transactionId or transactionReference parameter is required');
        $this->request->getData();
    }

    public function testSendDataSuccess()
    {
        $this->setMockHttpResponse(array(
            'OAuth2TokenSuccess.txt',
            'FetchTransactionSuccess.txt'
        ));

        $data = $this->request->getData();
        $response = $this->request->sendData($data);
        $this->assertSame('Omnipay\Neteller\Message\FetchTransactionResponse', get_class($response));
    }

    public function testSendDataFailure()
    {
        $this->setMockHttpResponse(array(
            'OAuth2TokenSuccess.txt',
            'FetchTransactionFailure.txt'
        ));

        $data = $this->request->getData();
        $response = $this->request->sendData($data);
        $this->assertSame('Omnipay\Neteller\Message\FetchTransactionResponse', get_class($response));
    }
}
