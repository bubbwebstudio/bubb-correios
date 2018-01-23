
# Essa lib foi migrada para o repositório [bubbstore/correios](https://github.com/bubbstore/correios)

# bubb-correios

Com esta biblioteca você consegue calcular valores/prazos e rastrear objetos diretamente do webservice dos Correios.

#### **Instalação via composer**

 - PHP >= 5.4
 - [Composer](https://getcomposer.org/)

#### **Composer Setup**
Adicione em seu composer.json

```json
{
    "require": {
        "bubb/bubb-correios": "dev-master"
    }
}
```

#### **Usage**

##### Cotação

```php
use BUBB\Correios\CorreiosQuote;
use BUBB\Correios\Exceptions\CorreiosQuoteException;

try
{
    $quotes = new CorreiosQuote();

    $quotes->setOriginZipcode('14940-000');
            ->setDestinyZipcode('14900-000');
            ->setWeight(2);
            ->setWidth(15);
            ->setHeight(10);
            ->setLength(16);
            ->setServicesCodes(['4014', '4510']);
            ->setFormat('caixa');

    // Métodos opcionais
    $quotes->setCompanyCode('00000000'); // Código da empresa
            ->setPassword('00000000'); // Senha webserivce
            ->setDiameter(0);
            ->setMaoPropria(true);
            ->setValorDeclarado(12.0);

    echo '<pre>' . json_encode($quotes->get(), true) . '</pre>';

} catch ( CorreiosQuoteException $e )
{
    echo $e->getMessage();
}
```

Output:

```php
[
    {
        "price": 29.2,
        "delivery_days": 2,
        "estimate_delivery_date": "2017-08-15",
        "code": 4014,
        "weight": 2,
        "service": {
            "name": "sedex",
            "optional_name": "Sedex"
        }
    },
    {
        "price": 25.8,
        "delivery_days": 5,
        "estimate_delivery_date": "2017-08-18",
        "code": 4510,
        "weight": 2,
        "service": {
            "name": "pac",
            "optional_name": "PAC"
        }
    }
]
```

##### Rastrear objetos

```php
use BUBB\Correios\CorreiosTracking;
use BUBB\Correios\Exceptions\CorreiosTrackingException;

try
{
    $tracking = new CorreiosTracking('PO548836895BR');
    echo '<pre>' . json_encode($tracking->get(), true) . '</pre>';

} catch ( CorreiosTrackingException $e )
{
    echo $e->getMessage();
}
```

Output:

```php
{
    "code": "PO548836895BR",
    "last_timestamp": 1502126880,
    "last_status": "Em trânsito para CTCE RIBEIRAO PRETO - RIBEIRAO PRETO/SP",
    "last_date": "2017-08-07 14:28",
    "last_locale": null,
    "delivered": false,
    "delivered_at": null,
    "tracking": [
        {
            "timestamp": 1502126880,
            "date": "2017-08-07 14:28",
            "place": "CTE VILA MARIA - SAO PAULO/SP Objeto encaminhado",
            "status": "Em trânsito para CTCE RIBEIRAO PRETO - RIBEIRAO PRETO/SP",
            "forwarded": null,
            "delivered": false
        },
        {
            "timestamp": 1502109900,
            "date": "2017-08-07 09:45",
            "place": "AGF JARDIM MARILIA - SAO PAULO/SP Objeto encaminhado",
            "status": "Em trânsito para CTE VILA MARIA - SAO PAULO/SP",
            "forwarded": null,
            "delivered": false
        },
        {
            "timestamp": 1501868640,
            "date": "2017-08-04 14:44",
            "place": "AGF JARDIM MARILIA - SAO PAULO/SP",
            "status": "Objeto postado",
            "forwarded": null,
            "delivered": false
        }
    ]
}
```

##### Consultar CEP

```php
use BUBB\Correios\Zipcode;
use BUBB\Correios\Exceptions\ZipcodeException;

try
{
    $zipcode = new Zipcode('14940000');
    echo '<pre>' . json_encode($zipcode->get(), true) . '</pre>';

} catch ( ZipcodeException $e )
{
    echo $e->getMessage();
}
```

Output:

```json
{
    "zipcode": "14940000",
    "street": "",
    "neighborhood": "",
    "city": "Ibitinga",
    "state": "SP"
}
```