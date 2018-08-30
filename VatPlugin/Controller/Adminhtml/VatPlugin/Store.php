<?php
/**
 * Created by PhpStorm.
 * User: rahul.makwana61@gmail.com
 * Date: 31/7/18
 * Time: 1:07 PM
 *  PHP Version 7
 *
 * @category Vat_Discount
 * @package  Rahul\VatPlugin\Block
 * @author   Rahul Makwana <rahul.makwana61@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     rahul.makwana61@gmail.com
 */

namespace Rahul\VatPlugin\Controller\Adminhtml\VatPlugin;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;
use \Magento\Framework\Controller\Result\JsonFactory;
use Rahul\VatPlugin\Block\VatPlugin;

/**
 * Class Store
 *
 * @category Vat_Discount
 * @package  Rahul\VatPlugin\Controller\Adminhtml\VatPlugin
 * @author   Rahul Makwana <rahul.makwana61@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     rahul.makwana61@gmail.com
 */
class Store extends Action
{
    /**
     * This variable will be used to inject PageFactory.
     *
     * @var PageFactory
     */
    public $resultPageFactory;

    /**
     * This variable will be used to inject JsonFactory.
     *
     * @var JsonFactory
     */
    public $resultJsonFactory;

    /**
     * This variable will be used to inject VatPlugin.
     *
     * @var VatPlugin
     */
    public $discountplugin;

    /**
     * Store constructor.
     *
     * @param Context     $context           context injection
     * @param PageFactory $resultPageFactory resultpage factory injection
     * @param JsonFactory $resultJsonFactory json factory injection
     * @param VatPlugin   $discountPlugin    Vatplugin injection
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        VatPlugin $discountPlugin
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->discountplugin = $discountPlugin;
    }
    /**
     * This function will be used to store the vat plugin settings.
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();
        $settings = $this->discountplugin;
        $result = $this->resultJsonFactory->create();
        $checkCredentials = $settings->vatValidator(
            $post['apikey'], $post['endpoint'], 'GB198332378'
        );
        if ($checkCredentials['status'] == 200 || $checkCredentials['status']==404) {
            $settings->savesettings($post['apikey'], $post['endpoint']);
            return $result->setData(['success' => true]);
        } else {
            return $result->setData(
                [
                    'success' => false, 'message' => $checkCredentials['message']
                ]
            );
        }
    }
}
