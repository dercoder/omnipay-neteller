<?php
namespace Omnipay\Neteller;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /**
     * @var Gateway
     */
    public $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setClientId('BBCBWLT2e1u0xopg');
        $this->gateway->setClientSecret('0.P6f4aetfmSE-Me1V7n46-2iTHj7Q7hkcXyr7QUhfJxc.OgmZct8zXndFJnGDqrRTZmiUAyI');
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array(
            'account'       => 'netellertest_USD@neteller.com',
            'transactionId' => 'TX9997888',
            'amount'        => '14.65',
            'currency'      => 'USD'
        ));

        $this->assertSame('netellertest_USD@neteller.com', $request->getAccount());
        $this->assertSame('TX9997888', $request->getTransactionId());
        $this->assertSame('14.65', $request->getAmount());
        $this->assertSame('USD', $request->getCurrency());
    }

    public function testPayout()
    {
        $request = $this->gateway->payout(array(
            'account'       => 'netellertest_USD@neteller.com',
            'transactionId' => 'TX8889777',
            'amount'        => '12.43',
            'currency'      => 'USD'
        ));

        $this->assertSame('netellertest_USD@neteller.com', $request->getAccount());
        $this->assertSame('TX8889777', $request->getTransactionId());
        $this->assertSame('12.43', $request->getAmount());
        $this->assertSame('USD', $request->getCurrency());
    }

    public function testFetchTransaction()
    {
        $request = $this->gateway->fetchTransaction(array(
            'transactionId'        => 'TX5557666',
            'transactionReference' => 'XXAACCD3231232'
        ));

        $this->assertSame('TX5557666', $request->getTransactionId());
        $this->assertSame('XXAACCD3231232', $request->getTransactionReference());
    }
}
