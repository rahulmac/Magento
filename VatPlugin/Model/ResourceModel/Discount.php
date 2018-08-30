<?php
/**
 * Created by PhpStorm.
 * User: rahul.makwana61@gmail.com
 * Date: 20/6/18
 * Time: 6:31 PM
 *  PHP Version 7
 *
 * @category Vat_Discount
 * @package  Rahul\VatPlugin\Model\ResourceModel
 * @author   Rahul Makwana <rahul.makwana61@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     rahul.makwana61@gmail.com
 */

namespace Rahul\VatPlugin\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Discount
 *
 * @category Vat_Discount
 * @package  Rahul\VatPlugin\Model\ResourceModel
 * @author   Rahul Makwana <rahul.makwana61@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     rahul.makwana61@gmail.com
 */
class Discount extends AbstractDb
{
    /**
     * This will declare table name and primary key
     */
    public function _construct()
    {
        $this->_init('discountplugin', 'vatpluginid');
    }
}
