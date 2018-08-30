<?php
/**
 * Created by PhpStorm.
 * User: rahul.makwana61@gmail.com
 * Date: 20/6/18
 * Time: 6:28 PM
 *  PHP Version 7
 *
 * @category Vat_Discount
 * @package  Rahul\VatPlugin\Model
 * @author   Rahul Makwana <rahul.makwana61@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     rahul.makwana61@gmail.com
 */

namespace Rahul\VatPlugin\Model;

use \Magento\Framework\Model\AbstractModel;
use \Magento\Framework\DataObject\IdentityInterface;

/**
 * Class Discount
 *
 * @category Vat_Discount
 * @package  Rahul\VatPlugin\Model
 * @author   Rahul Makwana <rahul.makwana61@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     rahul.makwana61@gmail.com
 */
class Discount extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'discount_coupons';

    /**
     * This will initialize the discount resource model
     */
    public function _construct()
    {
        $this->_init('Rahul\VatPlugin\Model\ResourceModel\Discount');
    }

    /**
     * This function will be used to get the vat plugin id
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
