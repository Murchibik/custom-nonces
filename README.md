# custom-nonces
Custom Nonce is a library for creating and checking "nonces" (simple hashes with the limited lifetime) to avoid replacing original url.

## Get nonce
For creating nonce you should create object `CustomNonce` and transmit string to function `createNonce` as argument.
In return you get string with nonce of incoming string.
If you need nonce as url parameter, you can use function `createNonceForUrl`. It has two arguments string for creating nonce and optional argument **customLabel**. 
It returns string with url part like **custom-nonce=XXXXXXXXXXXXX** where XXXXXXXXXXXXX is nonce. If was transmitted optional argument - it would be shown instead *custom-nonce* 

## Check existed nonce
For checking you should transmit url and nonce separately into function `checkNonce`. In return you get array of two elements:
 * status - boolean *true* if checking didn't find any changes in string or *false* if something was incorrect.
 * errors - empty string if status is *true* or explanation of error if status is *false*.