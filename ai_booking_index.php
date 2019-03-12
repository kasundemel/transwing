<?php

/**
 * Created by PhpStorm.
 * User: Kasun De Mel
 * Date: 1/5/2019
 * Time: 3:39 PM
 */

?>

<style>

    input,select{
        width: 80% !important;
    }
    label{
        width: 100% !important;
    }
    .input-medium {
        width: 70% !important;
    }

</style>

<div class="page-content">
    <!-- BEGIN BREADCRUMBS -->
    <div class="breadcrumbs">
        <div style="border-bottom:2px solid #FF6600; font-size:12pt; padding: 5px 20px; margin-bottom: 10px">
            <button class="btn btn-success bt-add-v2 pull-right"  onclick="add_new()" id="add_btn"><i class="glyphicon glyphicon-plus"></i> Add New Booking</button>
            <ol class="breadcrumb">
                <li style="font-size: 12px">
                    <a href="<?php echo base_url(); ?>">Home</a>
                </li>
                <li style="font-size: 12px">
                    <a href="javascript:;">Air Import</a>
                </li>
                <li class="active" style="font-size: 14px">Booking</li>
            </ol>
        </div>
    </div>
    <div class="row widget-row">
        <div class="tools"> </div>
        <table id="data_table" class="fresh-table  table table-bordered table-hover order-column" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th width="10">#</th>
                <th>JOB NUMBER</th>
                <th>ORIGIN</th>
                <th>MBL NO</th>
                <th>HBL NO</th>
                <th>AIR LINE</th>
                <th>POD</th>
                <th>POD ETD</th>
                <th>ACTION</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>


<div class="modal fade bs-example-modal-lg in" id="add_new_modal" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg"  style="min-width: 1000px; max-width: 1000px;">
<div class="modal-content">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title" id="udModalLabel"></h4>
</div>
<div class="modal-body">
<form id="booking_form" class="form-horizontal" role="form">
<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
<div class="row" style="margin-right:5px;margin-left: 5px;margin-top: 10px">
    <span id="job_number_span"></span>
    <h4  class="sub-head">Operation Contact Information</h4>
    <div class="col-md-6">
        <table style="width:100%" class="st-lumi-table" cellspacing="2" cellpadding="5" border="0">
            <input type="hidden" id="id" name="id">
            <input type="hidden" id="main_type" name="main_type" value="1">
            <tbody>
            <tr>
                <td valign="top"><label>JOB TYPE</label></td>
                <td>
                    <select name="job_type" class="from-control" id="job_type">
                    </select>
                    <span class="error-block"></span>
                </td>
            </tr>
            <tr>
                <td valign="top"><label>SHIPMENT TYPE</label></td>
                <td>
                    <select name="shipment_type" class="from-control" id="shipment_type">
                    </select>
                    <span class="error-block"></span>
                </td>
            </tr>
            <tr>
                <td valign="top"><label>SHIPPER</label></td>
                <td>
                    <select name="shipper" class="from-control" id="shipper">
                    </select>
                    <span class="error-block"></span>
                </td>
            </tr>
            <tr>
                <td valign="top"><label>CONSIGNEE</label></td>
                <td>
                    <select name="consignee" class="from-control" id="consignee">
                    </select>
                    <span class="error-block"></span>
                </td>
            </tr>
            <tr>
                <td valign="top"><label>AGENT</label></td>
                <td colspan="4">
                    <select name="agent" class="from-control" id="agent">
                    </select>
                    <span class="error-block"></span>
                </td>
            </tr>
            <tr>
                <td valign="top"><label>POD</label></td>
                <td colspan="4">
                    <input name="pod" type="text" class="from-control" id="pod">
                    <span class="error-block"></span>
                </td>
            </tr>
            <tr>
                <td valign="top"><label>ETD POD</label></td>
                <td colspan="4">
                    <input name="etd_pod" type="text" id="etd_pod" class="form-control form-control-inline input-medium date-picker" data-date-format="yyyy-mm-dd">
                    <span class="error-block"></span>
                </td>
            </tr>
            <tr>
                <td valign="top"><label>AIR LINE</label></td>
                <td colspan="4">
                    <select name="airline" class="from-control" id="airline">
                    </select>
                    <span class="error-block"></span>
                </td>
            </tr>
            <tr>
                <td valign="top"><label>ETA DESTINATION</label></td>
                <td colspan="4">
                    <input name="eta_destination" type="text" id="eta_destination" class="form-control form-control-inline input-medium date-picker" data-date-format="yyyy-mm-dd">
                    <span class="error-block"></span>
                </td>
            </tr>
            <tr>
                <td valign="top"><label>CO-LOADER</label></td>
                <td colspan="4">
                    <input name="main_line_co_loader" type="text" class="from-control" id="main_line_co_loader">
                    <span class="error-block"></span>
                </td>
            </tr>
            <tr>
                <td valign="top"><label>MASTER AWB NO</label></td>
                <td colspan="4">
                    <input name="mbl_no" type="text" class="from-control" id="mbl_no" maxlength="11">
                    <span class="error-block"></span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <table style="width:100%;" class="st-lumi-table" cellspacing="2" cellpadding="5" border="0">
            <tbody>
            <tr id="hbl_no_div">
                <td valign="top"><label>HOUSE AWB NO</label></td>
                <td colspan="4">
                    <input name="hbl_no" type="text" class="from-control" id="hbl_no">
                    <span class="error-block"></span>
                </td>
            </tr>
            <tr>
                <td valign="top"><label>FREIGHT TERM</label></td>
                <td>
                    <select name="mbl_freight_term" id="mbl_freight_term" class="from-control">
                        <option value="">CHARGES COLLECT</option>
                        <option value="">CREDIT</option>
                    </select>
                    <span class="error-block"></span>
                </td>
            </tr>
            <tr>
                <td valign="top"><label>JOB CURRENCY</label></td>
                <td>
                    <select name="job_currency" id="job_currency" class="from-control">

                    </select>
                    <span class="error-block"></span>
                </td>
            </tr>
            <tr>
                <td valign="top"><label>BUYING EX. RATE</label></td>
                <td colspan="4">
                    <input name="buying_rate" type="text" class="from-control allownumericwithdecimal rate" id="buying_rate" size="20" maxlength="6">
                    <span class="error-block"></span>
                </td>
            </tr>
            <tr>
                <td valign="top"><label>SELLING EX. RATE</label></td>
                <td colspan="4">
                    <input name="selling_rate" type="text" class="from-control allownumericwithdecimal rate" id="selling_rate" size="20" maxlength="6">
                    <span class="error-block"></span>
                </td>
            </tr>
            <tr>
                <td valign="top"><label>INCO TERM</label></td>
                <td colspan="4">
                    <select name="inco_term" id="inco_term" class="from-control">

                    </select>
                    <span class="error-block"></span>
                </td>
            </tr>
            <tr>
                <td valign="top"><label>PIECES</label></td>
                <td colspan="3">
                    <input type="text" name="pieces" id="pieces" name="numeric" class='from-control allownumericwithdecimal' style="width: 43% !important;">
                    <select name="pieces_type" id="pieces_type" class="from-control" style="width: 35% !important;">
                        <option value="PCS">PCS</option>
                        <option value="CTNS">CTNS</option>
                        <option value="BOXES">BOXES</option>
                        <option value="PKGS">PKGS</option>
                        <option value="ROLLS">ROLLS</option>
                        <option value="BALES">BALES</option>
                    </select>
                    <span class="error-block"></span>
                </td>
            </tr>
            <tr>
                <td valign="top"><label>GROSS WEIGHT</label></td>
                <td colspan="4">
                    <input type="text" name="gross_weight" id="gross_weight" class="from-control allownumericwithdecimal">
                    <span class="error-block"></span>
                </td>
            </tr>
            <tr>
                <td valign="top"><label>CHARGEABLE WEIGHT</label></td>
                <td colspan="4">
                    <input type="text" name="chargeable_weight" id="chargeable_weight" class="from-control allownumericwithdecimal">
                    <span class="error-block"></span>
                </td>
            </tr>
            <tr>
                <td valign="top"><label>CBM</label></td>
                <td colspan="4">
                    <input type="text" name="cbm" id="cbm" class="from-control allownumericwithdecimal">
                    <span class="error-block"></span>
                </td>
            </tr>
            <tr>
                <td valign="top"><label>SALES PERSON</label></td>
                <td colspan="4">
                    <select name="sales_person" id="sales_person" class="from-control">
                    </select>
                    <span class="error-block"></span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row" style="margin-right:5px;margin-left: 5px;margin-top: 10px">
    <h4  class="sub-head">Flight Information</h4>
    <div class="col-md-12">
        <table style="width:100%" class="st-lumi-table" cellspacing="2" cellpadding="5" border="0">
            <tbody><tr>
                <td style="text-align:center">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td>
                                <table style="width:100%" class="st-lumi-table" cellspacing="2" cellpadding="5" border="1" id="dytable">
                                    <thead>
                                    <tr>
                                        <th style="text-align:center;" scope="col" width="10">#</th>
                                        <th style="text-align:center;" align="center" scope="col">FLIGHT NO</th>
                                        <th style="text-align:center;" align="center" scope="col">ONLOAD</th>
                                        <th style="text-align:center;" align="center" scope="col">OFF LOAD</th>
                                        <th style="text-align:center;" align="center" scope="col">ETA</th>
                                        <th style="text-align:center;" align="center" scope="col">ETD</th>
                                        <th style="text-align:center;" align="center" scope="col">PIECES</th>
                                        <th style="text-align:center;" align="center" scope="col">WEIGHT</th>
                                        <th style="text-align:center;" align="center" scope="col">VOLUME</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php for($x=1;$x<=5;$x++){ ?>
                                    <tr class="trclone">
                                        <td align="center" width="10"><?php echo $x;?></td>
                                        <td align="center">
                                            <input name="cntr_id[]" type="hidden" id="cntr_id_<?php echo $x;?>" class="form-control" value="0">
                                            <input name="cntr_no[]" type="text" id="cntr_no_<?php echo $x;?>" class="form-control">
                                        </td>
                                        <td align="center">
                                            <input name="onload[]" type="text" id="onload_<?php echo $x;?>" width="80px" class="form-control">
                                        </td>
                                        <td align="center">
                                            <input name="offload[]" type="text" id="offload_<?php echo $x;?>" width="80px" class="form-control">
                                        </td>
                                        <td align="center">
                                            <input name="cntr_eta[]" type="text" id="cntr_eta_<?php echo $x;?>" class="form-control form-control-inline input-medium date-picker" data-date-format="yyyy-mm-dd">
                                        </td>
                                        <td align="center">
                                            <input name="cntr_etd[]" type="text" id="cntr_etd_<?php echo $x;?>" class="form-control form-control-inline input-medium date-picker" data-date-format="yyyy-mm-dd">
                                        </td>
                                        <td align="center">
                                            <input name="cntr_pieces[]" type="text" id="cntr_pieces_<?php echo $x;?>" class="form-control allownumericwithdecimal">
                                        </td>
                                        <td align="center">
                                            <input name="cntr_weight[]" type="text" id="cntr_weight_<?php echo $x;?>" class="form-control allownumericwithdecimal">
                                        </td>
                                        <td align="center">
                                            <input name="cntr_volume[]" type="text" id="cntr_volume_<?php echo $x;?>" class="form-control allownumericwithdecimal">
                                        </td>
                                    </tr>
                                    <?php } ?>
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
    </div>
</div>
<div class="row" style="margin-right:5px;margin-left: 5px;margin-top: 10px">
    <h4  class="sub-head">HOUSE AWB Information</h4>
    <div class="col-md-12">
        <table style="width:100%" class="st-lumi-table" cellspacing="2" cellpadding="5" border="0">
            <tbody><tr>
                <td style="text-align:center">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td>
                                <table style="width:100%" class="st-lumi-table" cellspacing="2" cellpadding="5" border="1" id="dytable">
                                    <thead>
                                    <tr>
                                        <th style="text-align:center;" scope="col" width="10">#</th>
                                        <th style="text-align:center;" align="center" scope="col">HAWB NO</th>
                                        <th style="text-align:center;" align="center" scope="col">CONSIGNEE</th>
                                        <th style="text-align:center;" align="center" scope="col">SHIPPER</th>
                                        <th style="text-align:center;" align="center" scope="col">PIECES</th>
                                        <th style="text-align:center;" align="center" scope="col">DESCRIPTION</th>
                                        <th style="text-align:center;" align="center" scope="col">SALES PERSON</th>
                                        <th style="text-align:center;" align="center" scope="col">WEIGHT</th>
                                        <th style="text-align:center;" align="center" scope="col">VOLUME</th>
                                        <th style="text-align:center;" align="center" scope="col">PACKAGE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php for($x=1;$x<=5;$x++){ ?>
                                    <tr class="trclone">
                                        <td align="center" width="10"><?php echo $x;?></td>
                                        <td align="center">
                                            <input name="hawb_no[]" type="hidden" id="cntr_id_<?php echo $x;?>" class="form-control" value="0">
                                            <input name="cntr_no[]" type="text" id="cntr_no_<?php echo $x;?>" class="form-control">
                                        </td>
                                        <td align="center">
                                            <input name="onload[]" type="text" id="onload_<?php echo $x;?>" width="80px" class="form-control">
                                        </td>
                                        <td align="center">
                                            <input name="offload[]" type="text" id="offload_<?php echo $x;?>" width="80px" class="form-control">
                                        </td>
                                        <td align="center">
                                            <input name="cntr_eta[]" type="text" id="cntr_eta_<?php echo $x;?>" class="form-control form-control-inline input-medium date-picker" data-date-format="yyyy-mm-dd">
                                        </td>
                                        <td align="center">
                                            <input name="cntr_etd[]" type="text" id="cntr_etd_<?php echo $x;?>" class="form-control form-control-inline input-medium date-picker" data-date-format="yyyy-mm-dd">
                                        </td>
                                        <td align="center">
                                            <input name="cntr_pieces[]" type="text" id="cntr_pieces_<?php echo $x;?>" class="form-control allownumericwithdecimal">
                                        </td>
                                        <td align="center">
                                            <input name="cntr_weight[]" type="text" id="cntr_weight_<?php echo $x;?>" class="form-control allownumericwithdecimal">
                                        </td>
                                        <td align="center">
                                            <input name="cntr_volume[]" type="text" id="cntr_volume_<?php echo $x;?>" class="form-control allownumericwithdecimal">
                                        </td>
                                    </tr>
                                    <?php } ?>
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
    </div>
</div>
</form>
</div>
<div class="modal-footer" style="margin-top: 20px">
    <button type="button" class="btn btn-primary" onclick="save()"> Save </button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>


<script>

var save_method='';
var counter =0;
var counter1=1;
var counter2=0;
var table;
var dyTable;

var html2='';

$(document).ready(function() {

    table = $('#data_table').DataTable({

        "processing": true,
        "serverSide": true,
        "stateSave": false,
        "oLanguage": {  sProcessing: "<img src='<?php echo base_url(); ?>/assets/global/img/loading-spinner-grey.gif'>" },
        "ajax": {
            "data": {
                "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
            },
            "url": "<?=base_url()?>air_import/air_import_con/ai_booking_list",
            "type": "POST"
        },
        "autoWidth": false,
        "columnDefs": [
            {
                "targets": [-1],
                "orderable": false
            }
        ],
        "aoColumns": [
            {"bSearchable": false}, null, null, null, null, null, null,{"bSearchable": false},{"bSearchable": false}
        ],
        "language": {
            "aria": {
                "sortAscending": ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            },
            "emptyTable": "No data available in table",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "No entries found",
            "infoFiltered": "(filtered1 from _MAX_ total entries)",
            "lengthMenu": "_MENU_ entries",
            "search": "Search:",
            "zeroRecords": "No matching records found"
        },
        //responsive: true,
        "order": [
            [0, 'asc']
        ],
        "lengthMenu": [
            [5, 10, 15, 20, -1],
            [5, 10, 15, 20, "All"]
        ],
        "buttons": [
            { extend: 'print', className: 'btn blue' },
            { extend: 'copy', className: 'btn blue' },
            { extend: 'pdf', className: 'btn blue' },
            { extend: 'excel', className: 'btn blue' },
            { extend: 'csv', className: 'btn blue' },
            { extend: 'colvis', className: 'btn blue', text: 'Columns'}
        ],

        "pageLength": 50,
        "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>" // horizobtal scrollable datatable

    });

    table.on( 'order.dt search.dt', function () {
        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    yadcf.init(table, [{

            filter_type: "text",
            filter_delay: 500,
            column_number: 1
        }, {
            filter_type: "text",
            filter_delay: 500,
            column_number: 2
        }, {
            filter_type: "text",
            filter_delay: 500,
            column_number: 3
        }, {
            filter_type: "text",
            filter_delay: 500,
            column_number: 4
        }, {
            filter_type: "text",
            filter_delay: 500,
            column_number: 5
        }, {
            filter_type: "text",
            filter_delay: 500,
            column_number: 6
        }, {
            filter_type: "text",
            filter_delay: 500,
            column_number: 7
        }],
        {
            filters_position: 'footer',
            cumulative_filtering: true
        });

    $.ajax({

        url: "<?php echo base_url('air_import/air_import_con/get_masters'); ?>",
        type: "POST",
        dataType: "JSON",
        data:{
            "<?php echo $this->security->get_csrf_token_name(); ?>":"<?php echo $this->security->get_csrf_hash(); ?>"
        },
        success: function(data){

            $('#airline').html('<option value=""> --- </option>');

            for(var i=0;i<data.job_type.length;i++){
                $('#job_type').append('<option value="'+data.job_type[i].id+'">'+data.job_type[i].name+'</option>');
            }

            for(var i=0;i<data.shipment_type.length;i++){
                $('#shipment_type').append('<option value="'+data.shipment_type[i].id+'">'+data.shipment_type[i].name+'</option>');
            }

            for(var i=0;i<data.agent.length;i++){
                $('#agent').append('<option value="'+data.agent[i].id+'">'+data.agent[i].client_code+' - '+data.agent[i].company_name+'</option>');
            }

            for(var i=0;i<data.airline.length;i++){
                $('#airline').append('<option value="'+data.airline[i].id+'">'+data.airline[i].client_code+' - '+data.airline[i].company_name+'</option>');
            }

            for(var i=0;i<data.inco_term.length;i++){
                $('#inco_term').append('<option value="'+data.inco_term[i].id+'">'+data.inco_term[i].code+'</option>');
            }

            for(var i=0;i<data.job_currency.length;i++){
                $('#job_currency').append('<option value="'+data.job_currency[i].id+'">'+data.job_currency[i].code+'</option>');
            }

            for(var i=0;i<data.consignee.length;i++){
                $('#consignee').append('<option value="'+data.consignee[i].id+'">'+data.consignee[i].client_code+' - '+data.consignee[i].company_name+'</option>');
            }

            for(var i=0;i<data.shipper.length;i++){
                $('#shipper').append('<option value="'+data.shipper[i].id+'">'+data.shipper[i].client_code+' - '+data.shipper[i].company_name+'</option>');
            }

            for(var i=0;i<data.sales_person.length;i++){
                $('#sales_person').append('<option value="'+data.sales_person[i].id+'">'+data.sales_person[i].first_name+' ('+data.sales_person[i].title_name+')</option>');
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            bootbox.alert(textStatus + " : " + errorThrown);
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }

    });
});

$('#job_type').change(function(){

    if($('#job_type').val() == 8){
        $('#hbl_no_div').hide();
    }
    else if($('#job_type').val() == 9){
        $('#hbl_no_div').show();
    }
});

function reload_table(table)
{
    if(typeof table !== "undefined")
    {
        table.ajax.reload(null,false);
    }
}

$("#booking_form :input").change(function(){
    $(this).siblings("span.error-block").html("").hide();
    $("span.help-inline").hide();
});

function add_new(){

    $('#hbl_no_div').hide();

    $.ajax({

        url: "<?php echo base_url('air_import/air_import_con/get_last_job_number'); ?>",
        type: "POST",
        dataType: "JSON",
        data:{
            "<?php echo $this->security->get_csrf_token_name(); ?>":"<?php echo $this->security->get_csrf_hash(); ?>"
        },
        success: function(data){
            $('#job_number_span').html('<b>JOB NUMBER : '+data.job_number+'</b>');

        },
        error: function(jqXHR, textStatus, errorThrown){
            bootbox.alert(textStatus + " : " + errorThrown);
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }

    });


    $("#booking_form :input").siblings("span.error-block").html("").hide();
    $("span.help-inline").hide();

    save_method = 'add';
    $('#booking_form')[0].reset();
    $('#booking_form :input').removeClass('has-error');
    $('#add_new_modal').modal({backdrop: 'static', keyboard: false});
    $('#add_new_modal .modal-title').text('Air Import - New Booking');

}

</script>

<script>

function edit_booking(id){

    html2 ='';
    counter=0;
    save_method='update';

    $.ajax({

        url: "<?php echo base_url('air_import/air_import_con/edit_booking'); ?>/"+id,
        type: "POST",
        dataType: "JSON",
        data:{
            "<?php echo $this->security->get_csrf_token_name(); ?>":"<?php echo $this->security->get_csrf_hash(); ?>"
        },
        success: function(data){

            $('[name="id"]').val(id);
            $('[name="job_type"]').val(data.booking.job_type);
            $('[name="shipment_type"]').val(data.booking.shipment_type);
            $('[name="airline"]').val(data.booking.airline);
            $('[name="agent"]').val(data.booking.agent);
            $('[name="pod"]').val(data.booking.pod ? data.booking.pod:"");
            $('[name="etd_pod"]').val(data.booking.etd_pod ? data.booking.etd_pod:"");
            $('[name="eta_destination"]').val(data.booking.eta_destination ? data.booking.eta_destination:"");
            $('[name="main_line_co_loader"]').val(data.booking.main_line_co_loader ? data.booking.main_line_co_loader:"");
            $('[name="mbl_no"]').val(data.booking.mbl_no ? data.booking.mbl_no:"");
            $('[name="hbl_no"]').val(data.booking.hbl_no ? data.booking.hbl_no:"");
            $('[name="mbl_freight_term"]').val(data.booking.mbl_freight_term);
            $('[name="original_main_line"]').val(data.booking.original_main_line ? data.booking.original_main_line:"");
            $('[name="original_mbl_no"]').val(data.booking.original_mbl_no ? data.booking.original_mbl_no:"");
            $('[name="serial_number"]').val(data.booking.serial_number ? data.booking.serial_number:"");
            $('[name="job_currency"]').val(data.booking.job_currency ? data.booking.job_currency:"");
            $('[name="buying_rate"]').val(data.booking.buying_rate ? data.booking.buying_rate:"");
            $('[name="selling_rate"]').val(data.booking.selling_rate ? data.booking.selling_rate:"");
            $('[name="inco_term"]').val(data.booking.inco_term ? data.booking.inco_term:"");
            $('[name="sales_person"]').val(data.booking.sales_person);
            $('[name="pieces"]').val(data.booking.pieces ? data.booking.pieces:"");
            $('[name="pieces_type"]').val(data.booking.pieces_type);
            $('[name="gross_weight"]').val(data.booking.gross_weight ? data.booking.gross_weight:"");
            $('[name="chargeable_weight"]').val(data.booking.chargeable_weight ? data.booking.chargeable_weight:"");
            $('[name="cbm"]').val(data.booking.cbm ? data.booking.cbm:"");

            $('#job_number_span').html('<b>JOB NUMBER : '+data.booking.job_number+'</b>');

            if(data.cntr){

                if(data.cntr.length > 0){

                    for(var i=0;i<data.cntr.length;i++){

                        $('#cntr_id_'+(i+1)).val(data.cntr[i].cntr_id);
                        $('#cntr_no_'+(i+1)).val(data.cntr[i].cntr_no ? data.cntr[i].cntr_no:"");
                        $('#onload_'+(i+1)).val(data.cntr[i].onload ? data.cntr[i].onload:"");
                        $('#offload_'+(i+1)).val(data.cntr[i].offload ? data.cntr[i].offload:"");
                        $('#cntr_eta_'+(i+1)).val(data.cntr[i].eta ? data.cntr[i].eta:"");
                        $('#cntr_etd_'+(i+1)).val(data.cntr[i].etd ? data.cntr[i].etd:"");
                        $('#cntr_pieces_'+(i+1)).val(data.cntr[i].pieces ? data.cntr[i].pieces:"");
                        $('#cntr_weight_'+(i+1)).val(data.cntr[i].weight ? data.cntr[i].weight:"");
                        $('#cntr_volume_'+(i+1)).val(data.cntr[i].weight ? data.cntr[i].volume:"");
                    }
                }
            }

            $('#add_new_modal .modal-title').text('Edit Book Number :'+data.booking.job_number);
            $('#add_new_modal').modal({backdrop: 'static', keyboard: false});
        },
        error: function(jqXHR, textStatus, errorThrown){
            bootbox.alert(textStatus + " : " + errorThrown);
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }

    });
}


function save(){

    var url;

    if(save_method == 'add'){
        url="<?php echo base_url(); ?>air_import/air_import_con/save_booking";
    }
    else if(save_method == 'update'){
        var id=$('#id').val();
        url="<?php echo base_url(); ?>air_import/air_import_con/update_booking/"+id;
    }

    $.ajax({

        url: url,
        type: "POST",
        dataType: "JSON",
        data:$('#booking_form').serialize()+"&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>",
        success: function(data){

            if(data.input_error)
            {
                for (var i = 0; i < data.input_error.length; i++)
                {
                    $('[name="'+data.input_error[i]+'"]').siblings("span.error-block").html(data.error_string[i]).show();
                }
            }
            else if(data.status){
                reload_table(table);
                $('#add_new_modal').modal('hide');
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            bootbox.alert(textStatus + " : " + errorThrown);
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }

    });
}

function view_booking(id){


    $.ajax({

        url: "<?php echo base_url('air_import/air_import_con/view_booking'); ?>/"+id,
        type: "POST",
        dataType: "JSON",
        data:{
            "<?php echo $this->security->get_csrf_token_name(); ?>":"<?php echo $this->security->get_csrf_hash(); ?>"
        },
        success: function(data){

            $('[name="id"]').val(id);
            $('[name="job_type"]').val(data.booking.job_type);
            $('[name="shipment_type"]').val(data.booking.shipment_type);
            $('[name="airline"]').val(data.booking.airline);
            $('[name="agent"]').val(data.booking.agent);
            $('[name="pod"]').val(data.booking.pod ? data.booking.pod:"");
            $('[name="etd_pod"]').val(data.booking.etd_pod ? data.booking.etd_pod:"");
            $('[name="eta_destination"]').val(data.booking.eta_destination ? data.booking.eta_destination:"");
            $('[name="main_line_co_loader"]').val(data.booking.main_line_co_loader ? data.booking.main_line_co_loader:"");
            $('[name="mbl_no"]').val(data.booking.mbl_no ? data.booking.mbl_no:"");
            $('[name="hbl_no"]').val(data.booking.hbl_no ? data.booking.hbl_no:"");
            $('[name="mbl_freight_term"]').val(data.booking.mbl_freight_term);
            $('[name="original_main_line"]').val(data.booking.original_main_line ? data.booking.original_main_line:"");
            $('[name="original_mbl_no"]').val(data.booking.original_mbl_no ? data.booking.original_mbl_no:"");
            $('[name="serial_number"]').val(data.booking.serial_number ? data.booking.serial_number:"");
            $('[name="job_currency"]').val(data.booking.job_currency ? data.booking.job_currency:"");
            $('[name="buying_rate"]').val(data.booking.buying_rate ? data.booking.buying_rate:"");
            $('[name="selling_rate"]').val(data.booking.selling_rate ? data.booking.selling_rate:"");
            $('[name="slpa_reference"]').val(data.booking.slpa_reference ? data.booking.slpa_reference:"");


            if(data.cntr){

                $('#cntr_no_'+i).val(data.cntr[0].cntr_no);
                $('#seal_no_'+i).val(data.cntr[0].seal_no);
                $('#cntr_size_'+i).val(data.cntr[0].cntr_size);
                $('#cntr_type_'+i).val(data.cntr[0].cntr_type);
                $('#ship_type_'+i).val(data.cntr[0].ship_type);
                $('#edi_code_'+i).val(data.cntr[0].edi_code);
                $('#status_'+i).val(data.cntr[0].status);
                $('#no_of_pkg_'+i).val(data.cntr[0].no_of_pkg);
                $('#gross_wt_'+i).val(data.cntr[0].gross_wt);
            }

            $('#view_modal').modal({backdrop: 'static', keyboard: false});
            $('#view_modal .modal-title').text('View Client '+data.booking.job_number);
        },
        error: function(jqXHR, textStatus, errorThrown){
            bootbox.alert(textStatus + " : " + errorThrown);
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }

    });
}
</script>

<script>
    $(".allownumericwithdecimal").on("keypress keyup blur",function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57) && event.which !=8) {
            event.preventDefault();
        }
    });

    $("#mbl_no").mask("999-99999999");
</script>

<script>
    function delete_cntr(id){
        $.ajax({

            url: "<?php echo base_url('air_import/air_import_con/delete_cntr'); ?>/"+id,
            type: "POST",
            dataType: "JSON",
            data:{
                "<?php echo $this->security->get_csrf_token_name(); ?>":"<?php echo $this->security->get_csrf_hash(); ?>"
            },
            success: function(data){
                console.log('ok');
            },
            error:function(){
                console.log('error');
            }
        });
    }
</script>