![alt text](https://marshmallow.dev/cdn/media/logo-red-237x46.png "marshmallow.")

# Laravel Priceable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/marshmallow/priceable.svg?style=flat-square)](https://packagist.org/packages/marshmallow/priceable)
[![Total Downloads](https://img.shields.io/packagist/dt/marshmallow/priceable.svg?style=flat-square)](https://packagist.org/packages/marshmallow/priceable)

Attach prices, currencies, VAT rates and price types to any Eloquent model. Priceable handles VAT calculation, multi-currency, discounts and validity periods, and ships ready-made Laravel Nova resources for managing it all.

## Installation

Priceable depends on [Laravel Nova](https://nova.laravel.com), a paid package. Make sure the Nova Composer repository is configured and your credentials are set:

```bash
composer config repositories.nova composer https://nova.laravel.com
composer config http-basic.nova.laravel.com "your-email" "your-license-key"
```

Install the package via Composer:

```bash
composer require marshmallow/priceable
```

Publish the config file:

```bash
php artisan vendor:publish --tag="config"
```

The migrations are loaded automatically by the package, so just run:

```bash
php artisan migrate
```

Seed the default currencies and VAT rates:

```bash
php artisan db:seed --class="Marshmallow\Priceable\Seeders\CurrencySeeder"
php artisan db:seed --class="Marshmallow\Priceable\Seeders\VatRatesSeeder"
```

## Configuration

`config/priceable.php`:

| Key | Default | Description |
| --- | --- | --- |
| `detault_price_type` | `1` | The price type id used when a model doesn't ask for a specific one. |
| `currency` | `env('CURRENCY', 'eur')` | Default currency code. |
| `currency_locale` | `env('CURRENCY_LOCALE', 'nl')` | Locale used to format money. |
| `models` | `Price`, `Currency`, `VatRate`, `PriceType` | Swap any model for your own implementation. |
| `resources` | Priceable Nova resources | Swap any Nova resource for your own. |
| `nova.prices_are_including_vat` | `true` | Whether prices entered in Nova include VAT. |
| `nova.defaults.currencies` | `1` | Default currency id for new prices. |
| `nova.defaults.vat_rates` | `2` | Default VAT rate id for new prices. |
| `nova.resources` | `[]` | Priceable models that Nova should expose. |
| `on_multiple_prices` | `'lowest'` | Which price wins when a model has several: `highest`, `lowest`, `eldest`, `newest`. |
| `public_excluding_vat` | `false` | Display prices excluding VAT on the front-end. |
| `observers.price` | `PriceableObserver` | Observer that computes the VAT columns on save. |

## Usage

Add the `Priceable` trait to any model you want to price:

```php
use Illuminate\Database\Eloquent\Model;
use Marshmallow\Priceable\Traits\Priceable;

class Product extends Model
{
    use Priceable;
}
```

Attach a price (prices are polymorphic, so any priceable model works):

```php
$product->prices()->create([
    'price_type_id' => config('priceable.detault_price_type'),
    'currency_id'   => 1,
    'vatrate_id'    => 2,
    'display_price' => 19.95,
    'valid_from'    => now(),
]);
```

Read prices on the front-end:

```php
$product->currentPrice();   // numeric price for the active currency & price type
$product->price;            // the resolved Price model (->price(), ->formatPrice(), ...)
$product->price_formatted;  // formatted string, e.g. "€ 19,95"

$product->isDiscounted();   // true when multiple prices resolve to different amounts
$product->discountedFrom(); // the highest price, to show a struck-through "from" price
```

Ask for a specific price type:

```php
use Marshmallow\Priceable\Models\PriceType;

$product->priceType(PriceType::find(2))->currentPrice();
```

### Switching currency

The package registers a named `set-currency` route and request macros:

```blade
@foreach (\Marshmallow\Priceable\Models\Currency::get() as $currency)
    <a href="{{ route('set-currency', $currency) }}">{{ $currency->name }}</a>
@endforeach
```

```php
request()->getUserCurrency();           // the visitor's active currency
request()->setUserCurrency($currency);  // set it manually
```

## Contributing

Pull requests are welcome. For larger changes, please open an issue first to discuss what you would like to change.

## Security Vulnerabilities

Please report security vulnerabilities by email to [stef@marshmallow.dev](mailto:stef@marshmallow.dev) rather than via the public issue tracker.

## Credits

- [Stef van Esch](https://github.com/stefvanesch)
- [All Contributors](https://github.com/marshmallow-packages/priceable/contributors)

## License

The MIT License. Please see the [License File](LICENSE) for more information.
