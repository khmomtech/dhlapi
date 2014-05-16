dhlapi
==========
[![Composer Downloads](https://poser.pugx.org/asgoodasnu/dhlapi/d/total.png)](https://packagist.org/packages/asgoodasnu/dhlapi) [![Build Status](https://travis-ci.org/asgoodasnu/dhlapi.png?branch=master)](https://travis-ci.org/asgoodasnu/dhlapi) [![Dependency Status](https://www.versioneye.com/user/projects/535e7506fe0d079701000023/badge.png)](https://www.versioneye.com/user/projects/535e7506fe0d079701000023)

this bundle implements the DHL API as described here https://entwickler.dhl.de/group/ep/apis. 
An account is required to see this restricted web page.

Installation
==========

Install via Composer by adding this to composer.json:

    php composer.phar require asgoodasnu/dhlapi "dev-master"

Enable in your AppKernel.php:

    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Wk\DhlApiBundle\WkDhlApiBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
        );
    }


Enable annotations by entering the following line into autoload.php:

    AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

Configuration
==========

to "talk" with DHL you'll need to set following parameters in your parameters.yml

Required settings for production environment:

    dhl_api_b2b_is_user: [YOUR_INTRASHIP_USER]
    dhl_api_b2b_is_password: [YOUR_INTRASHIP_PASSWORD]
    dhl_api_b2b_cig_user: [YOUR_APPLICATION_ID]
    dhl_api_b2b_cig_password: [YOUR_APPLICATION_TOKEN]
    dhl_api_b2b_cig_endpoint_uri: 'https://cig.dhl.de/services/production/soap'
    dhl_api_b2b_accounts: 
        account1:   [ACCOUNT_ID_1]
        account2:   [ACCOUNT_ID_2]
        ...

For dev and test environment use your developer account to authenticate with:

    dhl_api_b2b_cig_user: [YOUR_DEVELOPER_ID]
    dhl_api_b2b_cig_password: [YOUR_DEVELOPER_PASSWORD]

Services
==========

Your can use two services to connect to Intraship or to create an ident code for shipment independently from the DHL API

    $connection = $this->get('wk_dhl_api.b2b.connection');
    $connection->cancelPickup('123456789012');

    $identCode = $this->get('wk_dhl_api.b2b.ident_code');
    $identCode->setSerial(1);
    $identCode->get('retoure');
