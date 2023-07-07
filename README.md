# LostPetConnect




About Lost Pet Connect
----------------------

Lost Pet Connect was developed to give others a way to create an identification for pets that, that can be universally read by others and to provide up-to-date contact information for pets that may become misplaced.

Over 86% of the global population own a smartphone which have the ability to scan QR codes.
Lost Pet Connect utilizes QR codes as a form of ID. If a Pets QR code is scanned, the locator is presented with a webpage which contains personal information about the pet and its owner, including phone number and home location.


How it Works
------------
• All calls are routed using a single phone number.<br />
• Each pet is given a unique ID, QR code and personal profile upon registration.<br />
• When scanned, the QR code will direct the locator to a profile featuring information about the Pet and the Pet Owner. The Pet Owners personal phone number is   masked by a SignalWire phone number.<br />

• Communication

  - Phone calls:
  A 6-digit call code can be generated on the Pets profile page. This code allows for the Pet Owner to be called.
  Callers are placed into a waiting queue as they await the anwer from the Pet Owner. If the Owner doesn't answer then the caller is notified and the call is   diconnected.

  - Outgoing calls (Owner to Locator):
  Outgoing calls can be placed only to Locators who have placed an incoming call or pings the Pet Owner via text message.
  After the Pet Owner clicks, the "Call out" action, a call is placed to their phone number where they will be given the option to decline the call before being   connected with the locator.

  - Text messages:
  Since a single phone number is used, text messages act as a ping which relays only contact information from the locator. Direct message exchange is not     available.

• QR Code generation
   - QR codes are generated using Google API. This code is free to use 

   - Google API DISCLAIMER
   "The QR codes generated on this website/application are created using the Google API with proper authorization and permission. We acknowledge     that the Google API is utilized for the purpose of generating QR codes and comply with all applicable terms of service and usage policies set forth by Google. Please note that the Google API usage is subject to Google's terms and conditions, and we are not affiliated with or endorsed by Google."




Install
-------

*NOTE*: The 'Docker' folder is for use on a Docker setup only (environmental variable code adjustments specific).
For a standard server simply install as normal with the exception of using the 'Docker' folder.


1. Upload all files and directories contained within the archive to the home directory of your server.
CHMOD: 'images/images/' and 'images/qr' should be given public write permissions as these directories will contain user uploaded and generated images pertaining to their Pets.

2. Using your web browser navigate to the 'install' directory.

3. The Installation Wizard will guide you through the setup process.
Note: You will need MySQL server and SignalWire credentials to complete setup.

4. After setup is complete and you should delete the 'install' directory for security reasons.

If the Installation Wizard does not successfully complete (usually due to incorrect DB info) you may simply drop all tables associated with the installation and start-over.

**Additional notes:**<br />
A.  (Non Docker version)<br />
Open: The 'view' directory and rename the file 'htaccess.txt' to '.htaccess'.<p>

B.<br />
A call to Google API to generate a QR code is made each time a user registers a pet to their account. Make sure your servers settings are set appropriately.<br />
(function: file_get_contents("https://chart.googleapis.com/chart")...)<p>
5.<br />
Phone Number Settings<br />

<b>Edit these settings from within your SignalWire account</b>

[VOICE AND FAX SETTINGS]
------------------------<br />
Replace the domain with an appropriate domain or IP address.

[*HANDLE CALLS USING]<br />
Select: -LaML WebHooks<p>

[*WHEN A CALL COMES IN]<br />
http://domain.com/comm/call.php<p>

[*STATUS CHANGE WEBHOOK]<br />
http://domain.com/comm/status.php<p>

MESSAGING SETTINGS<br />
------------------<p>

[*When A Message Comes In]<br />
http://domain.com/comm/sms.php<p>


Website Access<br />
--------------<br />

Install Directory<br />
domain.com/install<p>

Admin Login<br />
domain.com/admin<p>

User Registration<br />
domain.com/registration<p>

User Login<br />
domain.com/members
