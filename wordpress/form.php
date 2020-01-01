<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lookup LatLng Form</title>

    <!-- Bootstrap -->
    <link href="/bootstrap-dist-3.1.1/css/bootstrap.css" rel="stylesheet">
    <link href="/bootstrapvalidator-dist-0.4.5/dist/css/bootstrapValidatorDev.min.css" rel="stylesheet"/>
    <link href="/eternicode-bootstrap-datepicker/css/datepicker.css" rel="stylesheet">
    <link href="/eternicode-bootstrap-datepicker/css/datepicker3.css" rel="stylesheet">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

    <style type="text/css">
      /* Custom container */
      .container-fixed {
        margin: 0 auto;
        max-width: 960px;
      }
      .container-fixed > hr {
        margin: 30px 0;
      }

      /* Custom modal position */
/*      .modal-dialog-center {
        margin: 0;
        position: absolute;
        top: 50%;
        left: 50%;
      }

      .modal-body {
        overflow-y: auto;
      }
      .modal-footer {
        margin-top: 0;
      }

      @media (max-width: 767px) {
        .modal-dialog-center {
          width: 100%;
        }
      }*/

      /* modal feedback */
      /*div.modal-feedback.error {color: #ff0000;}
      div.modal-feedback.success {color: #33CC00;} */
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>  
  <body onload="initialize()">
    <br>
    <div class="container-fixed">
      <div class="jumbotron text-center">
        <h2> Courier Service Pickup</h2>
        <br>
        <form class="form-horizontal" id="pickupForm" type="POST">
          <div class="form-group">
              <label class="col-lg-3 control-label">Name<sup>*</sup></label>
              <div class="col-lg-4">
                  <input type="text" class="form-control" name="firstName" placeholder="First Name" data-bv-notempty data-bv-notempty-message="First Name is required"/>
              </div>
              <div class="col-lg-4">
                  <input type="text" class="form-control" name="lastName" placeholder="Last Name" />
              </div>
          </div>
          <div class="form-group">
              <label class="col-lg-3 control-label">Mobile Number<sup>*</sup></label>
              <div class="col-lg-4">
                  <input type="text" class="form-control" name="telNo" id="telNo" placeholder="Mobile Number" >
              </div>
              <div class="col-lg-1">
                <button type="button" class="btn btn-info btn-sm" id="verifyMobile">Verify Mobile No</button>
              </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Pickup contact is not myself</label>
            <div class="col-lg-2">
              <div class="checkbox">            
                  <input type="checkbox" id="pickupContactCheck" name="pickupContactCheck" value="No">              
              </div>
            </div>
          </div>
          <div id="toggleContact" class="toggleContact" style="display:none;">
            <div class="form-group">
                <label class="col-lg-3 control-label">Pickup Contact Name<sup>*</sup></label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" name="pickupContactName" placeholder="Pickup Contact Name" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Pickup Contact Number<sup>*</sup></label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" name="pickupContactNo" placeholder="Pickup Contact Number" />
                </div>
            </div>
          </div>
          <div class="form-group">
              <label class="col-lg-3 control-label">Email Address<sup>*</sup></label>
              <div class="col-lg-4">
                  <input type="text" class="form-control" name="emailAdd" placeholder="Email Address" />
              </div>
          </div>
          <div class="form-group">
              <label class="col-lg-3 control-label">Pickup Address Postal Code<sup>*</sup></label>
              <div class="col-lg-4">
                <div class="input-group">
                  <span class="input-group-addon">Singapore</span>
                  <input type="text" class="form-control" name="postalCode" id="postalCode" placeholder="Postal Code" />
                </div>
              </div>
              <div class="col-lg-1">
                <button type="button" class="btn btn-info btn-sm" id="lookupBtn" onclick="codeAddress()">Lookup Address</button>
              </div>              
          </div>
          <div class="form-group">
            <input id="latlng" value="">
            <button type="button" class="btn btn-info btn-sm" id="lookupLatLngBtn" onclick="codeLatLng()">Lookup LatLng</button>
            <input id="lat" value="">
            <input id="lng" value="">
          </div>
          <div class="form-group">
              <label class="col-lg-3 control-label">Pickup Address Street Name</label>
              <div class="col-lg-4">
                  <input type="text" class="form-control" id="postalAdd" name="postalAdd" />
              </div>
          </div>
          <div class="form-group">
              <label class="col-lg-3 control-label">Unit Number (if any)</label>
              <div class="col-lg-2">
                <div class="input-group">
                  <span class="input-group-addon">#</span>
                  <input type="text" class="form-control" name="floorNo" placeholder="Floor" />
                </div>
              </div>
              <div class="col-lg-2">
                <div class="input-group">
                  <span class="input-group-addon">-</span>
                  <input type="text" class="form-control" name="unitNo" placeholder="Unit" />
                </div>
              </div>
          </div>
          <div class="form-group">
              <label class="col-lg-3 control-label">Pickup Date/Time<sup>*</sup></label>
              <!-- default datepicker -->
              <!-- <div class="col-lg-4"> 
                  <input type="date" class="form-control" name="pickupDate" placeholder="Pickup Date" />
              </div> --> 
              <!-- datepicker plugin --> 
              <div class="col-lg-4">
                  <input id="pickupDate" class="form-control" type="text" name="pickupDate" placeholder="Pickup Date" />

              </div>
              <div class="col-lg-4">
                  <select class="form-control" name="pickupTime" id="pickupTime">
                    <option value="">Pickup Time</option>
                    <option value="12:00:00">Before 12PM</option>
                    <option value="17:00:00">12PM - 5PM</option>
                    <option value="19:00:00">5PM - 7PM</option>
                  </select>
              </div>
          </div>
          <div class="form-group">
              <label class="col-lg-3 control-label">Delivery Date/Time<sup>*</sup></label>
              <!-- datepicker plugin --> 
              <div class="col-lg-4">
                  <input id="deliveryDate" class="form-control" type="text" name="deliveryDate" placeholder="Delivery Date" />
              </div>
              <div class="col-lg-4">
                  <select class="form-control" name="deliveryTime" id="deliveryTime">
                    <option value="">Delivery Time</option>
                    <option value="12:00:00">Before 12PM</option>
                    <option value="17:00:00">12PM - 5PM</option>
                    <option value="19:00:00">5PM - 7PM</option>
                  </select> 
              </div>
          </div>
          <button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
          <!-- <button class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Modal</button> -->
        </form> <!-- form end -->
        <!-- Confirmation Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centre">
            <div class="modal-content">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <div class="modal-body" id="modal-body">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="btnModalClose">Close</button>
              </div>
            </div>
          </div>
        </div> <!-- End of Modal -->
        
        <!-- Verification Modal -->
        <div class="modal fade" id="myVerifyModal" tabindex="-1" role="dialog" aria-labelledby="myVerifyModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centre">
            <div class="modal-content">
              <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                <h4 class="modal-title">Verify Mobile No</h4>
              </div>
              <!-- <div class="alert alert-warning" id="modal-feedback" aria-hidden="true"> -->
                <div class="modal-feedback">
                </div>
              <!-- </div> -->
              <div class="modal-body">
                <form class="form-horizontal" id="verifyForm" type="POST">
                  <!-- <div class="modal-feedback">
                  </div><br><br> -->
                  <div class="form-group">
                    <label class="col-md-5 control-label">Verification Key<sup>*</sup></label>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="verifyKey" />
                    </div>
                  </div>
                  <div class="modal-footer">
                    <!-- <div class="col-md-10" id="verifyDiv"> -->
                        <button type="button" class="btn btn-default" id="btnVerify">Verify</button>
                        <button type="button" class="btn btn-default" id="btnResendSMS" data-loading-text="Sending..." style="display: none">Resend SMS</button>
                    <!-- </div> -->
                  </div>
                </form> <!-- end of verifyForm -->
              </div>
            </div>
          </div>
        </div> <!-- End of Modal -->
      </div> 
  </div>
    <!-- PHP Pass Generator -->
    <?php
    function random_password( $length = 8 ) {
      $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ0123456789";
      $password = substr(str_shuffle( $chars ), 0, $length );
      return $password;
    }
    $key = random_password(6);
    ?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <!-- Bootstrapvalidator & Geocode JS -->
    <script>
    /* ---------------------------------------------------- START OF GOOGLEMAPS JS ------------------------------------------------------------- */
      //geocode
      var geocoder;
      var map;
      function initialize() {
        geocoder = new google.maps.Geocoder();
        //var latlng = new google.maps.LatLng(-34.397, 150.644);
      }
      function codeAddress() {
        var address = "singapore " + document.getElementById('postalCode').value;
        geocoder.geocode( { 'address': address, 'country': 'SG'}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
          document.getElementById('latlng').value = results[0].geometry.location;
          var input = document.getElementById('latlng').value;
          var str3 = input.match(/\((.*?)\)/)[1];
          var latlngStr = str3.split(',', 2);
          document.getElementById('latlng').value = latlngStr;
          codeLatLng();
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
      }
      function codeLatLng() {
        var input = document.getElementById('latlng').value;
        // alert(input);
        //var str3 = input.match(/\((.*?)\)/)[1];
        // var latlngStr = str3.split(',', 2);
        var latlngStr = input.split(',', 2);
        var lat = parseFloat(latlngStr[0]);
        var lng = parseFloat(latlngStr[1]);
        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;
        // alert(lat + " " + lng);
        var latlng = new google.maps.LatLng(lat, lng);
        geocoder.geocode({'latLng': latlng}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
              console.log(results[0].address_components[3]);
              // if ( results[0].address_components[3].long_name == "Singapore"|| results[0].address_components[2].long_name == "Singapore"){ 
              if ( ((results[0].formatted_address).split(",")[1]).indexOf("Singapore") >= 0 ){   
                // console.log((results[0].formatted_address).split(","));
                // console.log(((results[0].formatted_address).split(",")[1]).indexOf("Singapore"));
                // document.getElementById('postalAdd').value = results[0].formatted_address; //full address
                console.log(results);
                console.log(results[1]);
                console.log(results[1].formatted_address.split(",")[0]);
                var formatAddress = results[0].formatted_address.split(",")[0];
                 document.getElementById('postalAdd').value = formatAddress;
                //just street name
                // document.getElementById('postalAdd').value = (results[0].address_components[0].long_name).concat(" ".concat(results[0].address_components[1].long_name)); 
                // $('#postalAdd').val((results[0].formatted_address).split(",")[0]);
              }
              // else{
              //   console.log((results[0].formatted_address).split(",")[0]);
              //   $('#postalAdd').val((results[0].formatted_address).split(",")[0]);
              // }
            } else {
              alert('No results found');
            }
          } else {
            alert('Geocoder failed due to: ' + status);
          }
        });
      }
/* ---------------------------------------------------- END OF GOOGLEMAPS JS ------------------------------------------------------------- */
/* ----------------------------------------------------------- START OF JQUERY --------------------------------------------------------- */
      //readonly fields
      $('#postalAdd').attr('readonly', true);

      //adjust position of Modal
      // function adjustModalMaxHeightAndPosition(){
      //   $('.modal').each(function(){
      //     if($(this).hasClass('in') == false){
      //       $(this).show();
      //     };
      //     var contentHeight = $(window).height() - 60;
      //     var headerHeight = $(this).find('.modal-header').outerHeight() || 2;
      //     var footerHeight = $(this).find('.modal-footer').outerHeight() || 2;

      //     $(this).find('.modal-content').css({
      //       'max-height': function () {
      //         return contentHeight;
      //       }
      //     });

      //     $(this).find('.modal-body').css({
      //       'max-height': function () {
      //         return contentHeight - (headerHeight + footerHeight);
      //       }
      //     });

      //     $(this).find('.modal-dialog').addClass('modal-dialog-center').css({
      //       'margin-top': function () {
      //         return -($(this).outerHeight() / 2);
      //       },
      //       'margin-left': function () {
      //         return -($(this).outerWidth() / 2);
      //       }
      //     });
      //     if($(this).hasClass('in') == false){
      //       $(this).hide();
      //     };
      //   });
      // };
      // if ($(window).height() >= 320){
      //   $(window).resize(adjustModalMaxHeightAndPosition).trigger("resize");
      // }

      //$(document).ready(function (){
        
      //});
      var key = '<?php echo $key; ?>'; 
      var sendSMSStatus = 1;
      var alertStatus = false;
      var resendSMSStatus = false;
      // var formMessageVerify = $('#modal-feedback');
      //var formMessageVerify = $('#alertRes');

      // $(document).ready(function(){
      //   // When ever you change the postal code
      //   $('[name="emailAdd"]').on('keyup', function() {
      //       var isValidField = $('#pickupForm').data('bootstrapValidator').isValidField('emailAdd');
      //       console.log("emailAdd " + isValidField);
      //   });
      // });

      function sendSMS(){ 
        var formMessageVerify = $('#alertRes');
        if (resendSMSStatus == true){
          resendSMSStatus = false;
          //reset key
          <?php 
            $key = random_password(6);
          ?>
          key = '<?php echo $key; ?>';
        }
        
        //AJAX post - send SMS
        $.ajax({
          type: 'POST',
          url: 'sendSMS.php',
          data: 'key=' + key + '&telNo=' + $('#telNo').val(),
        })

        .always(function(){
          if (sendSMSStatus < 1){
            $('#btnResendSMS').button('reset');
          }
        })

        .done(function(response) {
          // Make sure that the formMessages div has the 'success' class.
          // $(formMessageVerify).removeClass('alert-warning');
          $(formMessageVerify).removeClass('alert-danger');
          $(formMessageVerify).addClass('alert-warning');
          $(formMessageVerify).fadeIn();

          //console.log("code-->", key);

          $('#btnVerify').prop('disabled', false);
          
          if (response != ""){
            if (sendSMSStatus < 1){
              response = "Message Resent!"
              $(formMessageVerify).text(response); // does not show after clicking resendSMS
              setTimeout(function() {
                  $('#btnResendSMS').attr('disabled', 'disabled'); // Disables the button correctly.
              }, 0);
              
            }
            else {
              // Set the message text.
              $(formMessageVerify).text(response);
            }
            if (alertStatus == false){
              alertStatus = true;
              var alert = ['<div class="alert alert-warning" id="alertRes">', response, '</div>'].join('');
              $('.modal-feedback').prepend(alert);
              // setTimeout(function() {$('#alertRes').fadeOut();}, 4000);
            }

            
          }

          else {
            response = "Message Sent!";
            $(formMessageVerify).text(response);
          }
          
        })

        .fail(function(data) {
          $(formMessageVerify).removeClass('alert-warning');
          $(formMessageVerify).addClass('alert-danger');
           // Set the message text.
          if (data.responseText !== '') {
            $(formMessageVerify).text(data.responseText);
          } 
          else {
            $(formMessageVerify).text('Oops! An error occured and records could not be saved.');
          }


        });
      }

      // var formMessageVerify = $('.modal-feedback');
      
      /* SEND PASSWORD VIA SMS */
      $('#verifyMobile').click(function(){
        //create a non-closable modal
        $('#myVerifyModal').modal({backdrop: 'static'});
        //show modal (create new modal for submitting password for verification)
        $('#myVerifyModal').modal('show');
        sendSMS();
      });

      //submit verify form
      $('#btnVerify').click(function() {
        $('#verifyForm').data('bootstrapValidator').validate();
        if ($('#verifyForm').data('bootstrapValidator').isValidField()) { 
          $('#verifyForm').submit();
          var submitNo = 0;
          submitNo++;
          console.log('submit ' + submitNo);

        }
      });

      $('#btnResendSMS').click(function(){ 
        alert('clicked resendSMS');
        resendSMSStatus = true;
        sendSMS();
        var btn = $(this);
        btn.button('loading');
      });      

      $('#verifyForm').submit(function(e) {
        // Stop the browser from submitting the form.
        e.preventDefault();

        // Serialize the form data.
        var verifyFormData = $('#verifyForm').serialize();

        var formMessageVerify = $('#alertRes');

        //AJAX post - verify with the verification key
        $.ajax({
          type: 'POST',
          url: 'verifyKey.php',
          data: verifyFormData + '&key=' + key,
        })
        
        .done(function(response) {
          //make sure formMessageVerify has alert-success class
          $(formMessageVerify).removeClass('alert-warning');
          $(formMessageVerify).removeClass('alert-danger');
          $(formMessageVerify).addClass('alert-success');
          //$(formMessageVerify).fadeIn();
          setTimeout(function() {$('#alertRes').fadeOut();}, 4000);
          // Set the message text.
          $(formMessageVerify).text(response);
          $('#btnVerify').attr('disabled', 'disabled');
          var drawButton = function(value) {
            // return ['<div class="col-md-5 col-md-offset-3"><type=button class="btn btn-danger" data-dismiss="modal">', value, '</type></div>'].join('');
            return ['<type=button class="btn btn-danger" data-dismiss="modal">', value, '</type>'].join('');
          };
          var button = drawButton('Close');
          $(button).insertAfter($('#btnVerify'));
          setTimeout(function() {$('#myVerifyModal').modal('hide');}, 4000);
          var addAlertOnTop = ['<div class="alert alert-success alert-dismissable" id="alertTop"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', response, '</div>'].join('');
          $('.jumbotron.text-center').prepend(addAlertOnTop);
          $('#verifyMobile').prop('disabled', true); //disable verifyMobile btn 
          if (sendSMSStatus < 1){
            $('#btnResendSMS').prop('disabled', true);
          }
        })

        .fail(function(data) {
          sendSMSStatus = sendSMSStatus - 1;
          $('#btnResendSMS').prop('disabled', false);
           // Set the message text.
          if (data.responseText !== '') {
            $(formMessageVerify).text(data.responseText);
            $(formMessageVerify).removeClass('alert-warning');
            $(formMessageVerify).removeClass('alert-success');
            $(formMessageVerify).addClass('alert-danger');
            if (sendSMSStatus == 0){
              $(formMessageVerify).fadeIn();
            }
            //setTimeout(function() {$('#alertRes').fadeOut();}, 4000);
          } 
          else {
            // $(formMessageVerify).text('Oops! An error occured and records could not be saved.');
          }
          if (sendSMSStatus == 0){ //enable ResendSMS button on first verify key fail
            $('#btnResendSMS').removeAttr('style');
          }
          alert('fail sendSMS' + sendSMSStatus);
          $('#btnVerify').prop('disabled', true);
                    
        });
        return false;
      }); //end verifyForm submit

      /* FORM SUBMIT */
      // Get the form.
      var form = $('#pickupForm');

      $('#btnSubmit').click(function(){
        form.submit();
      });

      // Get the messages div.
      var formMessages = $('#modal-body');

      // Set up an event listener for the pickup form.
      $(form).submit(function(e) {
        // Stop the browser from submitting the form.
        e.preventDefault();

        // Serialize the form data.
        var formData = $(form).serialize();

        // Submit the form using AJAX.
        $.ajax({
          type: 'POST',
          url: 'formProcess.php',
          data: formData,
          success: function(){
                 $('#pickupForm').unbind('submit');
               }
        })

        .done(function(response) {
          // Make sure that the formMessages div has the 'success' class.
          $(formMessages).removeClass('error');
          $(formMessages).addClass('success');

          // Set the message text.
          $(formMessages).text(response);

          //show modal
          $('#myModal').modal('show');
          setTimeout(function() {$('#myModal').modal('hide');}, 4000);
          $('#myModal').on('hide.bs.modal', function() {
            // form.bootstrapValidator('disableSubmitButtons', false)
            // form.bootstrapValidator('resetForm', true); 
            // $('#postalAdd').val('');
            // $('#lookupBtn').prop('disabled', true); 
            location.reload('true'); //refresh page
          });
          

        })
        .fail(function(data) {
          // Make sure that the formMessages div has the 'error' class.
          $(formMessages).removeClass('success');
          $(formMessages).addClass('error');

          // Set the message text.
          if (data.responseText !== '') {
            $(formMessages).text(data.responseText);
          } else {
            $(formMessages).text('Oops! An error occured and records could not be saved.');
          }
          //$(form).data('bootstrapValidator').validate();
          if($(form).data('bootstrapValidator').isValid()) {
            //show modal
            $('#myModal').modal('show');
            setTimeout(function() {$('#myModal').modal('hide');}, 4000);
            $('#btnSubmit').removeAttr('disabled'); //re-enable the submit button   
          }
        });
        return false;
        
      });


      //pickupContact toggle/fadein
      $('#pickupContactCheck').click(function(){

        $('#toggleContact').toggle(this.checked);
      });

      /* DATEPICKER FUNC /*
      /*    datepicker   GLOBAL VAR */
      var strPickupStartDate, strPickupEndDate, strDeliveryStartDate, strDeliveryEndDate, dtDeliveryEndDate;
      var rawDate = new Date();
      var mth_names = new Array("January", "February", "March", 
        "April", "May", "June", "July", "August", "September", 
        "October", "November", "December"); 
      
      ///////////////////////////////////////////////////////////////////////////////////////////////////////

      //init datepicker
      function initDatepicker() {  
        var pickupDateVal = document.getElementById("pickupDate").value; 
        var dtCurrentDate = new Date(rawDate.getFullYear(), rawDate.getMonth(), rawDate.getDate()); 
        pickupDateParts = pickupDateVal.split("/");

        if  (pickupDateVal != "") { //init delivery date/time
          pickupDateDay = pickupDateParts[0];
          pickupDateMth = +pickupDateParts[1] - 1;
          pickupDateYear = pickupDateParts[2]; 

          var pickupTimeVal = document.getElementById("pickupTime").value;
          if (pickupTimeVal == "17:00:00" || pickupTimeVal == "19:00:00"){
            dtDeliveryStartDate = new Date(pickupDateYear, pickupDateMth, (+pickupDateDay + 1));
            strDeliveryStartDate =  (((dtDeliveryStartDate.getDate() + 1) < 10)?"0":"") + dtDeliveryStartDate.getDate() + "/" + (((dtDeliveryStartDate.getMonth() + 1) < 10)?"0":"") + (dtDeliveryStartDate.getMonth() + 1) +"/" + dtDeliveryStartDate.getFullYear();
            dtDeliveryEndDate = new Date(dtDeliveryStartDate.getFullYear(), (dtDeliveryStartDate.getMonth()), ((+dtDeliveryStartDate.getDate()) + 7));
            strDeliveryEndDate =  (((dtDeliveryEndDate.getDate() + 1) < 10)?"0":"") + dtDeliveryEndDate.getDate() + "/" + (((dtDeliveryEndDate.getMonth() + 1) < 10)?"0":"") + (dtDeliveryEndDate.getMonth() + 1) +"/" + dtDeliveryEndDate.getFullYear();
            //alert("In pickupTime more than 12pm\n strDeliveryStartDate = " + strDeliveryStartDate);
            //alert("In pickupTime more than 12pm\n strDeliveryEndDate = " + strDeliveryEndDate);
          }
          
          else { 
            strDeliveryStartDate = pickupDateDay + "/" + (((pickupDateMth + 1) < 10)?"0":"") + (pickupDateMth + 1 ) +"/" + pickupDateYear;
            //alert("strDeliveryStartDate = " + strDeliveryStartDate);
            dtDeliveryEndDate = new Date(pickupDateYear, pickupDateMth, (+pickupDateDay + 7));
            strDeliveryEndDate =  (((dtDeliveryEndDate.getDate() + 1) < 10)?"0":"") + dtDeliveryEndDate.getDate() + "/" + (((dtDeliveryEndDate.getMonth() + 1) < 10)?"0":"") + (dtDeliveryEndDate.getMonth() + 1) +"/" + dtDeliveryEndDate.getFullYear();
            //alert("strDeliveryEndDate = " + strDeliveryEndDate);
        }

        }
        else{ //pickupDate empty, init pickup start/end date
          //if current time > 1700 then set start date +1
          if(Date.parse(mth_names[rawDate.getMonth()] + " " + rawDate.getDate() + " " + rawDate.getFullYear() + " " + 
            rawDate.getHours() + ":" + rawDate.getMinutes() + ":" + rawDate.getSeconds() + ":" + rawDate.getMilliseconds()) > 
            Date.parse(mth_names[rawDate.getMonth()] + " " + rawDate.getDate() + " " + rawDate.getFullYear() + " " + "17:00:00:00"))
          {    
            var dtNewPickupDate = new Date(rawDate.getFullYear(), rawDate.getMonth(), (+rawDate.getDate() + 1)); //increase rawDate by 1 day
            //alert("dtNewPickupDate="+dtNewPickupDate);
            strPickupStartDate = (((dtNewPickupDate.getDate()) < 10)?"0":"") + (dtNewPickupDate.getDate()) + "/" + (((dtNewPickupDate.getMonth()+1) < 10)?"0":"") + (dtNewPickupDate.getMonth()+1) + "/" + dtNewPickupDate.getFullYear();
            var dtNewPickupEndDate = new Date(dtNewPickupDate.getFullYear(), (dtNewPickupDate.getMonth()+1), (+dtNewPickupDate.getDate()) + 3);  
            //alert("dtNewPickupEndDate="+dtNewPickupEndDate);
            strPickupEndDate = (((dtNewPickupEndDate.getDate()) < 10)?"0":"") + (dtNewPickupEndDate.getDate()) + "/" + (((dtNewPickupEndDate.getMonth()) < 10)?"0":"") + (dtNewPickupEndDate.getMonth()) + "/" + dtNewPickupEndDate.getFullYear();
            //alert("in if:init pickup start and end date: \n" + strPickupStartDate + "--" + strPickupEndDate);
            strDeliveryStartDate = strPickupStartDate;
            dtDeliveryEndDate = new Date(dtNewPickupDate.getFullYear(), dtNewPickupDate.getMonth(), (+dtNewPickupDate.getDate() + 7));
            strDeliveryEndDate = dtDeliveryEndDate.getDate() + "/" + (dtDeliveryEndDate.getMonth()+1) + "/" + dtDeliveryEndDate.getFullYear();
            //alert(dtDeliveryEndDate + strDeliveryEndDate);

          }
          else{ 
            strPickupStartDate = ((rawDate.getDate() < 10)?"0":"") + (rawDate.getDate()) + "/" + (((rawDate.getMonth()+1) < 10)?"0":"") + (rawDate.getMonth()+1) + "/" + rawDate.getFullYear() ;
            dtPickupEndDate = new Date(rawDate.getFullYear(), rawDate.getMonth(), (+rawDate.getDate() + 3));
            strPickupEndDate = (((dtPickupEndDate.getDate()+3) < 10)?"0":"") + (dtPickupEndDate.getDate()) + "/" + (((dtPickupEndDate.getMonth()+1) < 10)?"0":"") + (dtPickupEndDate.getMonth()+1) + "/" + dtPickupEndDate.getFullYear();
            //alert("init pickup start and end date: \n" + strPickupStartDate + "--" + strPickupEndDate);
            strDeliveryStartDate = strPickupStartDate;
            dtDeliveryEndDate = new Date(rawDate.getFullYear(), (rawDate.getMonth()), (rawDate.getDate() + 7));
            strDeliveryEndDate = dtDeliveryEndDate.getDate() + "/" + (dtDeliveryEndDate.getMonth() + 1) + "/" + dtDeliveryEndDate.getFullYear();
            //alert("in inner else: " + dtDeliveryEndDate + strDeliveryEndDate);
          }

        }
      }

      function initTime() { 
        var pickupDateVal = document.getElementById("pickupDate").value;
        var deliveryDateVal = document.getElementById("deliveryDate").value; 
        //alert(pickupDateVal + "delDate = " + deliveryDateVal);
        if (pickupDateVal != "") { //init delivery date/time
          pickupDateParts = pickupDateVal.split("/");
          pickupDateDay = pickupDateParts[0];
          pickupDateMth = +pickupDateParts[1] - 1;
          pickupDateYear = pickupDateParts[2];
          //alert(pickupDateDay); 
          dtPickupDate = new Date(pickupDateYear, pickupDateMth, pickupDateDay);
          //init time fields
          $("#pickupTime option[value='12:00:00']").prop("disabled", false);
          $("#pickupTime option[value='17:00:00']").prop("disabled", false);
          $("#pickupTime option[value='19:00:00']").prop("disabled", false);
          //ensure that deliveryDate has a value
          if (deliveryDateVal != ""){ 
            deliveryDateParts = deliveryDateVal.split("/");
            deliveryDateDay = deliveryDateParts[0];
            deliveryDateMth = +deliveryDateParts[1] - 1;
            deliveryDateYear = deliveryDateParts[2];
            //init deliveryTime dropdown options
            $("#deliveryTime option[value='12:00:00']").prop("disabled", false);
            $("#deliveryTime option[value='17:00:00']").prop("disabled", false);
            $("#deliveryTime option[value='19:00:00']").prop("disabled", false);
            var pickupTimeVal = document.getElementById("pickupTime").value;
            //alert("pickupTime = " + pickupTimeVal);
            //if pickup date same as delivery date & pickuptime before 12pm
            if (((Date.parse(mth_names[pickupDateMth] + " " + pickupDateDay + " " + pickupDateYear)) == 
            Date.parse(mth_names[deliveryDateMth] + " " + deliveryDateDay + " " + deliveryDateYear)) && (pickupTimeVal == "12:00:00")) {
              //alert("same pickupdate and deliveryDate");
              $('#deliveryTime').val("");
              $("#deliveryTime option[value='12:00:00']").prop("disabled", true); //disable delivery time 12pm
            }

            else if ((Date.parse(mth_names[pickupDateMth] + " " + pickupDateDay + " " + pickupDateYear)) != 
            Date.parse(mth_names[deliveryDateMth] + " " + deliveryDateDay + " " + deliveryDateYear)) {
              $('#deliveryTime').val("");
            }
          }

          //if selected date same as current date
          if ((Date.parse(mth_names[rawDate.getMonth()] + " " + rawDate.getDate() + " " + rawDate.getFullYear()) == 
          Date.parse(mth_names[pickupDateMth] + " " + pickupDateDay + " " + pickupDateYear))) { 

            //alert("inside here");
            if(Date.parse(mth_names[rawDate.getMonth()] + " " + rawDate.getDate() + " " + rawDate.getFullYear() + " " + 
            rawDate.getHours() + ":" + rawDate.getMinutes() + ":" + rawDate.getSeconds() + ":" + rawDate.getMilliseconds()) > 
            Date.parse(mth_names[rawDate.getMonth()] + " " + rawDate.getDate() + " " + rawDate.getFullYear() + " " + "10:00:00:00")) {
              $("#pickupTime option[value='17:00:00']").prop("disabled", false);
              $("#pickupTime option[value='19:00:00']").prop("disabled", false);

              $("#pickupTime option[value='12:00:00']").prop("disabled", true);
            }
            if(Date.parse(mth_names[rawDate.getMonth()] + " " + rawDate.getDate() + " " + rawDate.getFullYear() + " " + 
            rawDate.getHours() + ":" + rawDate.getMinutes() + ":" + rawDate.getSeconds() + ":" + rawDate.getMilliseconds()) > 
            Date.parse(mth_names[rawDate.getMonth()] + " " + rawDate.getDate() + " " + rawDate.getFullYear() + " " + "15:00:00:00")) {
              $("#pickupTime option[value='19:00:00']").prop("disabled", false);

              $("#pickupTime option[value='17:00:00']").prop("disabled", true);
            }
            if(Date.parse(mth_names[rawDate.getMonth()] + " " + rawDate.getDate() + " " + rawDate.getFullYear() + " " + 
            rawDate.getHours() + ":" + rawDate.getMinutes() + ":" + rawDate.getSeconds() + ":" + rawDate.getMilliseconds()) > 
            Date.parse(mth_names[rawDate.getMonth()] + " " + rawDate.getDate() + " " + rawDate.getFullYear() + " " + "17:00:00:00")) {
              $("#pickupTime option[value='19:00:00']").prop("disabled", true);
            }
          }
        }
        else{
          //alert("initTime else")
          //if pickupDate is empty
        }
      }

      //load datepicker and time function
      $(document).ready(function (){
        initDatepicker();
        initTime();
        $('#pickupDate').datepicker({
          format: "dd/mm/yyyy",
          startDate: strPickupStartDate,
          endDate: strPickupEndDate,
          autoclose: true
        });

        //run initTime() & initDatepicker() on change of pickupTime
        $('#pickupTime').change(function() {
          initTime();
          initDatepicker();
          //init deliveryTime
          $("#deliveryTime option[value='12:00:00']").prop("disabled", false);
          $("#deliveryTime option[value='17:00:00']").prop("disabled", false);
          $("#deliveryTime option[value='19:00:00']").prop("disabled", false);
          $('#deliveryTime').val("");
          //set the updated delivery start date and end dates 
          $('#deliveryDate').datepicker('setStartDate', strDeliveryStartDate);
          $('#deliveryDate').datepicker('setEndDate', strDeliveryEndDate);
          $('#deliveryDate').val("");
        });


        //re-validate & update start/end dates on changeDate event
        $('#pickupDate').datepicker()
        .on('changeDate', function(e) {
            //reinit datepicker onchange  
            initDatepicker();
            initTime();
            //set the updated delivery start date and end dates 
            $('#deliveryDate').datepicker('setStartDate', strDeliveryStartDate);
            $('#deliveryDate').datepicker('setEndDate', strDeliveryEndDate);
            $('#deliveryDate').val("");
            // Re-Validate the date when user change it
            $('#pickupForm')
                // Get the bootstrapValidator instance
                .data('bootstrapValidator')
                // Mark the field as not validated, so it'll be re-validated when the user change date
                .updateStatus('pickupDate', 'NOT_VALIDATED', null)
                // Validate the field
                .validateField('pickupDate');
        });

        $('#deliveryDate').datepicker({
          format: "dd/mm/yyyy",
          //startDate: "0d",
          startDate: strDeliveryStartDate,
          endDate: strDeliveryEndDate,
          autoclose: true
        });
        //re-validate on changeDate event
        $('#deliveryDate').datepicker()
        .on('changeDate', function(e) {
            initTime();
            //init deliveryTime
            /*$("#deliveryTime option[value='12:00:00']").prop("disabled", false);
            $("#deliveryTime option[value='17:00:00']").prop("disabled", false);
            $("#deliveryTime option[value='19:00:00']").prop("disabled", false);
            $('#deliveryTime').val("");*/
            // Validate the date when user change it
            $('#pickupForm')
                // Get the bootstrapValidator instance
                .data('bootstrapValidator')
                // Mark the field as not validated, so it'll be re-validated when the user change date
                .updateStatus('deliveryDate', 'NOT_VALIDATED', null)
                // Validate the field
                .validateField('deliveryDate');
            // console.log($('#pickupForm').data('bootstrapValidator').isValid());
        });
    
      });

      /*   validations   */
      //bootstrap validator
      $(document).ready(function() {
        $('#verifyForm').bootstrapValidator({
            message: 'This value is not valid',
            excluded: [':disabled', ':hidden'],
            //submitButtons: $('#btnVerify'),
            // feedbackIcons: {
            //     valid: 'glyphicon glyphicon-ok',
            //     invalid: 'glyphicon glyphicon-remove',
            //     validating: 'glyphicon glyphicon-refresh'
            // },
            fields: {
                verifyKey: {
                    message: 'The name is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Verification key is required and cannot be empty'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9]+$/,
                            message: 'Only letters and numbers are allowed'
                        }
                    }
                }
              }
            });

        $('#pickupForm')
          .bootstrapValidator({
              message: 'This value is not valid',
              feedbackIcons: {
                  valid: 'glyphicon glyphicon-ok',
                  invalid: 'glyphicon glyphicon-remove',
                  validating: 'glyphicon glyphicon-refresh'
              },
            fields: {
                firstName: {
                    message: 'The name is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Your first name is required and cannot be empty'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z]+$/,
                            message: 'Only letters are allowed'
                        }
                    }
                },
                lastName: {
                    message: 'The name is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Your last name is required and cannot be empty'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z]+$/,
                            message: 'Only letters are allowed'
                        }
                    }
                },
                telNo: {
                    message: 'The mobile number is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Your mobile number is required and cannot be empty'
                        },
                        numeric: {
                            message: 'Only numbers are allowed'
                        },
                        regexp: {
                            regexp: /^[89]\d{7}$/,
                            message: 'The input is not a valid Singapore-registered mobile number'
                        }
                    }
                },
                postalCode: {
                    message: 'The postal code is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Your postal code is required and cannot be empty'
                        },
                        regexp: {
                            regexp: /^([0][1-9]|[1-6][0-9]|[7]([0-3]|[5-9])|[8][0-2])(\d{4})$/,
                            message: 'The input is not a valid Singapore postal code'
                        },
                        numeric: {
                          message: 'Only numbers are allowed'
                        }
                    }
                },
                floorNo: {
                    validators: {
                        numeric: {
                            message: 'Only numbers are allowed'
                        }
                    }
                },
                unitNo: {
                    validators: {
                        numeric: {
                            message: 'Only numbers are allowed'
                        }
                    }
                },
                emailAdd: {
                    validators: {
                        notEmpty: {
                            message: 'The email is required and cannot be empty'
                        },
                        emailAddress: {
                            message: 'The input is not a valid email address'
                        }
                    }
                },
                pickupContactName: {
                    message: 'The contact name is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The contact name is required and cannot be empty'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z ]+$/,
                            message: 'The contact name can only contain letters'
                        }
                    }
                },
                pickupContactNo: {
                    message: 'The pickup contact number is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The pickup contact number is required and cannot be empty'
                        },
                        regexp: {
                            regexp: /^[689]\d{7}$/,
                            message: 'The pickup contact number is not a valid Singapore-registered mobile number'
                        }
                    }
                },
                pickupDate: {
                    validators: {
                        notEmpty: {
                            message: 'The pickup date is required and cannot be empty'
                        },
                        date: {
                          format: 'DD/MM/YYYY',
                          message: 'This is not a valid date'
                        }
                    }
                },
                pickupTime: {
                    validators: {
                        notEmpty: {
                            message: 'The pickup time is required and cannot be empty'
                        }
                    }
                },
                deliveryDate: {
                    validators: {
                        notEmpty: {
                            message: 'The delivery date is required and cannot be empty'
                        },
                        date: {
                          format: 'DD/MM/YYYY',
                          message: 'This is not a valid date'
                        }
                    }
                },
                deliveryTime: {
                    validators: {
                        notEmpty: {
                            message: 'The delivery time is required and cannot be empty'
                        }
                    }
                },

            } //end of fields
        })
        .on('error.field.bv', function(e, data) {
            if (data.field == 'telNo') {
                // The tel no is not valid
                $('#verifyMobile').prop('disabled', true).removeClass('btn-success btn-warning').addClass('btn-warning');
            }
            // if (data.field == 'postalCode') {
            //     // The postal code is not valid
            //     $('#lookupBtn').prop('disabled', true).removeClass('btn-success btn-warning').addClass('btn-warning');
            // }
        })
        .on('success.field.bv', function(e, data) {
            if (data.field == 'telNo') {
                // The tel no is valid
                $('#verifyMobile').prop('disabled', false).removeClass('btn-success btn-warning').addClass('btn-info');
            }
            // if (data.field == 'postalCode') {
            //     // The postal code is valid
            //     $('#lookupBtn').prop('disabled', false).removeClass('btn-success btn-warning').addClass('btn-info');
            // }
        });
        $('#verifyMobile').prop('disabled', true);//disable onload
        // $('#lookupBtn').prop('disabled', true)//disable onload
        if ($('#lookupBtn').text() == "Lookup Address"){
          //alert($('#lookupBtn').text());
        }
        else{
          alert("Lookup button If fails");
        }
        
    });
    </script>
  </body>
</html>