<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();
        $this->load->model('contact');

    }

    public function index()
    {
        
        $result = $this->contact->read();
        
        $this->show($result);
    }

    public function id($id = null)
    {

        $result = $this->contact->read($id);
        
        $this->show($result);
    }

    public function create()
    {
        
        $data = $this->get_post();

        $result = $this->contact->create($data);

        $this->respond($result);
            
    }

    public function update()
    {

        $data = $this->get_post();
        $result = $this->contact->update($data);

        $this->respond($result);
    }

    public function delete($id = null)
    {

        $result = $this->contact->delete($id);

        $this->respond($result);
    }
    
    // show result
    private function show($result)
    {
        if ($result === null)
        {

            echo 'Data not found';
        }

        $contacts = array();

        foreach ($result as $row)
        {
            $temp = array(

                "contact_id" => $row['contact_id'],
                "address_id" => $row['address_id'],
                "name_id" => $row['name_id'],
                "personal_id" => $row['personal_id'],
                "email" => $row['email'],
                "phone" => $row['phone'],
                "mobile" => $row['mobile'],
                "prefix" => $row['prefix'],
                "first_name" => $row['first_name'],
                "mid_name" => $row['mid_name'],
                "last_name" => $row['last_name'],
                "suffix" => $row['suffix'],
                "nickname" => $row['nickname'],
                "street" => $row['street'],
                "street_2" => $row['street_2'],
                "city" => $row['city'],
                "province" => $row['province'],
                "country" => $row['country'],
                "postal" => $row['postal'],
                "birthday" => $row['birthday'],
                "job_title" => $row['job_title'],
                "department" => $row['department'],
                "company" => $row['company'],
                "website" => $row['website'],
                "note" => $row['note']
            );

            array_push($contacts, $temp);
        }

        echo json_encode($contacts);
    }

    // get post data
    private function get_post()
    {

        if (isset($_POST))
        {
            $data = json_decode(file_get_contents("php://input", true), false, 512, JSON_BIGINT_AS_STRING);

            $address = array(

                "address_id" => $data->address_id,
                "street" => $data->street,
                "street_2" => $data->street_2,
                "city" => $data->city,
                'province' => $data->province,
                "country" => $data->country,
                "postal" => $data->postal
            );

            $name = array(
                
                "name_id" => $data->name_id,
                "prefix" => $data->prefix,
                "first_name" => $data->first_name,
                "mid_name" => $data->mid_name,
                "last_name" => $data->last_name,
                "suffix" => $data->suffix,
                "nickname" => $data->nickname
            );

            $personal = array(
                
                "personal_id" => $data->personal_id,
                "birthday" => $data->birthday,
                "job_title" => $data->job,
                "department" => $data->department,
                "company" => $data->company,
                "website" => $data->website,
                "note" => $data->note
            );

            $contact = array(

                "contact_id" => $data->contact_id,
                "address_id" => $data->address_id,
                "name_id" => $data->name_id,
                "personal_id" => $data->personal_id,
                "phone" => $data->phone,
                "email" => $data->email,
                "mobile" => $data->mobile
            );

            $result = array(
                "address" => $address,
                "name" => $name,
                "personal" => $personal,
                "contact" => $contact
            );
            
        } else {

            echo 'sorry, we cannot process your request';
        }

        unset($_POST);

        return $result;
    }

    private function respond($result)
    {

        if ($result)
        {
            echo 'success';
        
        } else {

            echo 'failed';
        }
    }
}