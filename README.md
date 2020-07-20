## Kabeers Auth SDK.

Add Kabeers Network Authorization System and Account Chooser inside your PHP app with our PHP SDK.
View A Demo at [Kabeers Network Auth Site](http://auth.kabeersnetwork.rf.gd/server/account/).



```
//Sample Website to recieve Auth Callback
$redirect = 'http://auth.example.com/login/';
$id = '6567948';
$method = 'login';


//New KAuth Instance
$ob = new KAuth();

//INIT KAuth SDK
$ob->init($redirect, $id, $method);


//Render KAuth Button
$ob->render('5rem', 'auto', 'dark');


//Or Directly Redirect To Auth Url without Button Click
$ob->go();
````

Include Remotely or Localy from
```https://raw.githubusercontent.com/kabeer11000/k-auth-sdk/master/dist/k-authsdk.php```
Looking for JavaScript SDK? [Click Here](https://github.com/kabeer11000/kauthsdk-js/)

Having trouble with Above Docs? Check out our [documentation](http://kabeersnetwork.dx.am/apis#item-14-4)
