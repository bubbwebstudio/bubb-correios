
# installment

installment is an official library created by developers of the [BUBB.STORE](http://bubbstore.com.br) platform that handles the calculation of installments.


#### **System Requirements**
It requires the following components to work correctly.

 - PHP >= 5.4
 - [Composer](https://getcomposer.org/)

#### **Composer Setup**
In your `composer.json`, include the following:

```json
{
    "require": {
        "bubb/installments": "master"
    }
}
```

#### **Usage**

##### Example #1
To calculate possible installments for an order, in this case:

`Order total price: 200 BRL`, 
`Order max. installments: 3`

```php
$installment = new Installment;
// Required.
$installment->setAmount(200);
// Required.
$installment->setTaxes([
    // installment 1: no tax, no discount.
    ['installment' => 1, 'percent_discount' => 0, 'tax' => 0],
    // installment 2: no tax, no discount.
    ['installment' => 2, 'percent_discount' => 0, 'tax' => 0],
    // installment 3: no tax, no discount.
    ['installment' => 3, 'percent_discount' => 0, 'tax' => 0],
]);
exit(var_dump($installment->get()));
```

Its output would be:

```php
{  
   "max_installment":3,
   "max_installment_value":66.67,
   "amount":200,
   "text":"3x de R$ 66,67 sem juros",
   "text_with_tax":"3x de R$ 66,67",
   "text_discount_percent":null,
   "text_discount":null,
   "installments":[  
      {  
         "amount":200,
         "amount_formated":"R$ 200,00",
         "base_value":200,
         "tax":0,
         "tax_value":0,
         "discount_percent":0,
         "discount_value":0,
         "discount_value_formated":"R$ 0,00",
         "installment":1,
         "installment_value":200,
         "installment_value_formated":"R$ 200,00",
         "text":"1x de R$ 200,00 sem juros",
         "text_with_tax":"1x de R$ 200,00",
         "text_discount_percent":null,
         "text_discount":null
      },
      {  
         "amount":200,
         "amount_formated":"R$ 200,00",
         "base_value":200,
         "tax":0,
         "tax_value":0,
         "discount_percent":0,
         "discount_value":0,
         "discount_value_formated":"R$ 0,00",
         "installment":2,
         "installment_value":100,
         "installment_value_formated":"R$ 100,00",
         "text":"2x de R$ 100,00 sem juros",
         "text_with_tax":"2x de R$ 100,00",
         "text_discount_percent":null,
         "text_discount":null
      },
      {  
         "amount":200,
         "amount_formated":"R$ 200,00",
         "base_value":200,
         "tax":0,
         "tax_value":0,
         "discount_percent":0,
         "discount_value":0,
         "discount_value_formated":"R$ 0,00",
         "installment":3,
         "installment_value":66.67,
         "installment_value_formated":"R$ 66,67",
         "text":"3x de R$ 66,67 sem juros",
         "text_with_tax":"3x de R$ 66,67",
         "text_discount_percent":null,
         "text_discount":null
      }
   ]
}
```

##### Example #2

To calculate possible installments for an order, in this case:

`Order total price: 677 BRL`, 
`Order max. installments: 12 (up to 6, without taxes)`
`Order min. installment value: 20 BRL`

```php
$installment = new Installment;
// Required.
$installment->setAmount(200);
// Required.
$installment->setTaxes([
    // installment 1: no tax, 10% discount.
    ['installment' => 1, 'percent_discount' => 10, 'tax' => 0],
    // Installment 2: no tax, no discount.
    ['installment' => 2, 'percent_discount' => 0, 'tax' => 0],
    // Installment 3: no tax, no discount.
    ['installment' => 3, 'percent_discount' => 0, 'tax' => 0],
    // Installment 4: no tax, no discount.
    ['installment' => 4, 'percent_discount' => 0, 'tax' => 0],
    // Installment 5: no tax, no discount.
    ['installment' => 5, 'percent_discount' => 0, 'tax' => 0],
    // Installment 6: no tax, no discount.
    ['installment' => 6, 'percent_discount' => 0, 'tax' => 0],
    // Installment 7: tax 1.99% month, no discount.
    ['installment' => 7, 'percent_discount' => 0, 'tax' => 1.99],
    // Installment 8: tax 1.99% month, no discount.
    ['installment' => 8, 'percent_discount' => 0, 'tax' => 1.99],
    // Installment 9: tax 1.99% month, no discount.
    ['installment' => 9, 'percent_discount' => 0, 'tax' => 1.99],
    // Installment 10: tax 1.99% month, no discount.
    ['installment' => 10, 'percent_discount' => 0, 'tax' => 1.99],
    // Installment 11: no tax, no discount.
    ['installment' => 11, 'percent_discount' => 0, 'tax' => 1.99],
    // Installment 12: tax 1.99% month, no discount.
    ['installment' => 12, 'percent_discount' => 0, 'tax' => 1.99]
]);
$installment->setMinInstallmentValue(20);
$installment->setMaxInstallmentsWithoutTax(6);
exit(var_dump($installment->get()));
```

Its output would be:

```php
{
   // Max installments is 11 because the library popped the last installment for it had a value lower than the min. installment value (20 BRL).
   "max_installment":11,
   "max_installment_value":20.06,
   "amount":220.71,
   "text":"11x de R$ 20,06 com juros",
   "text_with_tax":"11x de R$ 20,06",
   "text_discount_percent":"10% de desconto \u00e0 vista no cart\u00e3o",
   "text_discount":"R$ 180,00 \u00e0 vista no cart\u00e3o",
   "installments":[  
      {
         // Discounted amount for the first installment.  
         "amount":180,
         "amount_formated":"R$ 180,00",
         "base_value":200,
         "tax":0,
         "tax_value":-20,
         "discount_percent":10,
         "discount_value":20,
         "discount_value_formated":"R$ 20,00",
         "installment":1,
         "installment_value":180,
         "installment_value_formated":"R$ 180,00",
         "text":"1x de R$ 180,00 sem juros",
         "text_with_tax":"1x de R$ 180,00",
         "text_discount_percent":"10% de desconto \u00e0 vista no cart\u00e3o",
         "text_discount":"R$ 180,00 \u00e0 vista no cart\u00e3o"
      },
      {  
         "amount":200,
         "amount_formated":"R$ 200,00",
         "base_value":200,
         "tax":0,
         "tax_value":0,
         "discount_percent":0,
         "discount_value":0,
         "discount_value_formated":"R$ 0,00",
         "installment":2,
         "installment_value":100,
         "installment_value_formated":"R$ 100,00",
         "text":"2x de R$ 100,00 sem juros",
         "text_with_tax":"2x de R$ 100,00",
         "text_discount_percent":null,
         "text_discount":null
      },
      {  
         "amount":200,
         "amount_formated":"R$ 200,00",
         "base_value":200,
         "tax":0,
         "tax_value":0,
         "discount_percent":0,
         "discount_value":0,
         "discount_value_formated":"R$ 0,00",
         "installment":3,
         "installment_value":66.67,
         "installment_value_formated":"R$ 66,67",
         "text":"3x de R$ 66,67 sem juros",
         "text_with_tax":"3x de R$ 66,67",
         "text_discount_percent":null,
         "text_discount":null
      },
      {  
         "amount":200,
         "amount_formated":"R$ 200,00",
         "base_value":200,
         "tax":0,
         "tax_value":0,
         "discount_percent":0,
         "discount_value":0,
         "discount_value_formated":"R$ 0,00",
         "installment":4,
         "installment_value":50,
         "installment_value_formated":"R$ 50,00",
         "text":"4x de R$ 50,00 sem juros",
         "text_with_tax":"4x de R$ 50,00",
         "text_discount_percent":null,
         "text_discount":null
      },
      {  
         "amount":200,
         "amount_formated":"R$ 200,00",
         "base_value":200,
         "tax":0,
         "tax_value":0,
         "discount_percent":0,
         "discount_value":0,
         "discount_value_formated":"R$ 0,00",
         "installment":5,
         "installment_value":40,
         "installment_value_formated":"R$ 40,00",
         "text":"5x de R$ 40,00 sem juros",
         "text_with_tax":"5x de R$ 40,00",
         "text_discount_percent":null,
         "text_discount":null
      },
      {  
         "amount":200,
         "amount_formated":"R$ 200,00",
         "base_value":200,
         "tax":0,
         "tax_value":0,
         "discount_percent":0,
         "discount_value":0,
         "discount_value_formated":"R$ 0,00",
         "installment":6,
         "installment_value":33.33,
         "installment_value_formated":"R$ 33,33",
         "text":"6x de R$ 33,33 sem juros",
         "text_with_tax":"6x de R$ 33,33",
         "text_discount_percent":null,
         "text_discount":null
      },
      {
         // Amount with taxes.  
         "amount":203.98,
         "amount_formated":"R$ 203,98",
         "base_value":200,
         "tax":1.99,
         "tax_value":3.98,
         "discount_percent":0,
         "discount_value":-3.98,
         "discount_value_formated":"R$ -3,98",
         "installment":7,
         "installment_value":29.14,
         "installment_value_formated":"R$ 29,14",
         "text":"7x de R$ 29,14 com juros",
         "text_with_tax":"7x de R$ 29,14",
         "text_discount_percent":null,
         "text_discount":null
      },
      {  
         // Amount with taxes.
         "amount":208.04,
         "amount_formated":"R$ 208,04",
         "base_value":200,
         "tax":1.99,
         "tax_value":8.039202,
         "discount_percent":0,
         "discount_value":-8.04,
         "discount_value_formated":"R$ -8,04",
         "installment":8,
         "installment_value":26,
         "installment_value_formated":"R$ 26,00",
         "text":"8x de R$ 26,00 com juros",
         "text_with_tax":"8x de R$ 26,00",
         "text_discount_percent":null,
         "text_discount":null
      },
      {
         // Amount with taxes. 
         "amount":212.18,
         "amount_formated":"R$ 212,18",
         "base_value":200,
         "tax":1.99,
         "tax_value":12.1791821198,
         "discount_percent":0,
         "discount_value":-12.18,
         "discount_value_formated":"R$ -12,18",
         "installment":9,
         "installment_value":23.58,
         "installment_value_formated":"R$ 23,58",
         "text":"9x de R$ 23,58 com juros",
         "text_with_tax":"9x de R$ 23,58",
         "text_discount_percent":null,
         "text_discount":null
      },
      {
         // Amount with taxes. 
         "amount":216.4,
         "amount_formated":"R$ 216,40",
         "base_value":200,
         "tax":1.99,
         "tax_value":16.401547844,
         "discount_percent":0,
         "discount_value":-16.4,
         "discount_value_formated":"R$ -16,40",
         "installment":10,
         "installment_value":21.64,
         "installment_value_formated":"R$ 21,64",
         "text":"10x de R$ 21,64 com juros",
         "text_with_tax":"10x de R$ 21,64",
         "text_discount_percent":null,
         "text_discount":null
      },
      {
         // Amount with taxes.   
         "amount":220.71,
         "amount_formated":"R$ 220,71",
         "base_value":200,
         "tax":1.99,
         "tax_value":20.7079386461,
         "discount_percent":0,
         "discount_value":-20.71,
         "discount_value_formated":"R$ -20,71",
         "installment":11,
         "installment_value":20.06,
         "installment_value_formated":"R$ 20,06",
         "text":"11x de R$ 20,06 com juros",
         "text_with_tax":"11x de R$ 20,06",
         "text_discount_percent":null,
         "text_discount":null
      }
   ]
}
```