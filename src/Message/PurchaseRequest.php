<?php

namespace Omnipay\Neteller\Message;

use Guzzle\Http\Exception\BadResponseException;

/**
 * Neteller Purchase Request.
 *
 * @author    Alexander Fedra <contact@dercoder.at>
 * @copyright 2016 DerCoder
 * @license   http://opensource.org/licenses/mit-license.php MIT
 */
class PurchaseRequest extends AbstractRequest
{
    /**
     * @return string|null
     */
    public function getAccount()
    {
        return $this->getParameter('account');
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function setAccount($value)
    {
        return $this->setParameter('account', $value);
    }

    /**
     * @return string|null
     */
    public function getVerificationCode()
    {
        return $this->getParameter('verificationCode');
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function setVerificationCode($value)
    {
        return $this->setParameter('verificationCode', $value);
    }

    /**
     * @return array request data
     */
    public function getData()
    {
        $this->validate(
            'account',
            'verificationCode',
            'transactionId',
            'amount',
            'currency'
        );

        return array(
            'paymentMethod'    => array(
                'type'  => 'neteller',
                'value' => (string) $this->getAccount()
            ),
            'transaction'      => array(
                'merchantRefId' => (string) $this->getTransactionId(),
                'amount'        => (int) $this->getAmountInteger(),
                'currency'      => (string) $this->getCurrency(),
            ),
            'verificationCode' => (string) $this->getVerificationCode()
        );
    }

    /**
     * @param array $data
     *
     * @return PurchaseResponse
     */
    public function sendData($data)
    {
        $headers = array(
            'Content-Type'  => 'application/json',
            'Authorization' => $this->createBearerAuthorization()
        );

        $uri = $this->createUri('transferIn');

        try {
            $response = $this->httpClient->post($uri, $headers, json_encode($data))->send();
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
        }

        return new PurchaseResponse($this, $response->json());
    }
}
