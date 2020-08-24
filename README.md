## Kabeers Auth SDK.

Add Kabeers Network Authorization System and Account Chooser inside your PHP app with our PHP SDK.

View A Demo at [Kabeers Network Auth Site](http://auth.kabeersnetwork.rf.gd/server/account/).

### Supports
- Oauth Implict Grant Type
- Oauth Authorization Code Grant Type
- OIDC Id Token Grant
- Oauth Refresh Tokens
- Saving, Deleting, Retriving Tokens For Later Use
- Rendering Fedrated Sign in Buttons
- Directly Redirecting To Auth URI
- Handling and Parsing Oauth2 Callback
- Natively Supports calling 'userinfo' endpoint & retriving User Info & id
- Generating & verifying Secure OIDC 'nonce' and oauth 'state' parameters
- Session Based State Verification To Protect against CSRF attacks
- Creating Oauth URI with callback
- Creating Auth URI with multiple Oauth Scopes


### Code Example

```php
//Include The SDK
include 'k-authsdk.php';
use Kabeers\KAuth;


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
    [/* Scopes */ 'p6rouHTvGJJCn9OuUNTZRfuaCnwc6:files'],
    /* Callback URL */ 'https://yourdomain.com/callback',
    /* OAuth State Uniqid Or Leave As '' */ uniqid(),
    /* Optional OAuth Response Type "code" OR "token" */ 'code',
    /* Optional Prompt Type */ 'consent',
    /* Optional State and OIDC Nonce Length */ 8
);

// Echo A Kabeers Auth Button
echo $kauth->render( 
    /* Height */ '5rem', 
    /* Width */ 'auto', 
    /* Theme, Light Or Dark */ 'dark'
);


// Or Redirect Directly To Auth URL
$kauth->redirect();


```

## Saving, Retriving and Deleting Tokens

```php
$kauth->getToken('[Key']); // Get From Saved Storage
$kauth->saveToken('[Key]', '[Value]'); // Save To Storage
$kauth->deleteToken('[Key]') // Delete From Saved Storage

```

## Getting User Info

Natively hit userinfo endpoint and get User Info
```php

echo getUserInfo(['access_token'])

```

## Example Way to Parse Callback

```php
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
