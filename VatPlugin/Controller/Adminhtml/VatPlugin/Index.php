<?php
/**
 * Created by PhpStorm.
 * User: rahul.makwana61@gmail.com
 * Date: 31/7/18
 * Time: 1:07 PM
 *  PHP Version 7
 *
 * @category Vat_Discount
 * @package  Rahul\VatPlugin\Controller\Adminhtml\VatPlugin
 * @author   Rahul Makwana <rahul.makwana61@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     rahul.makwana61@gmail.com
 */

namespace Rahul\VatPlugin\Controller\Adminhtml\VatPlugin;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 *
 * @category Vat_Discount
 * @package  Rahul\VatPlugin\Controller\Adminhtml\VatPlugin
 * @author   Rahul Makwana <rahul.makwana61@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     rahul.makwana61@gmail.com
 */
class Index extends Action
{
    /**
     * This variable will be used to inject PageFactory.
     *
     * @var PageFactory
     */
    public $resultPageFactory;

    /**
     * Index constructor.
     *
     * @param Context     $context           context injection
     * @param PageFactory $resultPageFactory pagefactory injection
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * This function will get executed on call of index
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        return $resultPage = $this->resultPageFactory->create();
    }
}
