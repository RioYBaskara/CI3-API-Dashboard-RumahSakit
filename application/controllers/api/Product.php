<?php

/* Table structure for table `products` */
// CREATE TABLE `products` (
//   `id` int(10) UNSIGNED NOT NULL,
//   `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
//   `price` double NOT NULL,
//   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
//   `updated_at` datetime DEFAULT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
// ALTER TABLE `products` ADD PRIMARY KEY (`id`);
// ALTER TABLE `products` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1; COMMIT;

/**
 * Product class.
 * 
 * @extends REST_Controller
 */
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Product extends REST_Controller
{

    /**
     * CONSTRUCTOR | LOAD MODEL
     *
     * @return Response
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Authorization_Token');
        $this->load->model('Product_model');
    }

    private function authenticate()
    {
        $decodedToken = $this->authorization_token->validateToken();

        if (!$decodedToken['status']) {
            $this->response([
                'status' => false,
                'status_code' => 401,
                'message' => 'Unauthorized',
                'error' => $decodedToken['message']
            ], REST_Controller::HTTP_UNAUTHORIZED);
            return false;
        }

        return $decodedToken['data'];
    }

    /**
     * SHOW | GET method.
     *
     * @return Response
     */
    public function index_get($id = 0)
    {
        if (!$this->authenticate())
            return;

        if (!empty($id)) {
            $product = $this->Product_model->show($id);

            if ($product) {
                $this->response([
                    'status' => true,
                    'message' => 'Product retrieved successfully',
                    'data' => $product
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Product not found',
                    'error' => 'No product exists with the given ID'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $products = $this->Product_model->show();

            $this->response([
                'status' => true,
                'message' => 'Products retrieved successfully',
                'data' => $products ?: []
            ], REST_Controller::HTTP_OK);
        }
    }


    /**
     * INSERT | POST method.
     *
     * @return Response
     */
    public function index_post()
    {
        if (!$this->authenticate())
            return;

        $input = $this->input->post();
        if (empty($input['name']) || empty($input['price'])) {
            $this->response([
                'status' => false,
                'message' => 'Bad Request',
                'error' => 'Name and price fields are required'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $insert_id = $this->Product_model->insert($input);

        if ($insert_id) {
            $this->response([
                'status' => true,
                'message' => 'Product created successfully',
                'data' => array_merge(['id' => $insert_id], $input)
            ], REST_Controller::HTTP_CREATED);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Internal Server Error',
                'error' => 'Failed to insert product'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * UPDATE | PUT method.
     *
     * @return Response
     */
    public function index_put($id)
    {
        if (!$this->authenticate())
            return;

        if (empty($id) || !is_numeric($id)) {
            $this->response([
                'status' => false,
                'message' => 'Bad Request',
                'error' => 'Invalid product ID'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $input = $this->put();
        if (empty($input['name']) || empty($input['price'])) {
            $this->response([
                'status' => false,
                'message' => 'Bad Request',
                'error' => 'Name and price fields are required'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $productExists = $this->Product_model->show($id);
        if (!$productExists) {
            $this->response([
                'status' => false,
                'message' => 'Not Found',
                'error' => 'Product not found'
            ], REST_Controller::HTTP_NOT_FOUND);
            return;
        }

        $updateStatus = $this->Product_model->update($input, $id);

        if ($updateStatus) {
            $this->response([
                'status' => true,
                'message' => 'Product updated successfully',
                'data' => array_merge(['id' => $id], $input)
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Internal Server Error',
                'error' => 'Failed to update product'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * DELETE method.
     *
     * @return Response
     */
    public function index_delete($id)
    {
        if (!$this->authenticate())
            return;

        if (empty($id) || !is_numeric($id)) {
            $this->response([
                'status' => false,
                'message' => 'Bad Request',
                'error' => 'Invalid product ID'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $productExists = $this->Product_model->show($id);
        if (!$productExists) {
            $this->response([
                'status' => false,
                'message' => 'Not Found',
                'error' => 'Product not found'
            ], REST_Controller::HTTP_NOT_FOUND);
            return;
        }

        $deleteStatus = $this->Product_model->delete($id);

        if ($deleteStatus) {
            $this->response([
                'status' => true,
                'message' => 'Product deleted successfully',
                'data' => ['id' => $id]
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Internal Server Error',
                'error' => 'Failed to delete product'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}