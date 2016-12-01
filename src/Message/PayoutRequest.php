<?php

namespace Omnipay\Neteller\Message;

use Guzzle\Http\Exception\BadResponseException;

/**
 * Neteller Payout Request.
 *
 * @author    Alexander Fedra <contact@dercoder.at>
 * @copyright 2016 DerCoder
 * @license   http://opensource.org/licenses/mit-license.php MIT
 */
class PayoutRequest extends AbstractRequest
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
     * @return array request data
     */
    public function getData()
    {
        $this->validate(
            'email',
            'description',
            'transactionId',
            'amount',
            'currency'
        );

        return array(
            'payeeProfile' => array(
                'email' => $this->getEmail()
            ),
            'transaction'  => array(
                'merchantRefId' => $this->getTransactionId(),
                'amount'        => $this->getAmountInteger(),
                'currency'      => $this->getCurrency(),
            ),
            'message'      => $this->getDescription()
        );
    }

    /**
     * @param array $data
     *
     * @return PayoutResponse
     */
    public function sendData($data)
    {
        $headers = array(
            'Content-Type'  => 'application/json',
            'Authorization' => $this->createBearerAuthorization()
        );

        $uri = $this->createUri('transferOut');

        try {
            $response = $this->httpClient->post($uri, $headers, json_encode($data))->send();
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
        }

        return new PayoutResponse($this, $response->json());
    }
}
