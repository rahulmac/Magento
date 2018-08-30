<?php
/**
 * Created by PhpStorm.
 * User: rahul.makwana61@gmail.com
 * Date: 27/8/18
 * Time: 7:08 PM
 *  PHP Version 7
 *
 * @category Vat_Discount
 * @package  Rahul\VatPlugin\Model
 * @author   Rahul Makwana <rahul.makwana61@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     rahul.makwana61@gmail.com
 */

namespace Rahul\VatPlugin\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Rahul\VatPlugin\Block\VatPlugin;

/**
 * Class DiscountpluginConfigProvider
 *
 * @category Vat_Discount
 * @package  Rahul\VatPlugin\Model
 * @author   Rahul Makwana <rahul.makwana61@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     rahul.makwana61@gmail.com
 */
class DiscountpluginConfigProvider implements ConfigProviderInterface
{
    /**
     * Inject vat plugin
     *
     * @var VatPlugin
     */
    public $discountplugin;

    /**
     * DiscountpluginConfigProvider constructor.
     *
     * @param VatPlugin $discountPlugin vat plugin
     */
    public function __construct(VatPlugin $discountPlugin)
    {
        $this->discountplugin = $discountPlugin;
    }

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        $settings = $this->discountplugin;
        $fetchData = $settings->getDiscountSettings();
        $template = '';
        if ($fetchData['apikey'] != '') {
            $template = 'Rahul_VatPlugin/additional-block';
        }
        return [
            'Rahul' => [
                'VatPlugin' => $template,
            ],
        ];
    }
}
