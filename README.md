![alt text](https://cdn.marshmallow-office.com/media/images/logo/marshmallow.transparent.red.png "marshmallow.")

# Priceable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/marshmallow/priceable.svg?style=flat-square)](https://packagist.org/packages/marshmallow/priceable)
[![Total Downloads](https://img.shields.io/packagist/dt/marshmallow/priceable.svg?style=flat-square)](https://packagist.org/packages/marshmallow/priceable)

Package for handling prices. Priceable lets you attach one or more prices to any
Eloquent model through a polymorphic relationship, with built-in support for VAT
rates, currencies, price types and a Laravel Nova integration. It is typically
used together with Marshmallow's Cart or Ecommerce packages.

## Installation

Install the package via Composer:

```bash
composer require marshmallow/priceable
```

The service provider is auto-discovered. It registers the package routes and
loads the migrations automatically, so run them after installing:

```bash
php artisan migrate
```

Publish the config file:

```bash
php artisan vendor:publish --provider="Marshmallow\Priceable\PriceableServiceProvider" --tag="config" --force
```

### Seed currencies and VAT rates

The package ships seeders with a set of default currencies and VAT rates:

```bash
php artisan db:seed --class="Marshmallow\Priceable\Seeders\CurrencySeeder"
php artisan db:seed --class="Marshmallow\Priceable\Seeders\VatRatesSeeder"
```

## Configuration

The config file is published to `config/priceable.php`. The available keys are:

| Key | Default | Description |
| --- | --- | --- |
| `detault_price_type` | `1` | ID of the price type used when none is specified. |
| `currency` | `env('CURRENCY', env('CASHIER_CURRENCY', 'eur'))` | Default currency code used for formatting amounts. |
| `currency_locale` | `env('CURRENCY_LOCALE', env('CASHIER_CURRENCY_LOCALE', 'nl'))` | Locale used to format monetary values. |
| `models` | `vat`, `price`, `currency`, `price_type` | Model classes used by the package. Override to swap in your own models. |
| `resources` | `vat`, `price`, `currency`, `price_type` | Nova resource classes for each model. |
| `nova.prices_are_including_vat` | `true` | Whether prices entered in Nova include VAT. |
| `nova.defaults.currencies` | `1` | Default currency ID for new prices. |
| `nova.defaults.vat_rates` | `2` | Default VAT rate ID for new prices. |
| `nova.resources` | `[ ... ]` | Priceable Nova resources so Nova knows where to look for them. |
| `on_multiple_prices` | `'lowest'` | Which price to use when a model has multiple: `highest`, `lowest`, `eldest` or `newest`. |
| `public_excluding_vat` | `env('PRICEABLE_PUBLIC_EXCLUDING_VAT', false)` | When `true`, public price methods return amounts excluding VAT. |
| `observers.price` | `PriceableObserver::class` | Observer applied to the `Price` model. |

You can set the default currency through your `.env` file:

```dotenv
CURRENCY=eur
```

## Usage

### Make a model priceable

Add the `Priceable` trait to any Eloquent model you want to attach prices to:

```php
use Illuminate\Database\Eloquent\Model;
use Marshmallow\Priceable\Traits\Priceable;

class Product extends Model
{
    use Priceable;
}
```

The trait adds a polymorphic `prices()` relationship and a number of helper
methods:

```php
$product->currentPrice();      // Current price for the active currency/price type
$product->discountedFrom();    // The highest price, e.g. the "from" price when discounted
$product->isDiscounted();      // True when the model has multiple, differing prices
$product->getHighestPrice();   // The highest available price
$product->hasPrice();          // True when at least one active price exists
$product->price;               // Price attribute accessor
$product->price_formatted;     // Formatted price string

// Scope to a specific price type:
$product->priceType($priceType)->currentPrice();
```

### Formatting a price

Each `Price` model exposes formatting and VAT helpers:

```php
$price->formatPrice();          // Formatted price (incl. or excl. VAT per config)
$price->includingVat();         // Amount including VAT
$price->excludingVat();         // Amount excluding VAT
$price->vat();                  // VAT amount
$price->formatIncludingVat();   // Formatted amount including VAT
$price->formatExcludingVat();   // Formatted amount excluding VAT
$price->formatVat();            // Formatted VAT amount
```

### The Price facade

The `Price` facade builds and formats price objects directly:

```php
use Marshmallow\Priceable\Facades\Price;

Price::make($vatRate, $currency, $displayAmount, $displayIsIncludingVat);
Price::formatAmount($amount);   // Formats an amount in cents to a currency string
```

### Currencies

The `Currency` model provides helpers for the currency the visitor is currently
using:

```php
use Marshmallow\Priceable\Models\Currency;

Currency::getUserCurrent();        // The visitor's current currency
Currency::getExceptUserCurrent();  // All other currencies
```

The package registers `set-currency/{currency}` (named `set-currency`) so users
can switch currency. It also adds `setUserCurrency()` and `getUserCurrency()`
macros to the request:

```blade
@foreach (\Marshmallow\Priceable\Models\Currency::get() as $currency)
    <a href="{{ route('set-currency', $currency) }}">
        {{ $currency->name }}
    </a>
@endforeach
```

### Nova

The package ships Nova resources for prices, currencies, VAT rates and price
types (configurable under the `resources` key). To attach prices to one of your
own Nova resources, use the `PriceableFields` helper to add the pivot fields to
your relationship.

## Testing

```bash
composer test
```

## Changelog

Please see the commit history for recent changes.

## Security Vulnerabilities

Please report security vulnerabilities to [stef@marshmallow.dev](mailto:stef@marshmallow.dev)
rather than via the public issue tracker.

## Credits

- [Stef](https://marshmallow.dev)
- [All Contributors](https://github.com/marshmallow-packages/priceable/contributors)

## License

The MIT License (MIT). Please see the [License File](LICENSE) for more information.
