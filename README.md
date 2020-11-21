[![Build Status](https://travis-ci.com/aashan10/magento2-link-guest-order.svg?branch=master)](https://travis-ci.com/aashan10/magento2-link-guest-order)
## Link Guest Customer

- [ Overview ](#overview)
- [ Installation ](#overview) 
  - [ Composer Installation ](#composer-installation)
  - [ Manual Installation ](#manual-installation)
  - [ Install via Git ](#install-via-git) 
- [ API ](#api) 
- [ Contributing ](#contributing)

### Overview
By default, Magento 2 doesn't automatically link the guest orders as customer orders if the account with email supplied during the checkout process already exists. That is where this module comes in handy. This module automatically synchronizes the orders on the basis of order email address. You can even sync previously added orders through the admin panel.

![Image](https://i.imgur.com/fkJATvr.png)
![Image](https://i.imgur.com/c0XHGuQ.png)
![Image](https://i.imgur.com/lVJrN4V.png)

### Installation 
- #### Composer Installation
	Inside your project root directory, run `composer require aashan/module-link-guest-orders`. 

	Make sure to run post installation scripts.
- #### Manual Installation
	This module can be installed by downloading the latest release and extracting the files under `<your project root>/app/code/Aashan/LinkGuestOrder`. Once the files have been extracted, run the post installation scripts.

- #### Install via Git
	To install it via git, follow the following process.
	- `cd <your project directory> `
	- `mkdir app/code/Aashan/LinkGuestOrder && cd app/code/Aashan/LinkGuestOrder`
	- `git init`
	- `git remote add origin https://github.com/aashan10/magento2-link-guest-order.git` 
	- `git pull origin master`

	Once the installation is complete, please follow post installation scripts.
- #### Post Installation
    Once you have your module installed by one of the above methods, run the following commands to make sure that the module is setup correctly.
    - `bin/magento setup:upgrade`
    - `bin/magento cache:clean`
### API
The module provides a simple API for auto linking the orders to their respective customers. 
The main API is available through `Aashan\LinkGuestOrder\Helper\OrderLinkHelper` helper class. It consists of a method `linkOrderToCustomer` which takes object of `Magento\Sales\Model\Order` class as the only argument.

Below is an example on using the API.

```php
<?php 
...

use Aashan\LinkGuestOrder\Helper\OrderLinkHelper;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
...

public function __constructor(
	...,
	OrderLinkHelper $helper,
	CollectionFactory $factory,
	...
){
	$this->helper = $helper;
	$this->factory = $factory;
};

...
...

public function linkOrderById($orderId){
	$order = $this->factory
				->create()
				->addFieldToFilter(
					'entity_id', 
					$orderId
				)
				->getFirstItem();
	$this->helper->linkOrderToCustomer($order);
}
...

``` 

### Contributing
Follow the Github docs for contribution guide given [here](https://github.com/github/docs/blob/main/CONTRIBUTING.md).
