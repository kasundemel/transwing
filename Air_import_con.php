<?php
/**
 * Created by PhpStorm.
 * User: Kasun De Mel
 * Date: 1/5/2019
 * Time: 3:28 PM
 */

class Air_import_con extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->load->database();
        $this->load->helper('url');
        $this->load->model('air_import_mod');
        $this->load->library('permissions');
        $this->load->library('system_log');
        $this->load->library('goapi');
        $this->load->library('form_validation');

    }

    public function ai_dashboard()
    {
        $meta['title'] = 'Air Import Dashboard';

        $this->load->view('common/header',$meta);
        $this->load->view('ai_dashboard_index');
        $this->load->view('common/footer');
    }

    public function ai_booking()
    {
        $this->permissions->check_permission('access');
        $meta['title'] = 'Air Import Booking';

        $this->load->view('common/header',$meta);
        $this->load->view('ai_booking_index');
        $this->load->view('common/footer');
    }

    public function ai_operation()
    {
        $this->permissions->check_permission('access');
        $meta['title'] = 'Air Import Operation';

        $data=array();

        $data['unit']=$this->air_import_mod->get_unit();
        $data['description']=$this->air_import_mod->get_costing();
        $data['currency']=$this->air_import_mod->get_job_currency();

        $this->load->view('common/header',$meta);
        $this->load->view('ai_operation_index',$data);
        $this->load->view('common/footer');
    }

    public function ai_booking_list()
    {
        $this->load->library('datatables');

        $this->datatables->select("
            booking.id,
            booking.job_number,
           	CONCAT(c1.client_code),
            booking.mbl_no,
            booking.hbl_no,
            CONCAT(c2.client_code),
            booking.pod,
            booking.etd_pod,
            booking.id AS book_id,
        ", FALSE);

        $this->datatables->from('booking');
        $this->datatables->join('client_list c1','c1.id=booking.agent','left');
        $this->datatables->join('client_list c2','c2.id=booking.airline','left');
        $this->datatables->where('booking.main_type',1);

        $this->datatables->add_column("Actions",
            "<a class='btn-sm ' href='javascript:;' title='View Invoice' onclick='view_booking(".'$1'.")'>
                <i class='glyphicon glyphicon-list-alt'></i>
            </a>
            <a class='btn-sm ' href='javascript:;' title='Edit Information' onclick='edit_booking(".'$1'.")'>
                <i class='glyphicon glyphicon-pencil'> </i>
            </a>", "book_id");

        $this->datatables->unset_column('book_id');
        echo $this->datatables->generate();

    }

    public function ai_operation_list()
    {
        $this->load->library('datatables');

        $this->datatables->select("
            booking.id,
            booking.job_number,
           	CONCAT(c1.client_code),
            booking.mbl_no,
            booking.hbl_no,
            CONCAT(c2.client_code),
            booking.pod,
            booking.etd_pod,
            booking.id AS book_id,
        ", FALSE);

        $this->datatables->from('booking');
        $this->datatables->join('client_list c1','c1.id=booking.agent','left');
        $this->datatables->join('client_list c2','c2.id=booking.airline','left');
        $this->datatables->where('booking.main_type',1);

        $this->datatables->add_column("Actions",
            "<a class='btn-sm ' href='javascript:;' title='Edit Information' onclick='edit_booking(".'$1'.")'>
                <i class='glyphicon glyphicon-pencil'> </i>
            </a>
            <a class='btn-sm ' href='javascript:;' title='View Invoice' onclick='view_invoice(".'$1'.")'>
                <i class='glyphicon glyphicon-list-alt'></i>
            </a>", "book_id");

        $this->datatables->unset_column('book_id');
        echo $this->datatables->generate();

    }

    public function get_masters(){

        $job_type=$this->air_import_mod->get_job_type();
        $shipment_type=$this->air_import_mod->get_shipment_type();
        $agent=$this->air_import_mod->get_client_by_category(array('category_id'=>1));
        $airline=$this->air_import_mod->get_client_by_category(array('category_id'=>2));
        $inco_term=$this->air_import_mod->get_inco_term();
        $job_currency=$this->air_import_mod->get_job_currency();
        $shipper=$this->air_import_mod->get_client_by_category(array('category_id'=>11));
        $consignee=$this->air_import_mod->get_client_by_category(array('category_id'=>7));
        $sales_person=$this->air_import_mod->get_sales_person();
        $unit=$this->air_import_mod->get_unit();
        $description=$this->air_import_mod->get_costing();

        echo json_encode(array('job_type'=>$job_type,'shipment_type'=>$shipment_type,'agent'=>$agent,'airline'=>$airline,'inco_term'=>$inco_term,'job_currency'=>$job_currency,'shipper'=>$shipper,'consignee'=>$consignee,'sales_person'=>$sales_person,'description'=>$description,'unit'=>$unit));

    }

    public function get_last_job_number(){

        $count=count($this->air_import_mod->get_last_job_number(array('main_type'=>1)));

        if($count > 0){
            $job_number='TRWAI'.date("Y").date("m").(sprintf('%05d',(int)$count+1));
        }
        else{
            $job_number='TRWAI'.date("Y").date("m").'00001';
        }

        echo json_encode(array('job_number'=>$job_number));
    }

    public function save_booking(){

        $this->form_validation->set_rules('job_type','Job Type','required|trim');
        $this->form_validation->set_rules('shipment_type','Shipment Type','required|trim');
        $this->form_validation->set_rules('pod','POD','required|trim');
        $this->form_validation->set_rules('etd_pod','ETD POD','required|trim');
        $this->form_validation->set_rules('mbl_no','AWB No','required|trim');
        $this->form_validation->set_rules('pieces','PIECES','required|trim');
        $this->form_validation->set_rules('gross_weight','GROSS WEIGHT','required|trim');
        $this->form_validation->set_rules('chargeable_weight','CHARGEABLE WEIGHT','required|trim');
        $this->form_validation->set_rules('cbm','CBM','required|trim');

        if($this->form_validation->run() === false){

            $data = array();
            $data['error_string'] = array();
            $data['input_error'] = array();
            $data['status'] = FALSE;
            $data['error'] = "validation_error";

            if (form_error('job_type'))
            {
                $data['input_error'][] = 'job_type';
                $data['error_string'][] = form_error('job_type');
            }
            if (form_error('shipment_type'))
            {
                $data['input_error'][] = 'shipment_type';
                $data['error_string'][] = form_error('shipment_type');
            }
            if (form_error('pod'))
            {
                $data['input_error'][] = 'pod';
                $data['error_string'][] = form_error('pod');
            }
            if (form_error('etd_pod')) {
                $data['input_error'][] = 'etd_pod';
                $data['error_string'][] = form_error('etd_pod');
            }
            if (form_error('mbl_no')) {
                $data['input_error'][] = 'mbl_no';
                $data['error_string'][] = form_error('mbl_no');
            }
            if (form_error('pieces')) {
                $data['input_error'][] = 'pieces';
                $data['error_string'][] = form_error('pieces');
            }
            if (form_error('gross_weight')) {
                $data['input_error'][] = 'gross_weight';
                $data['error_string'][] = form_error('gross_weight');
            }
            if (form_error('chargeable_weight')) {
                $data['input_error'][] = 'chargeable_weight';
                $data['error_string'][] = form_error('chargeable_weight');
            }
            if (form_error('cbm')) {
                $data['input_error'][] = 'cbm';
                $data['error_string'][] = form_error('cbm');
            }

            echo json_encode($data);
            exit;

        }
        else{

            $val=$this->input->post();

            $count=count($this->air_import_mod->get_last_job_number(array('main_type'=>1)));

            if($count > 0){
                $job_number='TRWAI'.date("Y").date("m").(sprintf('%05d',(int)$count+1));
            }
            else{
                $job_number='TRWAI'.date("Y").date("m").'00001';
            }

            $data=array(
                'main_type'=>$val['main_type'],
                'job_type'=>$val['job_type'],
                'job_number'=>$job_number,
                'shipment_type'=>$val['shipment_type'],
                'airline'=>$val['airline'],
                'shipper'=>$val['shipper'],
                'consignee'=>$val['consignee'],
                'agent'=>$val['agent'],
                'pod'=>$val['pod'],
                'etd_pod'=>$val['etd_pod'],
                'eta_destination'=>$val['eta_destination'],
                'main_line_co_loader'=>$val['main_line_co_loader'],
                'mbl_no'=>$val['mbl_no'],
                'hbl_no'=>$val['hbl_no'],
                'mbl_freight_term'=>$val['mbl_freight_term'],
                'job_currency'=>$val['job_currency'],
                'buying_rate'=>$val['buying_rate'],
                'selling_rate'=>$val['selling_rate'],
                'sales_person'=>$val['sales_person'],
                'inco_term'=>$val['inco_term'],
                'pieces'=>$val['pieces'],
                'pieces_type'=>$val['pieces_type'],
                'gross_weight'=>$val['gross_weight'],
                'chargeable_weight'=>$val['chargeable_weight'],
                'cbm'=>$val['cbm'],
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'user'=>USER_ID,
                'date'=>date('Y-m-d'),
            );

            if($insert_id=$this->air_import_mod->save('booking',$data)){

                for($i=0;$i<10;$i++){

                    $available_status=0;

                    if($val['cntr_no'][$i] !=''){
                        $available_status=1;
                    }

                    $data1=array(
                        'booking_ref_id'=>$insert_id,
                        'cntr_no'=>$val['cntr_no'][$i],
                        'onload'=>$val['onload'][$i],
                        'offload'=>$val['offload'][$i],
                        'eta'=>$val['cntr_eta'][$i],
                        'etd'=>$val['cntr_etd'][$i],
                        'pieces'=>$val['cntr_pieces'][$i],
                        'weight'=>$val['cntr_weight'][$i],
                        'volume'=>$val['cntr_volume'][$i],
                        'available_status'=>$available_status,
                    );

                    $this->air_import_mod->save_cntr('booking_cntr',$data1);
                }

                echo json_encode(array('status'=>TRUE));

            }
        }
    }

    public function save_invoice(){

        $this->form_validation->set_rules('flight','Flight','required|trim');
        $this->form_validation->set_rules('bill_to','Bill To','required|trim');
        $this->form_validation->set_rules('contact_person','Contact Person','required|trim');

        if($this->form_validation->run() === false) {

            $data = array();
            $data['error_string'] = array();
            $data['input_error'] = array();
            $data['status'] = FALSE;
            $data['error'] = "validation_error";

            if (form_error('flight'))
            {
                $data['input_error'][] = 'flight';
                $data['error_string'][] = form_error('flight');
            }
            if (form_error('bill_to'))
            {
                $data['input_error'][] = 'bill_to';
                $data['error_string'][] = form_error('bill_to');
            }
            if (form_error('contact_person'))
            {
                $data['input_error'][] = 'contact_person';
                $data['error_string'][] = form_error('contact_person');
            }

            echo json_encode($data);
            exit;

        }else{

            $val=$this->input->post();

            $count=count($this->air_import_mod->get_last_inv_number());

            if($count > 0){
                $inv_number='TWLAI'.date("Y").date("m").(sprintf('%05d',(int)$count+1));
            }
            else{
                $inv_number='TWLAI'.date("Y").date("m").'00001';
            }

            $data=array(
                'main_type'=>$val['main_type'],
                'flight_no'=>$val['flight'],
                'inv_number'=>$inv_number,
                'job_id'=>$val['id'],
                'date'=>date('Y-m-d'),
                'bill_to'=>$val['bill_to'],
                'contact_person'=>$val['contact_person'],
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'user'=>USER_ID,
            );

            if($insert_id=$this->air_import_mod->save('invoice',$data)){

                $local_length=sizeof($val['inv_description']);

                for($i=0;$i<$local_length;$i++){

                    if($val['inv_description'][$i] != ""){

                        $data1=array(
                            'invoice_ref_id'=>$insert_id,
                            'inv_description'=>$val['inv_description'][$i],
                            'inv_qty'=>$val['inv_qty'][$i],
                            'inv_unit'=>$val['inv_unit'][$i],
                            'inv_rate'=>$val['inv_rate'][$i],
                            'inv_currency'=>$val['inv_currency'][$i],
                            'inv_ex_rate'=>$val['inv_ex_rate'][$i],
                            'inv_tax'=>$val['inv_tax'][$i],
                            'inv_amount'=>$val['inv_amount'][$i],
                        );

                        $this->air_import_mod->save_cntr('invoice_more',$data1);
                    }
                }

                echo json_encode(array('status'=>TRUE));

            }
        }
    }

    public function update_booking(){

        $this->form_validation->set_rules('job_type','Job Type','required|trim');
        $this->form_validation->set_rules('shipment_type','Shipment Type','required|trim');
        $this->form_validation->set_rules('pod','POD','required|trim');
        $this->form_validation->set_rules('etd_pod','ETD POD','required|trim');
        $this->form_validation->set_rules('mbl_no','AWB No','required|trim');
        $this->form_validation->set_rules('pieces','PIECES','required|trim');
        $this->form_validation->set_rules('gross_weight','GROSS WEIGHT','required|trim');
        $this->form_validation->set_rules('chargeable_weight','CHARGEABLE WEIGHT','required|trim');
        $this->form_validation->set_rules('cbm','CBM','required|trim');

        if($this->form_validation->run() === false){

            $data = array();
            $data['error_string'] = array();
            $data['input_error'] = array();
            $data['status'] = FALSE;
            $data['error'] = "validation_error";

            if (form_error('job_type'))
            {
                $data['input_error'][] = 'job_type';
                $data['error_string'][] = form_error('job_type');
            }
            if (form_error('shipment_type'))
            {
                $data['input_error'][] = 'shipment_type';
                $data['error_string'][] = form_error('shipment_type');
            }
            if (form_error('pod'))
            {
                $data['input_error'][] = 'pod';
                $data['error_string'][] = form_error('pod');
            }
            if (form_error('etd_pod')) {
                $data['input_error'][] = 'etd_pod';
                $data['error_string'][] = form_error('etd_pod');
            }
            if (form_error('mbl_no')) {
                $data['input_error'][] = 'mbl_no';
                $data['error_string'][] = form_error('mbl_no');
            }
            if (form_error('pieces')) {
                $data['input_error'][] = 'pieces';
                $data['error_string'][] = form_error('pieces');
            }
            if (form_error('gross_weight')) {
                $data['input_error'][] = 'gross_weight';
                $data['error_string'][] = form_error('gross_weight');
            }
            if (form_error('chargeable_weight')) {
                $data['input_error'][] = 'chargeable_weight';
                $data['error_string'][] = form_error('chargeable_weight');
            }
            if (form_error('cbm')) {
                $data['input_error'][] = 'cbm';
                $data['error_string'][] = form_error('cbm');
            }

            echo json_encode($data);
            exit;

        }
        else{

            $val=$this->input->post();

            $data=array(

                'main_type'=>$val['main_type'],
                'job_type'=>$val['job_type'],
                'shipment_type'=>$val['shipment_type'],
                'airline'=>$val['airline'],
                'shipper'=>$val['shipper'],
                'consignee'=>$val['consignee'],
                'agent'=>$val['agent'],
                'pod'=>$val['pod'],
                'etd_pod'=>$val['etd_pod'],
                'eta_destination'=>$val['eta_destination'],
                'main_line_co_loader'=>$val['main_line_co_loader'],
                'mbl_no'=>$val['mbl_no'],
                'hbl_no'=>$val['hbl_no'],
                'mbl_freight_term'=>$val['mbl_freight_term'],
                'job_currency'=>$val['job_currency'],
                'buying_rate'=>$val['buying_rate'],
                'selling_rate'=>$val['selling_rate'],
                'inco_term'=>$val['inco_term'],
                'sales_person'=>$val['sales_person'],
                'pieces'=>$val['pieces'],
                'pieces_type'=>$val['pieces_type'],
                'gross_weight'=>$val['gross_weight'],
                'chargeable_weight'=>$val['chargeable_weight'],
                'cbm'=>$val['cbm'],
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'user'=>USER_ID,
                'date'=>date('Y-m-d'),
            );

            if($this->air_import_mod->update('booking',$data,array('booking.id'=>$val['id']))){


                for($i=1;$i<=10;$i++) {

                    $available_status=0;

                    if($val['cntr_no'][$i] !=''){
                        $available_status=1;
                    }

                    $data1 = array(
                        'booking_ref_id' => $val['id'],
                        'cntr_no' => $val['cntr_no'][$i],
                        'onload' => $val['onload'][$i],
                        'offload' => $val['offload'][$i],
                        'eta' => $val['cntr_eta'][$i],
                        'etd' => $val['cntr_etd'][$i],
                        'pieces' => $val['cntr_pieces'][$i],
                        'weight' => $val['cntr_weight'][$i],
                        'volume' => $val['cntr_volume'][$i],
                        'available_status' => $available_status,
                    );

                    $this->air_import_mod->update_cntr('booking_cntr', $data1, array('id' => $val['cntr_id'][$i],'schedule_status'=>1));
                }
            }

            echo json_encode(array('status'=>TRUE));

        }
    }

    public function edit_booking($id){

        $booking=$this->air_import_mod->get_booking($id);
        $cntr=$this->air_import_mod->get_booking_cntr($id);

        echo json_encode(array('booking'=>$booking,'cntr'=>$cntr));

    }

    public function delete_cntr($id){

        $this->air_import_mod->delete_booking_cntr($id);
        echo json_encode(array('satus'=>TRUE));

    }

    public function view_booking($id){

        $booking=$this->air_import_mod->get_booking($id);
        $cntr=$this->air_import_mod->get_booking_cntr($id);

        echo json_encode(array('booking'=>$booking,'cntr'=>$cntr));

    }


    public function sendMail()
    {

        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'mail.gateonelk.com',
            'smtp_port' => 465,
            'smtp_user' => 'noreply@gateonelk.com', // change it to yours
            'smtp_pass' => 'noreply@1234', // change it to yours
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'wordwrap' => TRUE
        );

        $headers = "MIME-Version: 2.0\n" ;
        $headers .= "Content-Type: text/html; charset=\"iso-8859-1\"\n";
        $headers .= "X-Priority: 1 (Highest)\n";
        $headers .= "X-MSMail-Priority: High\n";
        $headers .= "Importance: High\n";

        $message = '<html><body><table border="0" cellpadding="0" cellspacing="0" width="520"><tbody><tr><td width="520" align="left" valign="top" style="padding: 5px 0px 5px 0px;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tbody>
        <tr>
            <td width="520" align="center" valign="top">
                <a href="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/#" style="text-decoration: none;">
                    <img alt="Transwing Logistics" src="http://gateonelk.com/transwing/assets/pages/img/login/logo.png" width="200" height="50" border="0" style="text-align: center; font-size: 30px; color: #000001; font-family: Palatino, Times, Georgia; text-transform:uppercase; display:block;">
                </a>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td width="520" align="left" valign="top">
                <table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td width="520" align="center" valign="top">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td style="color:#000000; font-family:arial; font-size:22px; padding:10px 25px 25px 25px;">Dear Valued Customer,<br><br>
                        Your shipment is on its way!
                    </td>
                    </tr></tbody></table></td>
                </tr><tr><td width="520" align="center" valign="top">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td><img alt="" src="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/images/5f1eb47b2889809f9da8bfffc9c548ea61e94d48.jpeg" width="520" border="0" style="display: block;"></td>
                    </tr></tbody></table></td>
                </tr><tr><td style="padding:5px 0px 5px 0px;">
                <img alt="Victorias Secret" src="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/images/f42a4e48fcf3db86218e315ce14f25e3eb73d135.jpeg" width="520" border="0" style="display: block;"></td>
                </tr>
        <tr><td style="padding:10px 28px 10px 28px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td width="50%" style="font-family: Arial, Helvetica, sans-serif; font-size: 22px; font-weight:normal; color: #000000;">MAWB No</td>
                            <td width="50%" style="font-family: Arial, Helvetica, sans-serif; font-size: 22px; font-weight:bold; color: #000000;" align="right">
                                <a href="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/#" target="_self" style="text-decoration: underline; color: #1a1a1a;">112-99872113</a></td>
                        </tr></tbody></table></td>
                </tr><tr><td style="padding:5px 0px 5px 0px;"><img alt="Victorias Secret" src="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/images/f42a4e48fcf3db86218e315ce14f25e3eb73d135.jpeg" width="520" border="0" style="display: block;"></td>
                </tr><tr><td style="padding:10px 28px 10px 28px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td width="50%" style="font-family: Arial, Helvetica, sans-serif; font-size: 22px; font-weight:normal; color: #000000;">Expected Arrival</td>
                        <td width="50%" style="font-family: Arial, Helvetica, sans-serif; font-size: 22px; font-weight:bold; color: #000000;" align="right">January 20, 2019</td>
                    </tr></tbody></table></td>
                </tr><tr><td style="padding:5px 0px 5px 0px;"><img alt="Victorias Secret" src="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/images/f42a4e48fcf3db86218e315ce14f25e3eb73d135.jpeg" width="520" border="0" style="display: block;"></td>
                </tr><tr><td style="padding:10px 28px 10px 28px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td width="50%" style="font-family: Arial, Helvetica, sans-serif; font-size: 22px; font-weight:normal; color: #000000;">Flight No</td>
                        <td width="50%" style="font-family: Arial, Helvetica, sans-serif; font-size: 22px; font-weight:bold; color: #000000;" align="right">MU231/20JAN</td>
                    </tr></tbody></table></td>
                </tr>
                <tr>
                    <td style="padding:5px 0px 5px 0px;"><img alt="Victorias Secrets" src="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/images/f42a4e48fcf3db86218e315ce14f25e3eb73d135.jpeg" width="520" border="0" style="display: block;"></td></tr><tr>
                </tr><tr><td width="520" align="left" valign="top">
                        <img alt="Connect With Us" src="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/images/cbd7eadfdea7b4d58bafedabff2ca4fe63657a10.jpeg" width="520" height="62" border="0" style="text-align: center; font-size: 10px; color: #000001; font-family: Palatino, Times, Georgia; text-transform: uppercase; display:block;"></td>
                </tr>
                <tr>
                    <td width="520" align="left" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td width="73" align="left" valign="top"><a href="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/#" style="text-decoration: none;"><img alt="Facebook" src="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/images/e4f0ff0b9c5c989004d96e2bf37ee93866c8227f.jpeg" width="73" height="65" border="0" style="text-align: center; font-size: 10px; color: #000001; font-family: Palatino, Times, Georgia; text-transform: uppercase; display:block;"></a></td>
                            <td width="76" align="left" valign="top"><a href="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/#" style="text-decoration: none;"><img alt="Twitter" src="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/images/225f1485a879c3fe07528e63d5389b58071573e5.jpeg" width="76" height="65" border="0" style="text-align: center; font-size: 10px; color: #000001; font-family: Palatino, Times, Georgia; text-transform: uppercase; display:block;"></a></td>
                            <td width="74" align="left" valign="top"><a href="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/#" style="text-decoration: none;"><img alt="YouTube" src="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/images/10fb271b7b61c1b96e6956997f31b9aff5e0abb7.jpeg" width="74" height="65" border="0" style="text-align: center; font-size: 10px; color: #000001; font-family: Palatino, Times, Georgia; text-transform: uppercase; display:block;"></a></td>
                            <td width="76" align="left" valign="top"><a href="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/#" style="text-decoration: none;"><img alt="Pinterest" src="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/images/8ef2b0847a760911a2de20634ada2b359d0bfc29.jpeg" width="76" height="65" border="0" style="text-align: center; font-size: 10px; color: #000001; font-family: Palatino, Times, Georgia; text-transform: uppercase; display:block;"></a></td>
                            <td width="78" align="left" valign="top"><a href="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/#" style="text-decoration: none;"><img alt="Instagram" src="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/images/369c19a129a31ae2b18027de087ae38e9ec89df9.jpeg" width="78" height="65" border="0" style="text-align: center; font-size: 10px; color: #000001; font-family: Palatino, Times, Georgia; text-transform: uppercase; display:block;"></a></td>
                            <td width="75" align="left" valign="top"><a href="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/#" style="text-decoration: none;"><img alt="Tumblr" src="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/images/d1fb97fd09e867ffea1c346a63d1683759d9832d.jpeg" width="75" height="65" border="0" style="text-align: center; font-size: 10px; color: #000001; font-family: Palatino, Times, Georgia; text-transform: uppercase; display:block;"></a></td>
                            <td width="68" align="left" valign="top"><a href="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/#" style="text-decoration: none;"><img alt="Spotify" src="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/images/d8714a0428ac8a64fb67491924fea22c92aa6e3b.jpeg" width="68" height="65" border="0" style="text-align: center; font-size: 10px; color: #000001; font-family: Palatino, Times, Georgia; text-transform: uppercase; display:block;"></a></td>
                        </tr>
                        </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="520" align="left" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                            <tr>
                                <td width="58" valign="top" style="font-size:0%;">
                                    <img alt="" src="https://assets.mailcharts.com/emails/1ef75bc1-9f1a-1a49-7c5e-6db81af9e93e/images/d804b495cb9b84b9007a25b5d85f9ae674004cde.gif" width="58" height="58" border="0"></td>
                                <td width="200" align="center" valign="top" colspan="4">
                                    <a href="http://gateonelk.com" style="text-decoration: none;" target="_blank">
        Powered By Gateone Solutions (Pvt) Ltd.
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</td>
</tr>
</tbody>
</table>
</body>
</html>';

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from('noreply@gateonelk.com','Arrival Notification From Transwing Logistics'); // change it to yours
        $this->email->set_header($headers,'Transwing Logistics'); // change it to yours
        $this->email->to('reshansaparamadu@icloud.com');// change it to yours
        $this->email->cc('reshan@transwing.lk');// change it to yours
        $this->email->bcc('dilruk@transwing.lk');// change it to yours
        $this->email->subject('Arrival Notification');
        $this->email->set_mailtype("html");
        $this->email->message($message);
        if($this->email->send())
        {
            echo 'Email sent.';
        }
        else
        {
            show_error($this->email->print_debugger());
        }

    }

    public function load_schedule($id){

        $this->load->library('datatables');

        $this->datatables->select("
                booking_cntr.id AS cntr_id,
                booking_cntr.booking_ref_id AS ref_id,
                booking_cntr.schedule_status AS schedule_id,
                booking_cntr.id,
                booking_cntr.cntr_no,
                booking_cntr.onload,
                booking_cntr.offload,
                booking_cntr.eta,
                booking_cntr.etd,
                booking_cntr.pieces,
                booking_cntr.weight,
                booking_cntr.volume,
                booking_cntr.schedule_status,
        ", FALSE);

        $this->datatables->from('booking_cntr');
        $this->datatables->where(array('booking_cntr.booking_ref_id'=>$id,'booking_cntr.available_status'=>1));

        $this->datatables->add_column("Actions",
            "<a class='btn-sm ' href='javascript:;' title='Edit' onclick='confirmBtn(".'$1'.','.'$2'.','.'$3'.")'>
                <i class='glyphicon glyphicon-pencil'></i>
            </a>", "ref_id,cntr_id,schedule_id");

        $this->datatables->unset_column('cntr_id');
        $this->datatables->unset_column('ref_id');
        $this->datatables->unset_column('schedule_id');
        echo $this->datatables->generate();
    }

    public function load_invoice($id){

        $this->load->library('datatables');

        $this->datatables->select("
                invoice.id AS inv_id,
                invoice.id,
                invoice.job_id AS job_id,
                invoice.inv_number,
                invoice.date,
                auth_users.first_name,
        ", FALSE);

        $this->datatables->from('invoice');
        $this->datatables->join('auth_users','auth_users.id=invoice.user','left');
        $this->datatables->where('invoice.job_id',$id);

        $this->datatables->add_column("Actions",
            "<a class='btn-sm' target='_blank' href='".base_url('air_import/air_import_con/print_invoice/$1/$2')."' title='Print Invoice' >
                <i class='glyphicon glyphicon-print'></i>
            </a>", "job_id,inv_id");

        $this->datatables->unset_column('inv_id');
        $this->datatables->unset_column('job_id');
        echo $this->datatables->generate();
    }

    public function update_pending_status(){

        $var=$this->input->post();

        $data=array(
            'schedule_status'=>2,
        );

        if($this->air_import_mod->update_cntr('booking_cntr',$data,array('booking_ref_id'=>$var['job_id'],'id'=>$var['cntr_id']))){

            $message=$var['message'];
            $number ='94'.ltrim($var['contact_number'],'0');

            $this->goapi->send($number,$message,$status=null);

            echo json_encode(array('status'=>TRUE));
        }
    }

    public function update_arrived_status(){

        $var=$this->input->post();

        $data=array(
            'schedule_status'=>3,
        );

        if($this->air_import_mod->update_cntr('booking_cntr',$data,array('booking_ref_id'=>$var['job_id'],'id'=>$var['cntr_id']))){

            $message=$var['message'];
            $number ='94'.ltrim($var['contact_number'],'0');

            $this->goapi->send($number,$message,$status=null);

            echo json_encode(array('status'=>TRUE));
        }

    }

    public function print_invoice($job_id,$inv_id){

        $meta['title'] = 'Air Import Invoice';
        $this->load->library('Numbertowords');

        $data=array();

        $data['booking']=$this->air_import_mod->get_booking_id($job_id);
        $data['invoice']=$this->air_import_mod->get_invoice($inv_id);
        $data['invoice_more']=$this->air_import_mod->get_invoice_more($inv_id);

        $this->load->view('common/header',$meta);
        $this->load->view('ai_invoice_index',$data);
        $this->load->view('common/footer');
    }


    public function test_sms(){

        $mobile='94719330984';
        $message='hi, kasun';

        $this->send_sms->send($mobile,$message);
    }

    public function get_booking_id($id){
        $booking=$this->air_import_mod->get_booking_id($id);
        echo json_encode(array('booking'=>$booking));
    }

}
