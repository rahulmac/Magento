<?php
/**
 * Created by PhpStorm.
 * User: rahul.makwana61@gmail.com
 * Date: 8/8/18
 * Time: 1:53 PM
 *  PHP Version 7
 *
 * @category Vat_Discount
 * @package  Rahul\VatPlugin\Model
 * @author   Rahul Makwana <rahul.makwana61@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     rahul.makwana61@gmail.com
 */

namespace Rahul\VatPlugin\Model;

use \Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use \Magento\Framework\Event\ManagerInterface;
use \Magento\Store\Model\StoreManagerInterface;
use \Magento\SalesRule\Model\Validator;
use \Magento\Framework\Pricing\PriceCurrencyInterface;
use \Magento\Quote\Model\Quote;
use \Magento\Quote\Model\QuoteRepository;
use \Magento\Quote\Api\Data\ShippingAssignmentInterface;
use \Magento\Quote\Model\Quote\Address\Total;

/**
 * Class DiscountCalculation
 *
 * @category Vat_Discount
 * @package  Rahul\VatPlugin\Model
 * @author   Rahul Makwana <rahul.makwana61@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     rahul.makwana61@gmail.com
 */
class DiscountCalculation extends AbstractTotal
{
    /**
     * This variable is for injecting quore repository
     *
     * @var QuoteRepository
     */
    public $quoteRepository;

    /**
     * DiscountCalculation constructor.
     *
     * @param ManagerInterface       $eventManager    event manager
     * @param StoreManagerInterface  $storeManager    store manager
     * @param Validator              $validator       validator
     * @param PriceCurrencyInterface $priceCurrency   price currency
     * @param QuoteRepository        $quoteRepository quote repository
     */
    public function __construct(
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        Validator $validator,
        PriceCurrencyInterface $priceCurrency,
        QuoteRepository $quoteRepository
    ) {
        $this->eventManager = $eventManager;
        $this->calculator = $validator;
        $this->storeManager = $storeManager;
        $this->priceCurrency = $priceCurrency;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * This function will be used for removing tax if vat code is validated
     *
     * @param Quote                       $quote              quote
     * @param ShippingAssignmentInterface $shippingAssignment shipping
     * @param Total                       $total              total
     *
     * @return $this
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);
        $tax = $quote->getShippingAddress()->getData('tax_amount');

        $vatcode = $quote->getSaveVatDiscount();
        $country = $quote->getShippingAddress()->getCountry();

        if (isset($vatcode) && $vatcode != "" && isset($tax) && $tax != "" && $country == "GB") {
            $label = 'Vat Discount';
            $discountAmount = -$tax;

            $appliedCartDiscount = 0;
            if ($total->getDiscountDescription()) {
                $appliedCartDiscount = $total->getDiscountAmount();
                $discountAmount = $total->getDiscountAmount() + $discountAmount;
                $label = $total->getDiscountDescription() . ', ' . $label;
            }

            $total->setDiscountDescription($label);
            $total->setDiscountAmount($discountAmount);
            $total->setBaseDiscountAmount($discountAmount);
            $total->setSubtotalWithDiscount($total->getSubtotal() + $discountAmount);
            $total->setBaseSubtotalWithDiscount($total->getBaseSubtotal() + $discountAmount);

            if (isset($appliedCartDiscount)) {
                $total->addTotalAmount($this->getCode(), $discountAmount - $appliedCartDiscount);
                $total->addBaseTotalAmount($this->getCode(), $discountAmount - $appliedCartDiscount);
            } else {
                $total->addTotalAmount($this->getCode(), $discountAmount);
                $total->addBaseTotalAmount($this->getCode(), $discountAmount);
            }
        }

        return $this;
    }

    /**
     * This function will fetch the quote details
     *
     * @param Quote $quote quote
     * @param Total $total total
     *
     * @return array|null
     */
    public function fetch(Quote $quote, Total $total)
    {
        $quote->getSaveVatDiscount();
        $result = null;
        $amount = $total->getDiscountAmount();
        if ($amount != 0) {
            $description = $total->getDiscountDescription();
            $result = [
                'code' => $this->getCode(),
                'title' => $description,
                'value' => $amount
            ];
        }
        return $result;
    }
}
