minime
======

Fun project to make a real framework based on twittee container. (http://twittee.org/)


TL;DR;
======

HowTo

1. use composer to get "yarooze/minime" from the github (and whatever modules you need)

2. Copy files from the "skeleton" dir into your project root dir

3. Overwrite base classes in "/src/App/" as you wish.

4. set up your stuff in "src/app.php" and configure in "/config/*"

5. For CLI use something like "internal/service.php" for web - "web/index.php"

6. Enjoy!


More info
======


## Project structure
(This is only a brief description, for more information look into the code) Minime is a typical MVC framework, so the code is separated to:
1. `Controllers` - have the `actions` which are called if the route is matched. The action prepares the data and calls `View` for the presentation.
2.  `Core` - are the core parts of the framework:  
2.1 `Config` - loads the config from the `config/config_app.php` and is always available from the `$app->config`  
2.2 `Flasher` - manages the flash messages  
2.3 `HttpCache` - sends `HTTP/1.1 304 Not Modified` if needed  
2.4 `I18n` - internationalization service  
2.5 `Logger` - log service  
2.6 `PDO` - data base connection service  
2.7 `Request` - request data is here  
2.8 `Router` - reads routes from the `config/routing.php` and manages the whole routing stuff of the app  
2.9 `Session` - user session is here
3. `Exception` - custom exceptions are here  
4. `Form` - minime forms  
5. `Helper` - helpers with additional functions
6. `Model` - the `model` part of the MVC. The entities classes are here   
7. `Security` - all kinds of authentication and security stuff are here  
8. `Service` - other services  
9. `Templates` - view and partial templates
10. `View` - to prepare the headers and render the templates 

This is a usual `mimime` structure
```
garampel
├───bin  <-- console tools like .bat or .sh files  
├───cache  <-- cache directory
├───config  <-- app configs are here. The most important are `routing.php` and `config_app.php`
├───i18n  <-- translation files like `en.php` for the I18n service. 
├───internal  <-- entry point to use your app as internal service
├───log  <-- internal log directory if you don't use global logs directory
├───src  <-- your app is here
│   └───App
│       ├───Controller
│       ├───Core
│       ├───Exception
│       ├───Form
│       ├───Helper
│       ├───Model
│       ├───Security
│       ├───Service
│       ├───Templates
│       └───View
├───vendor  <-- vendor stuff (usually installed by composer)
└───web <-- apache DocumentRoot is here
    └───assets
        ├───css
        ├───img
        └───js
```

## Usage
There are two ways to use the application. `internal` and `web`.  

### `Internal` 
1. include `PATH/internal/service.php` in your another project  
2. call `$yourMinimeApp = getMiniApp();`   
3. now you can use the app services like `$yourMinimeApp->someService->getDataByName('some-id')`

### `Web`
1. Configure everything in the `config/config_app.php`. Also set the environment (`env`) there to `dev` or `prod`.   
2. Make `web` to your apache project root directory. (rewrite roles will send all request to index.php)
3. `web/index.php` will call the `src/bootstrap.php` and `src/app.php`  
4. `src/app.php` loads the services and starts the application
5. After everything is prepared (services are loaded route and request are parsed, session is loaded, user credentials
 are checked) the matched controller action will be called.  
6. Controller action will do some action stuff and then either make the redirect or put the data into view and call `View::render()`  
7. View sends the headers and then renders the template. 

