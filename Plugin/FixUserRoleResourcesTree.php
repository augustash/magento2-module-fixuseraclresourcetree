<?php

namespace Augustash\FixUserAclResourceTree\Plugin;

class FixUserRoleResourcesTree
{
    /**
     * Magento thinks the array key to use is '1' but really it should be '2'
     *
     * $resources[1]  = array(
     *      [id] => Magento_Backend::all
     *      [title] => Allow everything
     *      [sortOrder] => 10
     *      [children] => array()
     * )
     *
     * @see \Magento\User\Block\Role\Tab\Edit::getTree()
     *
     * @var integer
     */
    const MAGIC_INDEX_WHERE_RESOURCES_ARE = 2;

    /**
     * Acl resource provider
     *
     * @var \Magento\Framework\Acl\AclResource\ProviderInterface
     */
    protected $aclResourceProvider;

    /**
     * @var \Magento\Integration\Helper\Data
     */
    protected $integrationData;

    /**
     * Our own custom log path
     * @var string
     */
    protected $logFilePath = '/var/log/aai_debug.log';

    public function __construct(
        \Magento\Framework\Acl\AclResource\ProviderInterface $aclResourceProvider,
        \Magento\Integration\Helper\Data $integrationData
    )
    {
        $this->aclResourceProvider = $aclResourceProvider;
        $this->integrationData = $integrationData;
    }

    /**
     * Plugin method to wrap \Magento\User\Block\Role\Tab\Edit::getTree()
     * and if the parent method returns an empty array
     * @param  MagentoUserBlockRoleTabEdit $subject [description]
     * @param  [type]                      $result  [description]
     * @return [type]                               [description]
     */
    public function afterGetTree(\Magento\User\Block\Role\Tab\Edit $subject, $result)
    {
        if (is_array($result) && !empty($result)) {
            return $result;
        }

        $rootArray = $this->integrationData->mapResources($this->getAclResources());
        return $rootArray;
    }

    /**
     * Fetch the ACL Resources and return an array of children resources
     * if they can be found at the correct array key, otherwiser return
     * an empty array just like the parent class
     * 
     * @return array
     */
    public function getAclResources()
    {
        $resources = $this->aclResourceProvider->getAclResources();

        if (isset($resources[self::MAGIC_INDEX_WHERE_RESOURCES_ARE]['children'])) {
            return $resources[self::MAGIC_INDEX_WHERE_RESOURCES_ARE]['children'];
        } else {
            // give up, you don't know where it's at now.
            return [];
        }
    }

    /**
     * Utility method to help debug. Logs are written to the $this->logFilePath
     * to avoid all the white noise in other out-of-the-box Magento logs.
     *
     * @param  string $info
     * @return void
     */
    public function log($info)
    {
        $writer = new \Zend\Log\Writer\Stream(BP . $this->logFilePath);
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info($info);
    }
}
