<h2><h2>Title</h2><br />
Lost Pet Connect
<p>
  <h2>Description</h2><p>
Lost Pet Conenct was created for pet owners to give their pets a simple form of identification which would aide a 'locator' in returning them to their owner. Lost Pet Connect utilizes QR codes as an ID.
<p>
2022 stats. 86% of the global population own smartphones.
<p>
Most phones are equipped with cameras and software that was designed to read QR codes for their data automatically. Having an ID increases the likelihood of successful return of a lost or missing pet. Most pets that go missing are never re-united with their owners due to the lack of contact information.
<p>
  <h2>How it works</h2>
Each pet that is inserted in the database is given a QR code and a personalized web page which contains contact details. Once the QR code is scanned, the 'locator' is directed to the personal contact page. Since privacy is a big concern, pet owners phone numbers are not directly given on the webpage and communication works through a "proxy" phone number. Calls and text messages work rely on use of SignalWire. The contact page gives a real time layer of protection as it can be modified any time necessary by the owner.
----------------


 <h2>Requirements</h2><br />
	<strong>(Server)</strong><br />
Apache webserver<br />
PHP (8 and up)<br />
MySQL<br />
allow_url_fopen = enabled<br />
Fileinfo (PHP) extension<br />
<p>
<strong>(Phone number)</strong><br />
Purchased phone number from SignalWire:<br />
*Local and toll numbers are compatible.<br />


  <h2>Installation</h2><br />
Upload files:<br />
Upload all files and directories that are located in the compressed file to the root directory of your server. Code adjustments<br /> may be necessary if software is installed in a subdirectory.
<p>
	
  <strong>Chmod the following directories</strong>:<br />
'images/images' [757]<br />
(User uploaded image directory)<br />


Pet ID/QR code location:<br />
'images/qr' [757]<br />
(QR code storage directory)
<p>
  
Other:<br />
A MySQL connection file (connection.php) is automatically created in the root directory and an .htaccess file <br />is automatically created in the 'view' directory by the software during installation. Make sure applicable write permissions are configured.


  <h3>After upload is complete</h3>:<br />
Using your web browser, navigate to the 'install' (http://domain.com/install) folder on your server.<br />The installation wizard will guide you through a 3-page setup and install process.

1. Configure Admin account
2. Configure Website settings
	You'll need a SignalWire phone number to use as your 'proxy phone number'. This software is compatible with local and toll free mumbers. This number conceals both parties contact details for privacy reasons.
If you don't own one during setup you can insert a placeholder such as '00000000000' and edit the phone number at a later time..
3. Configure MySQL database

  
  
 [Edit these sections from within your SignalWire account]

Edit the phone number

Voice and Fax Settings
Handle calls using>LaML Webhooks>When a call comes in>https://domain.com/call.php


Messaging Settings>
Handle messages using>LaML Webhooks>https://domain.com/comm/sms.php
  
  
Note:
After setup is complete you should remove the 'install' directory from your server.

Access your Admin account:
Navigate to the admin directory on your server (https://domain.com/admin) using your web browser. Use the Administrator account credentials that you entered during installation.

User registration
https://domain.com/registration



Setup pages



How to use


----------------------
References
google devs

license
