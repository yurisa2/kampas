<?php

namespace Excellence\Customerattr\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Config;
use Magento\Customer\Model\Customer;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;

    public function __construct(EavSetupFactory $eavSetupFactory, Config $eavConfig)
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig       = $eavConfig;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'gst_number',
            [
                'type'         => 'varchar',
                'label'        => 'GST Number',
                'input'        => 'text',
                'required'     => false,
                'visible'      => true,
                'user_defined' => true,
                'position'     => 999,
                'system'       => 0,
            ]
        );
        $attribute = $this->eavConfig->getAttribute(Customer::ENTITY, 'gst_number');

        //  used_in_forms are of these types you can use forms key according to your need ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','customer_account_edit','customer_address_edit','customer_register_address', 'customer_account_create']

               $attribute->setData(
            'used_in_forms',
            ['adminhtml_customer', 'customer_account_create', 'adminhtml_checkout', 'adminhtml_customer_address', 'checkout_register', 'customer_account_edit', 'customer_address_edit', 'customer_register_address']);
        $attribute->save();
    }
}
