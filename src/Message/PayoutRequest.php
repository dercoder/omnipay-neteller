<?php

namespace Omnipay\Neteller\Message;

use Guzzle\Http\Exception\BadResponseException;
use Omnipay\Common\Exception\InvalidRequestException;

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
     * @return array request data
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'account',
            'description',
            'transactionId',
            'amount',
            'currency'
        );

        $account = $this->getAccount();
        $data = array(
            'payeeProfile' => array(),
            'transaction'  => array(
                'merchantRefId' => (string) $this->getTransactionId(),
                'amount'        => (int) $this->getAmountInteger(),
                'currency'      => (string) $this->getCurrency(),
            ),
            'message'      => (string) $this->getDescription()
        );

        if (is_numeric($account)) {
            $data['payeeProfile']['accountId'] = (string) $account;
        } elseif (filter_var($account, FILTER_VALIDATE_EMAIL)) {
            $data['payeeProfile']['email'] = (string) $account;
        } else {
            throw new InvalidRequestException('The account parameter must be an email or numeric value');
        }

        return $data;
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
