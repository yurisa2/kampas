php -d memory_limit=-1 bin/magento setup:upgrade
php -d memory_limit=-1 bin/magento setup:di:compile
php -d memory_limit=-1 bin/magento setup:static-content:deploy
php -d memory_limit=-1 bin/magento setup:static-content:deploy pt_BR
php -d memory_limit=-1 bin/magento cache:clean
php -d memory_limit=-1 bin/magento cache:flush
