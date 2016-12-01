<?php

namespace Omnipay\Neteller\Message;

use Exception;

/**
 * Neteller Abstract Request.
 *
 * @author    Alexander Fedra <contact@dercoder.at>
 * @copyright 2016 DerCoder
 * @license   http://opensource.org/licenses/mit-license.php MIT
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * @var string
     */
    protected $liveEndpoint = 'https://api.neteller.com';

    /**
     * @var string
     */
    protected $testEndpoint = 'https://test.api.neteller.com';

    /**
     * @var int
     */
    protected $version = 1;

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
     * @return string
     * @throws Exception
     */
    protected function getOAuth2Token()
    {
        $headers = array(
            'Content-Type'  => 'application/json',
            'Authorization' => $this->createBasicAuthorization()
        );

        $uri = $this->createUri('oauth2/token', array(
            'grant_type' => 'client_credentials'
        ));

        $response = $this->httpClient->post($uri, $headers)->send();
        $json = $response->json();

        return $json['accessToken'];
    }

    /**
     * @param string $path
     * @param array  $query
     *
     * @return string
     */
    protected function createUri($path, $query = array())
    {
        $url = sprintf('%s/v%d/%s', $this->getEndpoint(), $this->version, $path);
        if ($query) {
            $url .= '?' . http_build_query($query);
        }

        return $url;
    }

    /**
     * @return string
     */
    protected function createBasicAuthorization()
    {
        return 'Basic ' . base64_encode($this->getClientId() . ':' . $this->getClientSecret());
    }

    /**
     * @return string
     */
    protected function createBearerAuthorization()
    {
        $token = $this->getOAuth2Token();
        return 'Bearer ' . $token;
    }

    /**
     * @return string
     */
    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
