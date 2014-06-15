Symfony2-PaymentBundle
======================

Copyright 2014 - Timothee Moulin  
http://timothee-moulin.me/  

---
This Symfony2 Bundle add a transaction handler for SaferPay (http://saferpay.com/)  

---
INSTALLATION
---
1. Get the sources
2. Add the bundle to your AppKernel.php
``` php new Tehem\PaymentBundle\TehemPaymentBundle(),```
3. Configure the bundle by modifying the "services.yml" file
``` yaml
parameters:
    spCreatePayUrl: https://www.saferpay.com/hosting/CreatePayInit.asp?
    spVerifyPayUrl: https://www.saferpay.com/hosting/VerifyPayConfirm.asp?
    spFinalizePayUrl: https://www.saferpay.com/hosting/PayCompleteV2.asp?
    spAccount: xxx
    spPassword: xxx
    spSuccess: xxx

services:
    tehem.saferpay:
        class: Tehem\PaymentBundle\Interaction\SaferPay
        arguments: [%spCreatePayUrl%, %spVerifyPayUrl%, %spFinalizePayUrl%, %spAccount%, %spPassword%, %spSuccess%]
```
Replace xxx by your SaferPay account and password.
spSuccess is the URL where your customer will be redirected after the success of the transaction.