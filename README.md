# LPFC-router
A simple router for Light PHP Framework based on Composer (LPFC), which is under the [MIT License](http://mit-license.org)  

install by composer

+ Remember to use `Route::dispatch();` after all routes registered

+ main referrence: NoahBuscher's [Macaw](https://github.com/NoahBuscher/Macaw)

---   

## Quick Start  

### Install  

+ Install by composer (recommended)  

Edit your `composer.json`, add *require* like:  

		"require": {
			"phonicavi/lpfc-router": "dev-master"
		}

then run composer-update under the root directory:  

> composer update

All finished..

+ Install by code  

Please download the file package and unzip it,  
move them to `./vendor/phonicavi/lpfc-router/` in which `./` is just the root directory of your PHP works.  


### Namespace  

add that below in your route-configuration file:  

		use LPFCRouter\Hermes\Route;

since the namespace `LPFCRouter\\Hermes\\` defined by *lpfc-router*  


### Use Method  

Since native function `__callstatic()` used, you can register your routes with simple forms like  
        
        Route::get() 
        
or  
        
        Route::post()  



***more details need to be added***

