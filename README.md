# Freebox OS API

First, create an HTTP json service.
You can use the default or create your own that implements iJsonHttpRequest:
```php
$http = new DefaultCurlRequest("mafreebox.free.fr");
```

then obtains your session token with the application token (see samples for how to obtains app token)
```php
$authService = new FreeboxOsAuthService($http);
$appToken = file_get_contents(__ROOT__."/appToken.txt");//For sample propose
$challenge = $authService->login()->challenge;
$sessionToken = $authService->openSession(APP_ID, $appToken,$challenge )->session_token;
```

finally, call the needed API :

```php
$downloadService = new FreeboxOsDownloadService($http, $sessionToken);
$downloadService->setMode(THROTTLING_MODE_NORMAL);
```