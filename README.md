SamanPaymentBundle
===================
A symfony wrapper bundle for  [Saman Electeronic Payment](http://www.sep.ir/en/).

## Install

Via Composer

``` bash
composer require ericomgroup/saman-payment-bundle
```

Edit your app/AppKernel.php to register the bundle in the registerBundles() method as above if you haven't symfony flex:


```php
class AppKernel extends Kernel
{

    public function registerBundles()
    {
        $bundles = array(
            // ...
            // register the bundle here
            new EricomGroup\SamanPaymentBundle\SamanPaymentBundle()
        );
    }
}
```

## Usage

Wherever you have access to the service container :
```php
<?php
    // get the saman_payment as a service
    $samanPayment = $this->container->get('saman_payment');

    // set merchantID and password
    $samanPayment->setMerchantId('your-merchantId')->setPassword('your-password');
    
    //receive param from bank gateway; (verify payed amount and return true or false)
    $result = $samanPayment->receiverParams($refNum, $state, $amountMustBePayed);
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
