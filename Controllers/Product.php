<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\productModel;


class Product extends ResourceController
{
    use ResponseTrait;

    // Get All Records 
    public function getproducts()
    {
        try {
            $model = new productModel();

            $data = $model->findAll();

            return $this->respond($data, 200);
        } catch (\Throwable $th) {

            throw $th;
        }
    }

    // Get Single Records
    public function getproduct($id = null)
    {

        try {
            $model = new productModel();

            $data = $model->getWhere(['product_id' => $id])->getResult();

            if ($data) {
                return $this->respond($data);
            } else {
                return $this->failNotFound('Product Not Found id = ' . $id);
            }
        } catch (\Throwable $th) {

            throw $th;
        }
    }

    //create new product

    public function createproduct()
    {
        $model = new productModel();

        $data = [
            'product_name' => $this->request->getPost('product_name'),
            'product_price' => $this->request->getPost('product_price')
        ];

        $data = json_decode(file_get_contents('php://input'));

        $response = [
            'status' => 201,
            'message' => [
                'success' => 'Data insertedd Successfully'
            ]
        ];

        $model->insert($data);
    }

    // update products

    public function updateproduct($id = null)
    {
        $model = new productModel();

        $json = $this->request->getJSON();

        if($json){
            $data = [
                'product_name' => $this->request->getPost('product_name'),
                'product_price' => $this->request->getPost('product_price')
            ];
        }else{
            $input = $this->request->getRawInput();


            $data = [
                'product_name' => $input['product_name'],
                'product_price' => $input['product_price']
            ];
        }

        $model->update(
            $id,
            $data
        );

        $response = [
            'status' => 200,
            'message' => [
                'success' => 'product updated successfully'
            ]
        ];

        return $this->respond($response);
    }

    //delete product

    public function deleteproduct($id = null)
    {
        $model = new productModel();

        $data = $model->find($id);

        if($data)
        {
            $model->delete($id);

            $response = [
                'status' => 200,
                'message' => [
                    'success' => 'Deleted Successfully'
                ]
            ];

            return $this->respond($response);

        }else{
            
            return $this->failNotFound("user id not found");
        }
    }
}
