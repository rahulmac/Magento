<?php
/**
 * Created by PhpStorm.
 * User: rahul.makwana61@gmail.com
 * Date: 20/6/18
 * Time: 6:50 PM
 *  PHP Version 7
 *
 * @category Vat_Discount
 * @package  Rahul\VatPlugin\Model\ResourceModel\Discount
 * @author   Rahul Makwana <rahul.makwana61@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     rahul.makwana61@gmail.com
 */

namespace Rahul\VatPlugin\Model\ResourceModel\Discount;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 *
 * @category Vat_Discount
 * @package  Rahul\VatPlugin\Model\ResourceModel\Discount
 * @author   Rahul Makwana <rahul.makwana61@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     rahul.makwana61@gmail.com
 */
class Collection extends AbstractCollection
{
    /**
     * This function is used for model and resource model initalization
     */
    public function _construct()
    {
        $model = 'Rahul\VatPlugin\Model\Discount';
        $resourcemodel = 'Rahul\VatPlugin\Model\ResourceModel\Discount';
        $this->_init($model, $resourcemodel);
    }
}
