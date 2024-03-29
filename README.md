Midtrans&nbsp; Easy Digital Downloads - Wordpress Payment Gateway Module
=====================================

Midtrans&nbsp; :heart: EDD!

A WordPress plugin that let your Easy-Digital-Downloads store integrated with Midtrans payment gateway.

Also [Available on Wordpress plugin store](https://wordpress.org/plugins/edd-midtrans-gateway/)

### Description

Midtrans payment gateway is an online payment gateway that is highly concerned with customer experience (UX). They strive to make payments simple for both the merchant and customers. With this plugin you can make your Easy Digital Downloads store integrated with Midtrans payment gateway.

Payment Method Feature:

* Credit card fullpayment and other payment methods.
* Bank transfer, internet banking for various banks
* Credit card Online & offline installment payment.
* Credit card BIN, bank transfer, and other channel promo payment.
* Custom expiry.
* Two-click & One-click feature.
* Midtrans Snap all payment method fullpayment.

#### Live Demo
Want to see Midtrans EDD payment plugins in action? We have some demo web-stores for EDD that you can use to try the payment journey directly, click the link below.
* [Midtrans CMS Demo Store](https://docs.midtrans.com/en/snap/with-plugins?id=midtrans-payment-plugin-live-demonstration)

### Installation

#### Minimum Requirements

* WordPress 3.9.1 or greater
* Easy Digital Downloads 2.0 or greater
* PHP version 5.4 or greater
* MySQL version 5.0 or greater
* PHP CURL enabled server/host

#### Simple Installation
1. Login to your Wordpress admin panel.
2. Go to `Plugins` menu, click `add new`. Search for `Midtrans-Easy-Digital-Downloads` plugin.
3. Install and follow on screen instructions.
4. Proceed to step **5** below.

#### Manual Instalation

The manual installation method involves downloading our feature-rich plugin and uploading it to your webserver via your favourite FTP application..

1. [Download](../../archive/master.zip) the plugin file to your computer and unzip it
2. Extract the plugin, then rename the folder modules as **edd-midtrans-gateway**
3. Using an FTP program, or your hosting control panel, upload the unzipped plugin folder to your WordPress installation `wp-content/plugins/` directory.
5. Install & then Activate **Midtrans Payment Gateway for Easy Digital Downloads** plugin from Plugin menu in your WordPress admin page.

#### Midtrans MAP Configuration
1. Login to your [Midtrans Account](https://dashboard.midtrans.com/), select your environment (sandbox/production), go to menu ***settings > configuration***
 * Insert `http://[YourWeb].com/?edd-listener=midtrans` link as the Payment Notification URL in your MAP configuration.
 * Insert `http://[YourWeb].com` (or your desired URL) link as Finish/Unfinish/Error Redirect URL in your MAP configuration.
  

#### Plugin Configuration
In order to configure Midtrans plug-in:

1. Access your WordPress admin page.
2. Go to **Downloads - Settings** menu in the WordPress admin page, click **Payment Gateways** tab.
3. Click **Save Changes**.
4. Click **Payment Gateways** section, that you click before i.e. **Midtrans** next to **General**
5. Input required fields below. (alternatively you may refer to image below) 
  * **Checkout Label** : \<text that will be shown when customers pick payment options\>
  * **Production Server Key**: \<your production server key\> (leave blank if you dont have production account)
  * **Production Client Key**: \<your production client key\> (leave blank if you dont have production account)
  * **Sandbox Server Key**: \<your sandbox server key\>  	
  * **Sandbox Client Key**: \<your sandbox client key\>
  * **Enable 3D Secure** : yes
6. Click **Save Changes**.

#### Get help

* Please follow [this step by step guide](https://docs.midtrans.com/en/snap/with-plugins?id=wordpress-edd-plugin-configuration) for complete configuration. If you have any feedback or request, please [do let us know here](https://docs.midtrans.com/en/snap/with-plugins?id=feedback-and-request).
* [Midtrans registration](https://dashboard.midtrans.com/register)
* [Midtrans documentation](http://docs.midtrans.com)
* [Midtrans SNAP Documentation](http://snap-docs.midtrans.com)
* Technical support [support@midtrans.com](mailto:support@midtrans.com)
