<?php defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------
| JWT Secure Key
|--------------------------------------------------------------------------
*/
$config['jwt_key'] = 'f52638c4ef756e01a1b6723f5d532a5c794d9764d609a639bc9a26c6d467f27a8fba9ef6624f79d1d8c0804ab153b6ca9807993accbbb1d64b2203dcfe5adf423ba454a42b6b4e6a8120eb6171ff4fc6ea7ba278322803bf0f94eef65e632808186b6962b70b7ca9558ff3b0b435a5f903c27c6429e5668b418ea59afe4354a3';


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