<?php
/**
 * Created by PhpStorm.
 * User: Kasun De Mel
 * Date: 2/6/2019
 * Time: 8:36 PM
 */

class Air_import_job_mod extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function get_job_type()
    {
        $this->db->select('id,name');
        $this->db->from('master_job_type');
        $this->db->where('master_job_type.type',2);
        $query=$this->db->get();

        return $query->result();
    }

    function get_shipment_type()
    {
        $this->db->select('id,name');
        $this->db->from('master_shipment_type');
        $query=$this->db->get();

        return $query->result();
    }

    function get_last_job_number($where)
    {
        $this->db->select('id');
        $this->db->from('booking');
        $this->db->where($where);
        $query=$this->db->get();

        return $query->result();
    }

    function get_last_inv_number()
    {
        $this->db->select('id');
        $this->db->from('invoice');
        $query=$this->db->get();

        return $query->result();
    }

    function get_unit()
    {
        $this->db->select('id,code');
        $this->db->from('master_unit');
        $query=$this->db->get();

        return $query->result();
    }

    function get_costing()
    {
        $this->db->select('id,name');
        $this->db->from('master_costing');
        $query=$this->db->get();

        return $query->result();
    }

    function get_job_currency()
    {
        $this->db->select('id,code');
        $this->db->from('master_job_currency');
        $query=$this->db->get();

        return $query->result();
    }

    function get_sales_person()
    {
        $this->db->select('auth_users.id,first_name,master_job_title.name as title_name');
        $this->db->from('auth_users');
        $this->db->join('master_job_title','master_job_title.id=auth_users.job_title');
        $this->db->where('auth_users.is_employee',0);
        $query=$this->db->get();

        return $query->result();
    }

    function get_inco_term()
    {
        $this->db->select('id,code');
        $this->db->from('master_inco_terms');
        $query=$this->db->get();

        return $query->result();
    }

    function get_client_by_category($where)
    {
        $this->db->select('client_list.id,client_code,company_name');
        $this->db->from('client_list');
        $this->db->join('client_category_list','client_category_list.client_id=client_list.id');
        $this->db->where($where);
        $query=$this->db->get();

        return $query->result();
    }

    public function save($table,$data)
    {
        $this->db->insert($table,$data);
        return $this->db->insert_id();
    }

    public function save_cntr($table,$data)
    {
        $this->db->insert($table,$data);
    }

    public function update_cntr($table,$data,$where)
    {
        $this->db->update($table,$data,$where);
    }

    public function update($table,$data,$where)
    {
        $this->db->update($table,$data,$where);
        return true;
    }

    function get_booking($id)
    {
        $this->db->select('id,main_type,job_type,job_number,shipment_type,airline,inco_term,agent,pod,etd_pod,vessel,voyage,eta_destination,main_line_co_loader,mbl_no,hbl_no,mbl_freight_term,original_main_line,original_mbl_no,serial_number,job_currency,buying_rate,selling_rate,costing_type,slpa_reference,status,pieces,pieces_type,gross_weight,chargeable_weight,cbm,sales_person');
        $this->db->from('booking');
        $this->db->where('booking.id',$id);
        $query=$this->db->get();

        return $query->row();
    }

    function get_booking_cntr($id)
    {
        $this->db->select('booking_cntr.id as cntr_id,cntr_no,seal_no,cntr_size,cntr_type,ship_type,edi_code,status,no_of_pkg,gross_wt,onload,offload,eta,etd,pieces,weight,volume');
        $this->db->from('booking_cntr');
        $this->db->where('booking_cntr.booking_ref_id',$id);
        $this->db->order_by('booking_cntr.id','ASC');
        $query=$this->db->get();

        return $query->result();
    }

    function get_flight($where)
    {
        $this->db->select('*');
        $this->db->from('booking_cntr');
        $this->db->where($where);
        $this->db->where(array('cntr_no !='=>''));
        $this->db->order_by('booking_cntr.id','ASC');
        $query=$this->db->get();

        return $query->result();
    }

    function get_customers()
    {
        $this->db->select('*');
        $this->db->from('client_list');
        $this->db->order_by('client_list.id','ASC');
        $query=$this->db->get();

        return $query->result();
    }

    public function delete_booking_cntr($id){

        $this->db->where(array('id'=>$id,'schedule_status'=>1));
        $this->db->delete('booking_cntr');
        return true;
    }

    public function get_schedules($id){

        $this->db->select('schedule_status');
        $this->db->from('booking_cntr');
        $this->db->where('booking_cntr.booking_ref_id',$id);
        $query=$this->db->get();

        return $query->result();
    }

    public function get_invoice($id){
        $this->db->select('inv_number,date');
        $this->db->from('invoice');
        $this->db->where('invoice.id',$id);
        $query=$this->db->get();

        return $query->row();
    }

    public function get_booking_id($id){
        $this->db->select('booking.id,c1.company_name,c1.address,c1.city,job_number,c3.company_name as shipper_name,master_job_currency.code as currency_name,airline,inco_term,c2.company_name as agent_name,pod,etd_pod,vessel,voyage,eta_destination,main_line_co_loader,mbl_no,hbl_no,mbl_freight_term,original_main_line,original_mbl_no,serial_number,job_currency,buying_rate,selling_rate,costing_type,slpa_reference,status,pieces,pieces_type,gross_weight,chargeable_weight,cbm,sales_person');;
        $this->db->from('booking');
        $this->db->where('booking.id',$id);
        $this->db->join('client_list c1','c1.id=booking.consignee','left');
        $this->db->join('client_list c2','c2.id=booking.agent','left');
        $this->db->join('client_list c3','c3.id=booking.shipper','left');
        $this->db->join('master_job_currency','master_job_currency.id=booking.job_currency','left');
        $query=$this->db->get();

        return $query->row();
    }

    public function get_invoice_more($id){
        $this->db->select('invoice_ref_id,master_costing.name as description_name,inv_qty,master_unit.code as unit_name,inv_rate,inv_ex_rate,inv_tax,master_job_currency.code as currency_name,inv_amount');
        $this->db->from('invoice_more');
        $this->db->where('invoice_more.invoice_ref_id',$id);
        $this->db->join('master_job_currency','master_job_currency.id=invoice_more.inv_currency','left');
        $this->db->join('master_costing','master_costing.id=invoice_more.inv_description','left');
        $this->db->join('master_unit','master_unit.id=invoice_more.inv_unit','left');
        $this->db->where('invoice_more.inv_qty !=',0);
        $query=$this->db->get();

        return $query->result();
    }

    public function get_invoice_count($job_id){

        $this->db->select("id");
        $this->db->from("invoice");
        $this->db->where(array('job_id'=>$job_id));
        $q=$this->db->get();

        return $q->result();
    }
}
