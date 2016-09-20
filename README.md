# OTPKit

This package is part of Fruit Framework.

OTPKit is still under development, not usable now.

## Synopsis

```php
$otp = new TOTP('10 bytes binary string', 3);
if ($otp->validate($_POST['otp_pass'])) {
    // authorized!
}
```

## License

Any version of MIT, GPL or LGPL.
