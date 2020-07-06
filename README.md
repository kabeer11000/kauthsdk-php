## Kabeers Auth SDK.

Add Kabeers Network Authorization System and Account Chooser inside your PHP app with our PHP SDK.
View A Demo at [Kabeers Network Auth Site](http://auth.kabeersnetwork.rf.gd/server/account/).



```$ob = new KAuth();<br/>
//Sample Website <br/>
$redirect = 'http://auth.example.com/login/';<br/>
$id = '6567948';<br/>
$method = 'login';<br/>
//INIT KAuth SDK<br/>
$ob->init($redirect, $id, $method);<br/>
//Render KAuth Button<br/>
$ob->render('5rem', 'auto');<br/>
//Or Directly Redirect To Auth Url without Button Click<br/>
$ob->go();<br/>
```<br/><br/>


Having trouble with Pages? Check out our [documentation](https://help.github.com/categories/github-pages-basics/) or [contact support](https://github.com/contact) and we’ll help you sort it out.
