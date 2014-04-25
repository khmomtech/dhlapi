dhlapi
==========
[![Composer Downloads](https://poser.pugx.org/asgoodasnu/dhlapi/d/total.png)](https://packagist.org/packages/asgoodasnu/dhlapi) [![Build Status](https://travis-ci.org/asgoodasnu/dhlapi.png?branch=master)](https://travis-ci.org/asgoodasnu/dhlapi) [![Dependency Status](https://www.versioneye.com/user/projects/53344ea47bae4bb0ec0005a3/badge.png)](https://www.versioneye.com/user/projects/53344ea47bae4bb0ec0005a3)

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
        );
    }


Enable annotations by entering the following line into autoload.php:

    AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

Configuration
==========

to "talk" with DHL you'll need to set following parameters in your parameters.yml

Required settings for production environment:

    dhl_api_b2b_ekp: [YOUR_EKP_NUMBER]
    dhl_api_b2b_is_user: [YOUR_INTRASHIP_USER]
    dhl_api_b2b_is_password: [YOUR_INTRASHIP_PASSWORD]
    dhl_api_b2b_cig_user: [YOUR_APPLICATION_ID]
    dhl_api_b2b_cig_password: [YOUR_APPLICATION_TOKEN]
    dhl_api_b2b_cig_endpoint_uri: 'https://cig.dhl.de/services/production/soap'

Optional settings for production environment:

    dhl_api_b2b_attendance: '01'
    dhl_api_b2b_product_id: 'DDN' (alternative value: 'TDN')

For dev and test environment use your developer account to authenticate with:

    dhl_api_b2b_cig_user: [YOUR_DEVELOPER_ID]
    dhl_api_b2b_cig_password: [YOUR_DEVELOPER_PASSWORD]

