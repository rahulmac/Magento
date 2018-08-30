<?php
/**
 * Created by PhpStorm.
 * User: rahul.makwana61@gmail.com
 * Date: 13/8/18
 * Time: 2:28 PM
 *  PHP Version 7
 *
 * @category Vat_Discount
 * @package  Rahul\VatPlugin\Controller\Index
 * @author   Rahul Makwana <rahul.makwana61@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     rahul.makwana61@gmail.com
 */

namespace Rahul\VatPlugin\Controller\Index;

use Rahul\VatPlugin\Block\VatPlugin;
use \Magento\Framework\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;
use \Magento\Checkout\Model\Session;
use \Magento\Framework\Controller\Result\JsonFactory;
use Magento\Quote\Model\QuoteRepository;

/**
 * Class ValidationCall
 *
 * @category Vat_Discount
 * @package  Rahul\VatPlugin\Controller\Index
 * @author   Rahul Makwana <rahul.makwana61@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     rahul.makwana61@gmail.com
 */
class ValidationCall extends \Magento\Framework\App\Action\Action
{
    /**
     * This variable will be used to inject PageFactory.
     *
     * @var PageFactory
     */
    public $resultPageFactory;

    /**
     * This variable will be used to inject session.
     * @var Session
     */
    public $checkoutSession;

    /**
     * This variable will be used to inject json factory.
     *
     * @var JsonFactory
     */
    public $resultJsonFactory;
    /**
     * This variable will be used to inject vat plugin.
     *
     * @var VatPlugin
     */
    public $discountplugin;

    /**
     * This variable will be used to inject quoterepository.
     *
     * @var QuoteRepository
     */
    public $quoteRepository;
    /**
     * ValidationCall constructor.
     *
     * @param Context         $context           inject action context
     * @param PageFactory     $resultPageFactory inject page factory
     * @param Session         $checkoutSession   inject session
     * @param JsonFactory     $resultJsonFactory inject json factory
     * @param VatPlugin       $discountPlugin    inject vat plugin
     * @param QuoteRepository $quoteRepository   inject quote repository
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Session $checkoutSession,
        JsonFactory $resultJsonFactory,
        VatPlugin $discountPlugin,
        QuoteRepository $quoteRepository
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->checkoutSession = $checkoutSession;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->discountplugin = $discountPlugin;
        $this->quoteRepository = $quoteRepository;
        parent::__construct($context);
    }

    /**
     * This function will be used to store vat code if its valid
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();
        $blockInstance = $this->discountplugin;
        $settings = $blockInstance->getDiscountSettings();
        $apikey = $settings['apikey'];
        $endpoint = $settings['endpoint'];
        $vatnumber = $post['vatnumber'];
        $response = $blockInstance->vatValidator($apikey, $endpoint, $vatnumber);
        $result = $this->resultJsonFactory->create();
        $quoteRepository = $this->quoteRepository;
        $quote = $quoteRepository->get($this->checkoutSession->getQuote()->getId());
        $msg = [];
        if ($response['message'] == true) {
            $quote->setSaveVatDiscount($post['vatnumber']);
            $quote->save($quote->collectTotals());
            $msg['valid'] = true;
        } else {
            $quote->setSaveVatDiscount(null);
            $quote->save($quote->collectTotals());
            $msg['valid'] = false;
        }
        return $result->setData($msg);
    }
}
