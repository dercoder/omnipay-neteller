<?php

namespace Omnipay\Neteller;

use Omnipay\Common\AbstractGateway;
use Omnipay\Neteller\Message\PurchaseRequest;
use Omnipay\Neteller\Message\PayoutRequest;
use Omnipay\Neteller\Message\FetchTransactionRequest;

class Gateway extends AbstractGateway
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Neteller';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultParameters()
    {
        return array(
            'testMode'     => false,
            'clientId'     => '',
            'clientSecret' => ''
        );
    }

    /**
     * Get Neteller client ID.
     *
     * @return string clientId
     */
    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    /**
     * Set Neteller client ID.
     *
     * @param string $value clientId
     *
     * @return self
     */
    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }

    /**
     * Get Neteller client secret.
     *
     * @return string clientSecret
     */
    public function getClientSecret()
    {
        return $this->getParameter('clientSecret');
    }

    /**
     * Set Neteller client secret.
     *
     * @param string $value clientSecret
     *
     * @return self
     */
    public function setClientSecret($value)
    {
        return $this->setParameter('clientSecret', $value);
    }

    /**
     * @param array $options
     *
     * @return PurchaseRequest
     */
    public function purchase(array $options = array())
    {
        return $this->createRequest('\Omnipay\Neteller\Message\PurchaseRequest', $options);
    }

    /**
     * @param array $options
     *
     * @return PayoutRequest
     */
    public function payout(array $options = array())
    {
        return $this->createRequest('\Omnipay\Neteller\Message\PayoutRequest', $options);
    }

    /**
     * @param array $options
     *
     * @return FetchTransactionRequest
     */
    public function fetchTransaction(array $options = array())
    {
        return $this->createRequest('\Omnipay\Neteller\Message\FetchTransactionRequest', $options);
    }
}
