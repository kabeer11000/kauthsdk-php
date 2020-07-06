## Kabeers Auth SDK.

Add Kabeers Network Authorization System and Account Chooser inside your PHP app with our PHP SDK.
View A Demo at [Kabeers Network Auth Site](http://auth.kabeersnetwork.rf.gd/server/account/).



```$ob = new KAuth();
//Sample Website to recieve Auth Callback
$redirect = 'http://auth.example.com/login/';
$id = '6567948';
$method = 'login';
//INIT KAuth SDK
$ob->init($redirect, $id, $method);
//Render KAuth Button
$ob->render('5rem', 'auto');
//Or Directly Redirect To Auth Url without Button Click
$ob->go();
```<br/><br/>


Having trouble with Pages? Check out our [documentation](https://help.github.com/categories/github-pages-basics/) or [contact support](https://github.com/contact) and we’ll help you sort it out.
