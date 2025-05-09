<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/* API */
$route['product'] = 'api/Product';
$route['product/(:any)'] = 'api/Product/$1';
$route['product/(:num)']['PUT'] = 'api/Product/$1';
$route['product/(:num)']['DELETE'] = 'api/Product/$1';
$route['api/appointments/(:num)/cancel']['patch'] = 'api/appointments/cancel/$1';
$route['api/appointments/(:num)/complete']['patch'] = 'api/appointments/complete/$1';
$route['api/medical-records/(:any)'] = 'api/MedicalRecords/$1';
$route['api/medical-records'] = 'api/MedicalRecords';
$route['api/invoices/(:num)/cancel']['patch'] = 'api/invoices/cancel/$1';
$route['api/invoices/(:num)/paid']['patch'] = 'api/invoices/paid/$1';
$route['api/inpatients/(:num)/discharge']['patch'] = 'api/inpatients/discharge/$1';

// API REPORT/Dashboard
$route['api/reports/patient-visits'] = 'api/reports/patient_visits';
$route['api/reports/patient-visit-department'] = 'api/reports/patient_visit_department';
$route['api/reports/top-diagnoses'] = 'api/reports/top_diagnoses';
$route['api/reports/inpatient-capacity'] = 'api/reports/inpatient_capacity';
$route['api/reports/patient-new-vs-returning'] = 'api/reports/patient_new_vs_returning';

// $route['register'] = 'api/User/register';
// $route['login'] = 'api/User/login';
// $route['logout'] = 'api/User/logout';
// $route['reGenToken'] = 'api/Token/reGenToken';

// dashboard
$route['dashboard/(:num)'] = 'dashboard/index/$1';