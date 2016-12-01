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
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
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
            'email',
            'verificationCode',
            'transactionId',
            'amount',
            'currency'
        );

        return array(
            'paymentMethod'    => array(
                'type'  => 'neteller',
                'value' => $this->getEmail()
            ),
            'transaction'      => array(
                'merchantRefId' => $this->getTransactionId(),
                'amount'        => $this->getAmountInteger(),
                'currency'      => $this->getCurrency(),
            ),
            'verificationCode' => $this->getVerificationCode()
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
