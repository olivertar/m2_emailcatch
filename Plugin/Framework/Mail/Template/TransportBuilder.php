<?php
namespace Orangecat\EmailCatch\Plugin\Framework\Mail\Template;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Orangecat\EmailCatch\Model\Config;

class TransportBuilder
{
    /**
     * @var \Orangecat\EmailCatch\Model\Config
     */
    private $config;

    /**
     * TransportBuilder constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Redirect to email
     *
     * @param \Magento\Framework\Mail\Template\TransportBuilder $subject
     * @param array|string $address
     * @param string $name
     * @return array
     */
    public function beforeAddTo(
        \Magento\Framework\Mail\Template\TransportBuilder $subject,
        $address,
        $name = ''
    ) : array {
        if ($address != "" || (is_array($address) && count($address) > 0)) {
            if ($this->config->isRedirectionMailEnable() && $this->config->getEmailAddressRedirect()) {
                if (!is_array($address)) {
                    $address = [$address];
                }

                return [$this->getToEmail(), sprintf('Redirected from %s', implode(',', $address))];
            }
        }

        return [$address, $name];
    }

    /**
     * Redirect Cc email
     *
     * @param \Magento\Framework\Mail\Template\TransportBuilder $subject
     * @param array|string $address
     * @param string $name
     * @return array
     */
    public function beforeAddCc(
        \Magento\Framework\Mail\Template\TransportBuilder $subject,
        $address,
        $name = ''
    ) : array {
        if ($address != "" || (is_array($address) && count($address) > 0)) {
            if ($this->config->isRedirectionMailEnable() && $this->config->getEmailAddressRedirect()) {
                if (!is_array($address)) {
                    $address = [$address];
                }
                return [$this->getToEmail(), sprintf('Redirected from %s', implode(',', $address))];
            }
        }

        return [$address, $name];
    }

    /**
     * Redirect Bcc email
     *
     * @param \Magento\Framework\Mail\Template\TransportBuilder $subject
     * @param array|string $address
     * @return string
     */
    public function beforeAddBcc(\Magento\Framework\Mail\Template\TransportBuilder $subject, $address) : string
    {
        if ($address != "" || (is_array($address) && count($address) > 0)) {
            if ($this->config->isRedirectionMailEnable() && $this->config->getEmailAddressRedirect()) {
                return $this->getToEmail()[0];
            }
        }

        return $address;
    }

    /**
     * Retrieve to email of redirection
     *
     * @return array []
     */
    protected function getToEmail()
    {
        return $this->config->getEmailAddressRedirect();
    }
}
