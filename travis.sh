#!/usr/bin/env bash

moduleName='billpay'
moduleNiceName='billpay'
cpath=`pwd`
modulePath="$cpath/module"
shopPath="$cpath/current"
globalResult=1
message=""

function runTests {
    echo "Preparing environment..."
    echo "Adding PSR-0 namespaces"
    php "$modulePath/composer-add-psr.php" composer.json 0 Unit vendor/spryker-eco/arvato-rss/tests
    php "$modulePath/composer-add-psr.php" composer.json 0 Functional vendor/spryker-eco/arvato-rss/tests
    echo "define('APPLICATION_ROOT_DIR', '$shopPath');" >> "$shopPath/vendor/composer/autoload_real.php"
    echo "Copy configuration..."
    if [ -f "vendor/spryker-eco/$moduleName/config/config.dist.php" ]; then
        tail -n +2 "vendor/spryker-eco/$moduleName/config/config.dist.php" >> config/Shared/config_default-devtest.php
        php "$modulePath/fix-config.php" config/Shared/config_default-devtest.php
    fi
    echo "Building transfer objects..."
    "$shopPath/vendor/bin/console" transfer:generate
    echo "Running tests..."
    cd "vendor/spryker-eco/$moduleName/"
    "$shopPath/vendor/bin/codecept" run
    if [ "$?" = 0 ]; then
        newMessage=$'\nTests are green'
        message="$message$newMessage"
        testResult=0
    else
        newMessage=$'\nTests are failing'
        message="$message$newMessage"
        testResult=1
    fi
    echo "Done tests"
    return $testResult
}

function checkWithLatestDemoShop {
    echo "Checking module with latest DemoShop"
    composer config repositories.ecomodule path $modulePath

    composer require "spryker-eco/$moduleName @dev"
    result=$?
    if [ "$result" = 0 ]; then
        newMessage=$'\nCurrent version of module is COMPATIBLE with latest DemoShop modules\' versions'
        message="$message$newMessage"

        if runTests; then
            globalResult=0

            checkModuleWithLatestVersionOfDemoshop
        fi
    else
        newMessage=$'\nCurrent version of module is NOT COMPATIBLE with latest DemoShop due to modules\' versions'
        message="$message$newMessage"

        checkModuleWithLatestVersionOfDemoshop
    fi
}

function checkModuleWithLatestVersionOfDemoshop {
    echo "Merging composer.json dependencies..."
    updates=`php "$modulePath/merge-composer.php" "$modulePath/composer.json" composer.json "$modulePath/composer.json"`
    newMessage=$'\nUpdated dependencies in module to match DemoShop\n'
    message="$message$newMessage$updates"

    echo "Installing module with updated dependencies..."
    composer require "spryker-eco/$moduleName @dev"
    result=$?
    if [ "$result" = 0 ]; then
        newMessage=$'\nModule is COMPATIBLE with latest versions of modules used in DemoShop'
        message="$message$newMessage"

        runTests
    else
        newMessage=$'\nModule is NOT COMPATIBLE with latest versions of modules used in DemoShop'
        message="$message$newMessage"
    fi
}

cd current/
composer install

checkWithLatestDemoShop

echo "$message"
exit $globalResult