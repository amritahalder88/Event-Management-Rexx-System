<?php include_once("json_conn.php");?>
<?php include_once("controller.php");?>
<?php $fetchdata = new DB_con(); ?>

<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Management - Rexx System By Amrita Halder</title>
    
    <!-- DATATABLE JQURY AND CSS STARTS-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"/>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <!-- DATATABLE JQURY AND CSS ENDS-->

    <!-- DATEPICKER STARTS-->
    <script type="text/javascript" src="https://formden.com/static/cdn/formden.js"></script>
    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />
    <link rel="stylesheet" href="https://formden.com/static/cdn/font-awesome/4.4.0/css/font-awesome.min.css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
    <style>
      .bootstrap-iso .dropdown-menu { position:absolute; left:0px !important;}
    </style>
    <!-- DATEPICKER ENDS -->
  </head>

  <!-- CUSOM STYLE FOR DATATABLE STARTS-->
  <style type="text/css">
    .table_container { padding:20px; }

    .table { width:100% !important; } 
    .table-striped { width:100% !important; } 
    .table-bordered { width:100% !important; } 
    .dataTable { width:100% !important; }
    .dataTables_length { width:50% !important; }
    .search_container { width 100%; padding 20px; }
    #div_error { color:#b51f09; font-size: 14px; display:none; padding-top:10px; }
  </style>
  <!-- CUSOM STYLE FOR DATATABLE ENDS-->

  <body>
    <div class="search_container">    
      <br />
      <h3 align="center">Event Management - Rexx System</h3>
      <br />
      <br />
      <div class="row">
        <div class="col-md-2" style="margin-left:15px;">
          <input type="text" name="employee_name" id="employee_name" class="form-control" placeholder="Employee Name"  />
        </div>
        <div class="col-md-2">
          <?php $fetchevent = new DB_con(); ?>
          <select class="form-control" id="event_id" name="event_id">
            <option value="0">Select Event</option>
            <?php
              $sql=$fetchevent->fetchevent();
              while($row=mysqli_fetch_array($sql)) {
                if(!empty($row) && count($row) > 0) {
            ?>
            <option value="<?php echo $row['event_id']; ?>"><?php echo $row['event_name']; ?></option>
            <?php
                }
              }
            ?>
          </select>
        </div>
        <br><br>

        <div class="col-md-2">
          <div class="bootstrap-iso">
            <form action="" class="form-horizontal" method="post">
              <input class="form-control" name="event_date" id="event_date" placeholder="Event Date(YYYY-MM-DD)" type="text"/>
            </form>
          </div>
        </div>
      </div>
   
      <div class="col-md-4">
        <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
        <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
      </div>
      <div class="col-md-8" id="div_error"></div>
    </div>
    <br />

    <div class="table_container">
      <table id="display_record" class="table table-striped table-bordered"> 
        <thead style="background-color: #8A8F93; color: #FFF;">
          <tr>
            <th>EMPLOYEE NAME</th>
            <th>EMPLOYEE EMAIL</th>
            <th>EVENT NAME</th>
            <th>PARTICIPATION FEES</th>
            <th>EVENT DATE</th>
            <th>VERSION</th>
          </tr> 
        </thead>
        <tfoot>
          <tr>
            <th colspan="3" id="total_fees"> </th>
            <th colspan="3"> </th>
          </tr> 
        </tfoot>
      </table>

      <table border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td class="gutter">
              <div class="line number1 index0 alt2" style="display: none;">1</div>
            </td>
            <td class="code">
              <div class="container" style="display: none;">
                <div class="line number1 index0 alt2" style="display: none;">&nbsp;</div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

    </div>
  </body>
</html>

<script>
$(document).ready(function() {
  search_data(employee_name='', event_id=0, event_date='');

  //CALLING DATEPICKER
  var date_input=$('input[name="event_date"]');
  var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
  date_input.datepicker({
    format: 'yyyy-mm-dd',
    container: container,
    todayHighlight: true,
    autoclose: true,
  })

  //FETCHING DATA FOR DATATABLE
  function search_data(employee_name, event_id, event_date) 
  {
    $('#display_record').DataTable({
      processing: true,
      serverSide: false,
      ajax: {
        type: 'POST',
        data: {employee_name: employee_name, event_id:event_id, event_date:event_date},
        url: 'controller.php'
      },
      columns: [
        { data: "employee_name" },
        { data: "employee_mail" },
        { data: "event_name" },
        { data: "participation_fee" },
        { data: "event_date" },
        { data: "version" }
      ],

      //FOR TOTAL COUNT
      "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;
        // REMOVE THE FORMATTING TO GET INTEGER DATA FOR SUMATION
        var intVal = function ( i ) {
          return typeof i === 'string' ?
          i.replace(/[\$,]/g, '')*1 :
          typeof i === 'number' ?
          i : 0;
        };

        // TOTAL OVER ALL PAGES
        total = api
        .column( 3 )
        .data()
        .reduce( function (a, b) {
          return intVal(a) + intVal(b);
        }, 0 );

        // TOTAL OVER THIS PAGE
        pageTotal = api
        .column( 3, { page: 'current'} )
        .data()
        .reduce( function (a, b) {
          return intVal(a) + intVal(b);
        }, 0 );

        // UPDATE FOOTER
        $( api.column( 3 ).footer() ).html("TOTAL FEES: "+
          Math.round(pageTotal * 100) / 100 // (round at most 2 decimal places, but only if necessary)
        );
      }
    });
   }

  //FILTER BUTTON CLICK FUNCTION
  $('#filter').click(function() {
    var employee_name = $('#employee_name').val();
    var event_id = $('#event_id').val();
    var event_date = $('#event_date').val();

    if((employee_name == '') && (event_id == 0) && (event_date == '')) {
      var message = "Select atleast one field";
      $("#div_error").html(message);
      $("#div_error").show();
    }
    else {
      $("#div_error").hide();
      $('#display_record').DataTable().destroy();
      search_data(employee_name,event_id,event_date);
    }
  });

  //REFRESH BUTTON CLICK FUNCTION
  $('#refresh').click(function(){
    $('#display_record').DataTable().destroy();
    $('#employee_name').val('');
    $('#event_id').val(0);
    $('#event_date').val('');
    $("#div_error").hide();
    search_data(employee_name='', event_id=0, event_date='');
  });
});
</script>