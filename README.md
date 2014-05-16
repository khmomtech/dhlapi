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

You can use the two following services.

Connect to Intraship:

    $connection = $this->get('wk_dhl_api.b2b.connection');
    $connection->cancelPickup('123456789012');

Create an ident code for shipment independent of Intraship:

    $identCode = $this->get('wk_dhl_api.b2b.ident_code');
    $identCode->setSerial(1);
    $identCode->get('retoure');

Controllers
==========

Here are all routes to call the API. You can enter the following command to see the available routes:
    
    php app/console router:debug

wk_dhl_api_b2b_ident_code
-------------------------

- Path: /dhl/b2b/identcode/{account}/{id}.{_format}
- Host: ANY
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: WkDhlApiBundle:B2b:getIdentCode
    - `_format`: json
- Requirements: 
    - `_format`: (xml|json)
    - `id`: \d+
    - `account`: \w+


wk_dhl_api_b2b_book_pickup
--------------------------

- Path: /dhl/b2b/pickup.{_format}
- Host: ANY
- Scheme: ANY
- Method: POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: WkDhlApiBundle:B2b:bookPickup
    - `_format`: json
- Requirements: 
    - `_format`: (xml|json)

Sample JSON payload for POST content:

    {
        information:{
            product:'DDN',
            account:5005537481,
            attendance:1,
            date:'2011-04-07',
            ready_by_time:'09:00',
            closing_time:'16:00',
            location:'Main Building',
            pieces:1,
            pallets:0,
            weight:4,
            shipments:1,
            total_weight:2,
            max_length:70,
            max_width:30,
            max_height:15
        },
        address:{
            name:{
                company:{
                    name:'Muster Company'
                }
            },
            address:{
                street_name:'Leipziger Strasse',
                street_number:47,
                zip:{
                    germany:10117
                },
                city:'Berlin',
                country:{
                    code:'DE'
                }
            },
            communication:{
                phone:'+4930-33215-0',
                email:'max@muster.de',
                contact:'Max Muster'
            }
        },
        orderer:{
            name:{
                company:{
                    name:'Muster Company'
                }
            },
            address:{
                street_name:'Leipziger Straße',
                street_number:47,
                zip:{
                    germany:10117
                },
                city:'Berlin',
                country:{
                    code:'DE'
                }
            },
            communication:{
                phone:'+ 49 0987654321',
                email:'max@muster.de',
                contact:'Max Muster'
            }
        }
    }

wk_dhl_api_b2b_cancel_pickup
----------------------------

- Path: /dhl/b2b/pickup/{id}.{_format}
- Host: ANY
- Scheme: ANY
- Method: DELETE
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: WkDhlApiBundle:B2b:cancelPickup
    - `_format`: json
- Requirements: 
    - `_format`: (xml|json)
    - `id`: \d+


wk_dhl_api_b2b_create_shipment_dd
---------------------------------

- Path: /dhl/b2b/dd/shipment.{_format}
- Host: ANY
- Scheme: ANY
- Method: POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: WkDhlApiBundle:B2b:createShipmentDD
    - `_format`: json
- Requirements: 
    - `_format`: (xml|json)

Sample JSON payload for POST content:

    {
        sequence:1,
        shipment:{
            shipment:{
                product:'EPN',
                date:'2014-10-08',
                ekp:5000000000,
                attendance:{
                    id:1
                },
                item:{
                    weight:10,
                    length:50,
                    width:30,
                    height:15,
                    package_type:'PK'
                }
            }
        },
        shipper:{
            name:{
                company:{
                    name:'Muster Company'
                }
            },
            address:{
                street_name:'Leipziger Straße',
                street_number:47,
                zip:{
                    germany:10117
                },
                city:'Berlin',
                country:{
                    code:'DE'
                }
            },
            communication:{
                email:'max@muster.de',
                contact:'Max Muster'
            }
        },
        receiver:{
            name:{
                person:{
                    firstname:'Markus',
                    lastname:'Meier'
                }
            },
            address:{
                street_name:'Marktplatz',
                street_number:1,
                zip:{
                    other:70173
                },
                city:'Stuttgart',
                country:{
                    code:'DE'
                }
            },
            communication:{
                phone:'0049-30-763291'
            }
        }
    }

wk_dhl_api_b2b_delete_shipment_dd
---------------------------------

- Path: /dhl/b2b/dd/shipment/{id}.{_format}
- Host: ANY
- Scheme: ANY
- Method: DELETE
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: WkDhlApiBundle:B2b:deleteShipmentDD
    - `_format`: json
- Requirements: 
    - `_format`: (xml|json)
    - `id`: \d{12}


wk_dhl_api_b2b_update_shipment_dd
---------------------------------

- Path: /dhl/b2b/dd/shipment/{id}.{_format}
- Host: ANY
- Scheme: ANY
- Method: PUT
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: WkDhlApiBundle:B2b:updateShipmentDD
    - `_format`: json
- Requirements: 
    - `_format`: (xml|json)
    - `id`: \d{12}

Sample JSON payload for POST content:

[See wk_dhl_api_b2b_create_shipment_dd](#user-content-wk_dhl_api_b2b_update_shipment_dd)

wk_dhl_api_b2b_get_label_dd
---------------------------

- Path: /dhl/b2b/dd/label/{id}.{_format}
- Host: ANY
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: WkDhlApiBundle:B2b:getLabelDD
    - `_format`: json
- Requirements: 
    - `_format`: (xml|json)
    - `id`: \d{12}


wk_dhl_api_b2b_get_export_dd
----------------------------

- Path: /dhl/b2b/dd/export/{id}.{_format}
- Host: ANY
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: WkDhlApiBundle:B2b:getExportDocDD
    - `_format`: json
- Requirements: 
    - `_format`: (xml|json|pdf)
    - `id`: \d{12}


wk_dhl_api_b2b_create_shipment_td
---------------------------------

- Path: /dhl/b2b/td/shipment.{_format}
- Host: ANY
- Scheme: ANY
- Method: POST
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: WkDhlApiBundle:B2b:createShipmentTD
    - `_format`: json
- Requirements: 
    - `_format`: (xml|json)


wk_dhl_api_b2b_delete_shipment_td
---------------------------------

- Path: /dhl/b2b/td/shipment/{id}.{_format}
- Host: ANY
- Scheme: ANY
- Method: DELETE
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: WkDhlApiBundle:B2b:deleteShipmentTD
    - `_format`: json
- Requirements: 
    - `_format`: (xml|json)
    - `id`: \d{12}


wk_dhl_api_b2b_get_label_td
---------------------------

- Path: /dhl/b2b/td/label/{id}.{_format}
- Host: ANY
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: WkDhlApiBundle:B2b:getLabelTD
    - `_format`: json
- Requirements: 
    - `_format`: (xml|json)
    - `id`: \d{12}


wk_dhl_api_b2b_get_export_td
----------------------------

- Path: /dhl/b2b/td/export/{id}.{_format}
- Host: ANY
- Scheme: ANY
- Method: GET
- Class: Symfony\Component\Routing\Route
- Defaults: 
    - `_controller`: WkDhlApiBundle:B2b:getExportDocTD
    - `_format`: json
- Requirements: 
    - `_format`: (xml|json|pdf)
    - `id`: \d{12}

TODOS:
==========

- Implement B2C API
- Implement shipment tracking API
- Implement location search API
- Implement market place API
