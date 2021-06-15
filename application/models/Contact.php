<?php
defined('BASEPATH') OR exit('No direct access script allowed');

class Contact extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function read($id = null)
    {   
        $query = "SELECT
        name.name_id as name_id,
        IFNULL(name.prefix, '') as prefix,
        IFNULL(name.first_name, '') as first_name,
        IFNULL(name.mid_name, '') as mid_name,
        IFNULL(name.last_name, '') as last_name,
        IFNULL(name.suffix, '') as suffix,
        IFNULL(name.nickname, '') as nickname,
        address.address_id as address_id,
        IFNULL(address.street, '') as street,
        IFNULL(address.street_2, '') as street_2,
        IFNULL(address.city, '') as city,
        IFNULL(address.province, '') as province,
        IFNULL(address.country, '') as country,
        IFNULL(address.postal, '') as postal,
        contact.contact_id as contact_id,
        IFNULL(contact.mobile, '') as mobile,
        IFNULL(contact.phone, '') as phone,
        IFNULL(contact.email, '') as email,
        personal.personal_id as personal_id,
        IFNULL(personal.birthday, '') as birthday,
        IFNULL(personal.website, '') as website,
        IFNULL(personal.job_title, '') as job_title,
        IFNULL(personal.department, '') as department,
        IFNULL(personal.company, '') as company,
        IFNULL(personal.note, '') as note
        from contact
        left join address on contact.address_id = address.address_id
        left join name on contact.name_id = name.name_id
        left join personal on contact.personal_id = personal.personal_id";

        if ($id !== null)
        {
            $query = $query . ' where contact_id = ' . $id;
        
        }

        $query = $query . ' order by first_name asc';

        return $this->db->query($query)->result_array();

    }
    
    public function create($result)
    {
        
        $contact = $result['contact'];

        $this->db->insert('address', $result['address']);
        $this->db->insert('name', $result['name']);
        $this->db->insert('personal', $result['personal']);
        
        // query to insert record
        $query = "INSERT INTO contact(ADDRESS_ID, NAME_ID, PERSONAL_ID, EMAIL, PHONE, MOBILE) VALUES(
            (select address_id from address order by address_id desc limit 1), 
            (select name_id from name order by name_id desc limit 1), 
            (select personal_id from personal order by personal_id desc limit 1), ?, ?, ?)";

        
        return $this->db->query($query, array($contact['email'], $contact['phone'], $contact['mobile']));
    }

    public function update($result)
    {
        $this->db->replace('address', $result['address']);
        $this->db->replace('name', $result['name']);
        $this->db->replace('personal', $result['personal']);

        $result = $this->db->replace('contact', $result['contact']);
        
        return $result;
    }

    public function delete($id)
    {
        return $this->db->delete('contact', array('contact_id' => $id));
    }
}