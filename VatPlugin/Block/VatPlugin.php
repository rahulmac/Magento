<?php
/**
 * Created by PhpStorm.
 * User: rahul.makwana61@gmail.com
 * Date: 31/7/18
 * Time: 1:10 PM
 *  PHP Version 7
 *
 * @category Vat_Discount
 * @package  Rahul\VatPlugin\Block
 * @author   Rahul Makwana <rahul.makwana61@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     rahul.makwana61@gmail.com
 */

namespace Rahul\VatPlugin\Block;

use \Magento\Backend\Block\Template\Context;
use \Rahul\VatPlugin\Model\DiscountFactory;
use Magento\Framework\HTTP\Client\Curl;
use \Magento\Framework\View\Element\Template;

/**
 * Class VatPlugin
 *
 * @category Vat_Discount
 * @package  Rahul\VatPlugin\Block
 * @author   Rahul Makwana <rahul.makwana61@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     rahul.makwana61@gmail.com
 */

class VatPlugin extends Template
{
    /**
     * This variable will be used for injecting dependency for  DiscountFactory.
     *
     * @var DiscountFactory
     */
    public $discountFactory;

    /**
     * This variable will be used for passing form key for magento admin section.
     *
     * @var \Magento\Framework\Data\Form\FormKey
     */

    public $formKey;

    /**
     * This variable will be used for injecting dependency to use Curl client.
     *
     * @var Curl
     */
    public $curlClient;

    /**
     * VatPlugin constructor this is the constructor for injecting dependencies.
     *
     * @param Context         $context         for context dependency
     * @param DiscountFactory $discountFactory inject discount factory.
     * @param Curl            $curlClient      inject curl dependency.
     */
    public function __construct(
        Context $context,
        DiscountFactory $discountFactory,
        Curl $curlClient
    ) {
        $this->discountFactory = $discountFactory;
        $this->formKey = $context->getFormKey();
        $this->curlClient = $curlClient;
        parent::__construct($context);
    }

    /**
     * This function will be used for returning the url with key.
     *
     * @param string $url url passed by user
     *
     * @return string
     */
    public function formURL($url)
    {
        return $this->getUrl($url);
    }

    /**
     * This function will be used to retrive backend key.
     *
     * @return string
     */
    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    /**
     * This function will be used for storing config settings for vat plugin.
     *
     * @param string $api      api key entered by user
     * @param string $endpoint endpoint entered by user
     *
     * @return mixed
     */
    public function savesettings($api, $endpoint)
    {
        $settings = $this->discountFactory->create();
        $settings->setData(
            [
            'apikey' => $api,
            'endpoint' => $endpoint,
            ]
        );
        return $settings->save();
    }

    /**
     *  This function will return the config settings .
     *
     * @return array
     */
    public function getDiscountSettings()
    {
        $settings = $this->discountFactory->create();
        $settings = $settings->getCollection();
        $settinginfo = [];
        foreach ($settings as $setting) {
            $settinginfo['apikey'] = $setting['apikey'];
            $settinginfo['endpoint'] = $setting['endpoint'];
            $settinginfo['vatpluginid'] = $setting['vatpluginid'];
        }

        $array_count = count($settinginfo);
        $settinginfo['url'] = $this->formURL('/vatplugin/store');
        $settinginfo['entry'] = 0;
        if ($array_count > 0) {
            $settinginfo['url'] = $this->formURL('/vatplugin/update');
            $settinginfo['entry'] = 1;
        } else {
            $settinginfo['apikey'] = '';
            $settinginfo['endpoint'] = '';
            $settinginfo['vatpluginid'] ='';
        }

        return $settinginfo;
    }

    /**
     * This function will be used for updating config settings for vat plugin.
     *
     * @param string $api      api key provided
     * @param string $endpoint end point provided
     * @param string $id       id of the vat plugin record
     *
     * @return string mixed
     */
    public function updatesettings($api, $endpoint, $id)
    {
        $settings = $this->discountFactory->create();
        $settings->setData(
            [
                'vatpluginid' => $id,
                'apikey' => $api,
                'endpoint' => $endpoint
            ]
        );
        return $settings->save();
    }

    /**
     * This function will be used for validate a vat number entered by user.
     *
     * @param string $apikey    api key entered by user
     * @param string $endpoint  endpoint provided
     * @param string $vatnumber vat number provided
     *
     * @return array $messages
     */
    public function vatValidator($apikey, $endpoint, $vatnumber)
    {
        $curl = $this->curlClient;
        $curl->setOption(CURLOPT_URL, $endpoint.$vatnumber);
        $curl->setOption(CURLOPT_HEADER, 0);
        $curl->setOption(CURLOPT_HTTPHEADER, ['Apikey: '.$apikey]);
        $curl->setOption(CURLOPT_RETURNTRANSFER, 1);
        $curl->setOption(CURLOPT_TIMEOUT, 40);
        $curl->get($endpoint);
        $response = $curl->getBody();

        $result = json_decode($response, true);

        $messages = [];
        $messages['status'] = $result['status'];
        if ($result['status'] == 200 || $result['status']==404) {
            $messages['message']  = $result['valid'];
        } else {
            $messages['message'] = $result['message'];
        }
        return $messages;
    }
}
