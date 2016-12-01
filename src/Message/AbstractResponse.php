<?php

namespace Omnipay\Neteller\Message;

/**
 * Neteller Abstract Response.
 *
 * @author    Alexander Fedra <contact@dercoder.at>
 * @copyright 2016 DerCoder
 * @license   http://opensource.org/licenses/mit-license.php MIT
 */
abstract class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->getStatus() == 'accepted';
    }

    /**
     * @return bool
     */
    public function isPending()
    {
        return $this->getStatus() == 'pending';
    }

    /**
     * @return bool
     */
    public function isCancelled()
    {
        return $this->getStatus() == 'cancelled';
    }

    /**
     * @return null|int
     */
    public function getCode()
    {
        if (!isset($this->data['error']) || !isset($this->data['error']['code'])) {
            return null;
        }

        return (string) $this->data['error']['code'];
    }

    /**
     * @return null|string
     */
    public function getMessage()
    {
        if (!isset($this->data['error']) || !isset($this->data['error']['message'])) {
            return null;
        }

        return (string) $this->data['error']['message'];
    }

    /**
     * @return null|string
     */
    public function getTransactionId()
    {
        if (!isset($this->data['transaction']) || !isset($this->data['transaction']['merchantRefId'])) {
            return null;
        }

        return (string) $this->data['transaction']['merchantRefId'];
    }

    /**
     * @return null|string
     */
    public function getTransactionReference()
    {
        if (!isset($this->data['transaction']) || !isset($this->data['transaction']['id'])) {
            return null;
        }

        return (string) $this->data['transaction']['id'];
    }

    /**
     * @return null|string
     */
    public function getDescription()
    {
        if (!isset($this->data['transaction']) || !isset($this->data['transaction']['description'])) {
            return null;
        }

        return (string) $this->data['transaction']['description'];
    }

    /**
     * @return null|string
     */
    public function getStatus()
    {
        if (!isset($this->data['transaction']) || !isset($this->data['transaction']['status'])) {
            return null;
        }

        return $this->data['transaction']['status'];
    }
}
