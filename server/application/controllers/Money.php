<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

header('Access-Control-Allow-Origin: *');  

header('Access-Control-Allow-Headers: accept, authorization,x-requested-with'); 

class money extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Money_model');
    }

    public function index_options()
    {
        $this->response(200);
    }

    public function index_get()
    {

        $header = $this->input->get_request_header('Authorization', TRUE);

        $token = JWT::decode($header, $this->config->item('jwt_key'));

        if(!$token){
            $this->response(403);
        }

        $balance = $this->Money_model->checkBalance($token->userid);

        $this->response($balance, 200);
    }

    public function index_post()
    {
        $header = $this->input->get_request_header('Authorization', TRUE);

        $token = JWT::decode($header, $this->config->item('jwt_key'));

        if(!$token){
            $this->response(403);
        }

        $amount = $this->post('amount');
        $to = $this->post('to');
        $from = $token->userid;

        $this->Money_model->sendMoney($amount,$from,$to);

        // $this->response(200);


    }

}