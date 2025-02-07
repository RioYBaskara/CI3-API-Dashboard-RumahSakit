<?php defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------
| JWT Secure Key
|--------------------------------------------------------------------------
*/
$config['jwt_key'] = '361c95a35dc376eb75f78c038cdc01cce48766cde55e49fa884ad8fc2afa9c998c95dcef0bffb0180f8fd42ab45536df8f021d322e4d20de1e27c2ff5a3eb5c3162c5463a3fda934b444d76e62095620bdb9972f11276b84e79e6fad6d86d3fea225ae4023aa8811a0b2ab221deb7bc190cee69326576db86c58df8bd6cd78bc32eb67dec8fa307148a2992679756d9f26c4b3ebb174d73f1420bb9857c22760ad8e606bc616537790c1b5e4564a653a0f4f15b7cc2599e3ac96be90cdddc569e3c5fac1ecc1a12880765c81296f08d5f64eb4c6a6113a15fe07b982648c5d3e7e0e2ff0d49e375e757f27ed401af4e48a630879300a7e601a2330d952509abf';


/*
|-----------------------
| JWT Algorithm Type
|--------------------------------------------------------------------------
*/
$config['jwt_algorithm'] = 'HS256';


/*
|-----------------------
| Token Request Header Name
|--------------------------------------------------------------------------
*/
$config['token_header'] = 'authorization';


/*
|-----------------------
| Token Expire Time

| https://www.tools4noobs.com/online_tools/hh_mm_ss_to_seconds/
|--------------------------------------------------------------------------
| ( 1 Day ) : 60 * 60 * 24 = 86400
| ( 1 Hour ) : 60 * 60     = 3600
| ( 1 Minute ) : 60        = 60
*/
$config['token_expire_time'] = 86400;