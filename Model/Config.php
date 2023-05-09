<?php

namespace Orangecat\EmailCatch\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;

class Config
{
    /**
     * Constants
     */
    private const XML_PATH_DEBUG = 'emailcatch/debug/';
    private const XML_PATH_LOG = 'emailcatch/log/';

    /**
     * @var \Orangecat\EmailCatch\Helper\Config
     */
    private $config;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * Constructor
     *
     * @param \Orangecat\EmailCatch\Helper\Config            $config
     * @param \Magento\Framework\App\RequestInterface       $request
     * @param \Magento\Store\Model\StoreManagerInterface    $storeManager
     */
    public function __construct(
        \Orangecat\EmailCatch\Helper\Config $config,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->config = $config;
        $this->request = $request;
        $this->storeManager = $storeManager;
    }

    /**
     * Get Current Store Id
     *
     * @return int|mixed
     */
    public function getCurrentStoreId()
    {
        $storeId = 0;
        $requestStore = $this->request->getParam(ScopeInterface::SCOPE_STORE);
        $requestWebsite = $this->request->getParam(ScopeInterface::SCOPE_WEBSITE);

        try {
            if ($requestStore) {
                $storeId = $requestStore;
            } elseif ($requestWebsite) {
                $storeId = $this->storeManager->getWebsite($requestWebsite)->getDefaultStore()->getId();
            } else {
                $storeId = $this->storeManager->getStore()->getId();
            }
        } catch (LocalizedException $exception) {
        } catch (NoSuchEntityException $e) {
        }

        return $storeId;
    }

    /**
     Is Log Enabled
     *
     * @return bool
     */
    public function isLogEnabled()
    {
        return (bool)$this->config->getModuleConfig(
            self::XML_PATH_LOG . 'enable',
            $this->getCurrentStoreId()
        );
    }

    /**
     * Is Redirection Mail Enable
     *
     * @return bool
     */
    public function isRedirectionMailEnable()
    {
        return  (bool)$this->config->getModuleConfig(
            self::XML_PATH_DEBUG . 'enable',
            $this->getCurrentStoreId()
        );
    }

    /**
     * Get Email Address Redirect
     *
     * @return string|bool
     */
    public function getEmailAddressRedirect()
    {
        $redirect_email_addresses = $this->config->getModuleConfig(
            self::XML_PATH_DEBUG . 'redirect_email_addresses',
            $this->getCurrentStoreId()
        );

        if (!empty(trim($redirect_email_addresses))) {
            return $redirect_email_addresses;
        }

        return false;
    }

    /**
     * Clean Log Days
     *
     * @return int
     */
    public function cleanLogDays()
    {
        return  (int)$this->config->getModuleConfig(
            self::XML_PATH_LOG . 'log_clean',
            $this->getCurrentStoreId()
        );
    }

    /**
     * Get Address List
     *
     * @param array $emailsData
     * @return \Zend\Mail\AddressList
     */
    public function getAddressList($emailsData)
    {
        $addressList = new \Zend\Mail\AddressList();

        foreach ($emailsData as $data) {
            $addressList->add($data['email'], isset($data['name']) ? $data['name'] : null);
        }

        return $addressList;
    }
}
