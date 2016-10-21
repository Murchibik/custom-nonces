# custom-nonces
Custom Nonce is a library for creating and checking "nonces" (simple hashes with the limited lifetime) to avoid replacing original url.

## Installation
To install, add code below in your **composer.json**:
```
{
  "require" : {
    "php" : ">=5.3.0",
    "murchibik/custom-nonces" : "dev-master"
  },
  "repositories":[
    {
      "type":"git",
      "url":"http://github.com/Murchibik/custom-nonces"
    }
  ]
}
```
and run `composer update` from console.

## Get nonce
To create nonce, you should build object `CustomNonce` with non empty *secret key* string (it can be session id or random string) and transmit needed string to function `createNonce` as an argument.
In return you get string with nonce of incoming string.

If you need nonce as an url parameter, you can use function `createNonceForUrl`. The function returns string with the url part like **custom-nonce=XXXXXXXXXXXXX** where XXXXXXXXXXXXX is the nonce.

For using custom label you can use function `setCustomLabel` with an argument **customLabel** before call `createNonceForUrl`. This label will replace *custom-nonce* label. 

## Check existed nonce
To check, separately transmit url and nonce into function `checkNonce`. In return you get array of two elements:
 * status - boolean *true*, if checking didn't find any changes in string or *false*, if something is incorrect.
 * errors - empty string if status is *true* or explanation of error if status is *false*.
 
## Testing
The library uses Codeception for unit tests. To enable it, update Composer in dev mode like this (in console): 

`composer update --dev`

and then

`codecept bootstrap`

it added in in your root folder file **codeception.yml** and folder **tests**, so then for running tests just type in console this:

`codecept run`