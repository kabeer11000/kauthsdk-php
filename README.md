## Kabeers Auth SDK.

Add Kabeers Network Authorization System and Account Chooser inside your PHP app with our PHP SDK.
View A Demo at [Kabeers Network Auth Site](http://auth.kabeersnetwork.rf.gd/server/account/).



```php
// New KAuth Instance
$kauth = new KAuth();


// Initalize KAuth SDK
$kauth->init(
    '[CLIENT PUBLIC]',
    '[CLIENT SECRET]',
    /* Save Refresh Token Directory */ './', 
    /* Verify OAuth State */ true
);

// [OPTIONAL] Create Auth URI to use Render and Redirect Methods
$kauth->createAuthURI(
    [/* Claims */ 'p6rouHTvGJJCn9OuUNTZRfuaCnwc6:files'],
    /* Callback URL */ 'https://yourdomain.com/callback',
    /* OAuth State */ uniqid(),
    /* OAuth Response Type */ 'code'
);

// Echo A Kabeers Auth Button
echo $kauth->render( 
    /* Height */ '5rem', 
    /* Width */ 'auto', 
    /* Theme, Light Or Dark */ 'dark'
);


// Or Redirect Directly To Auth URL
$kauth->redirect();


// Other Useful Methods

$kauth->getToken('[Key']); // Get From Saved Storage
$kauth->saveToken('[Key]', '[Value]'); // Save To Storage
$kauth->deleteToken('[Key]') // Delete From Saved Storage

```

## Example Way to Parse Callback

```
if ($kauth->tokens) {
    foreach ($kauth->tokens as $token) {
        foreach ($token as $t => $value) {
            if (array_search($value, $token) === '[Public ID of API You Want]') {

                $info = $token[array_search($value, $token)]; // Search Token Array for Value
                $refresh_token = $kauth->refreshToken($info['refresh_token']); // Refresh Token For API
                
                
                $user_info = $kauth->getUserInfo($info['access_token']);
                // Get User Info From K Auth User Info EndPoint. To Use it Public API Claim Should be [AStroWorld_Cn9OuUNTZRfuaCnwc6]
                
                
                $kauth->saveToken('Example Key', $info['refresh_token']); // Save Refresh Token To Use Later!
                break;
            }
        }
    }
}

````

Include Remotely or Localy from

```
https://raw.githubusercontent.com/kabeer11000/kauthsdk-php/master/dist/k-authsdk.php
```

Looking for JavaScript SDK? [Click Here](https://kabeer11000.github.io/kauthsdk-js/)

Having trouble with Above Docs? Check out our [documentation](http://kabeersnetwork.dx.am/apis#item-14-4)
