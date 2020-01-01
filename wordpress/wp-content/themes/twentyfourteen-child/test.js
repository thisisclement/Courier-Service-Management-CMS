jQuery(document).ready(function($){
        initialize();
        $('#lookupBtn').click(function() {
          codeAddress();
          // $('#myVerifyModal').modal('show');
        });
    /* ---------------------------------------------------- START OF GOOGLEMAPS JS ------------------------------------------------------------- */
      //geocode
      var geocoder;
      var map;
      function initialize() {
        geocoder = new google.maps.Geocoder();
        //var latlng = new google.maps.LatLng(-34.397, 150.644);
      }
      function codeAddress() {
        var address = "singapore " + $('#postalCode').val();
        geocoder.geocode( { 'address': address}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
          $('#latlng').val(results[0].geometry.location);
          codeLatLng();
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
      }
      function codeLatLng() {
        var input = $('#latlng').val();
        var str3 = input.match(/\((.*?)\)/)[1];
        var latlngStr = str3.split(',', 2);
        var lat = parseFloat(latlngStr[0]);
        var lng = parseFloat(latlngStr[1]);
        
        var latlng = new google.maps.LatLng(lat, lng);
        geocoder.geocode({'latLng': latlng}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
            //$('#addressResult').val() = results[0].formatted_address; //full address
            //alert(results[0].address_components[4].long_name);
              if (results[0].address_components[4].long_name == "Singapore"){
                //just street name
                $('#postalAdd').val((results[0].address_components[0].long_name).concat(" ".concat(results[0].address_components[1].long_name)));
                $('#pickupForm')
                  // Get the bootstrapValidator instance
                  .data('bootstrapValidator')
                  // Mark the field as not validated, so it'll be re-validated when the user clicks on Lookup address btn
                  .updateStatus('postalAdd', 'NOT_VALIDATED', null)
                  // Validate the field
                  .validateField('postalAdd');
              }

              else {
                alert("This is not a valid Singapore postal code.");
                $('#postalCode').val("");
                $('#pickupForm')
                  // Get the bootstrapValidator instance
                  .data('bootstrapValidator')
                  // Mark the field as not validated, so it'll be re-validated after user keys in invalid postal code
                  .updateStatus('postalCode', 'NOT_VALIDATED', null)
                  // Validate the field
                  .validateField('postalCode');
              }
                
            }
            else {
              alert('No results found');
            }
          } else {
            alert('Geocoder failed due to: ' + status);
          }
        });
      }
/* ---------------------------------------------------- END OF GOOGLEMAPS JS ------------------------------------------------------------- */
/* ----------------------------------------------------------- START OF JQUERY --------------------------------------------------------- */
      function numericField(field){

        $(field).keydown(function(event) { //only allow numeric entry
          // Allow only backspace, delete, arrow keys left and right, tab
          if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 9) {
            // let it happen, don't do anything
          }
          else {
            // Ensure that it is a number and stop the keypress
            if (event.keyCode < 48 || event.keyCode > 57 ) {
              event.preventDefault(); 
            } 
          }
        });
      }

      function textonlyField(field){

        $(field).keydown(function(event) { //only allow text entry
          // Allow only backspace, delete, arrow keys left and right tab
          if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 9) {
            // let it happen, don't do anything
          }
          else {
            // Ensure that it is a letter and stop the keypress
            if (event.keyCode < 65 || event.keyCode > 90 ) {
              event.preventDefault(); 
            } 
          }
        });
      }

      function keyGen(len){

          var number = "";
          for(var i=0;i<len;i++)
          {
              number += Math.floor(Math.random() * (9)); //generates no 1-9 each time
          }
          return number;
      }

      //readonly fields
      $('#postalAdd').attr('readonly', true);
      
      /* init text-only fields */
      textonlyField('input[name="firstName"]');
      textonlyField('input[name="lastName"]');
      textonlyField('input[name="pickupContactName"]');

      /* init numeric fields */
      numericField('#postalCode');
      numericField('#telNo');
      numericField('input[name="floorNo"]');
      numericField('input[name="unitNo"]');

      var key = keyGen(6);
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
          key = keyGen(6);
        }
        
        //AJAX post - send SMS
        $.ajax({
          type: 'POST',
          url: '/courier/clem/wordpress/wp-content/themes/twentyfourteen-child/sendSMS.php',
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

          $('#btnVerify').prop('disabled', false);
          
          if (response != ""){
            if (sendSMSStatus < 1){
              response = "Message Resent!";
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

      // $('#lookupBtn').click(function) {
      //   $('#pickupForm').data('bootstrapValidator').validateField('postalAdd');
      // }
      //submit verify form
      $('#btnVerify').click(function() {
        // $('#verifyForm').data('bootstrapValidator').validateField('verifyKey');
        // $('#verifyForm').bootstrapValidator('updateStatus', 'verifyKey', 'NOT_VALIDATED')
        $('#verifyForm').data('bootstrapValidator').validate();
        // if ($('#verifyForm').data('bootstrapValidator').isValid()) { 
          $('#verifyForm').submit();
          var submitNo = 0;
          submitNo++;
          console.log('submit ' + submitNo);

        // }
      });

      $('#btnResendSMS').click(function(){ 
        //alert('clicked resendSMS');
        resendSMSStatus = true;
        sendSMS();
        var btn = $(this);
        btn.button('loading');
      });      

      $('#verifyForm').submit(function(e) {
        // Stop the browser from submitting the form.
        e.preventDefault();
        // alert("verifyForm submit");

        // Serialize the form data.
        var verifyFormData = $('#verifyForm').serialize();

        var formMessageVerify = $('#alertRes');

        //AJAX post - verify with the verification key
        $.ajax({
          type: 'POST',
          url: '/courier/clem/wordpress/wp-content/themes/twentyfourteen-child/verifyKey.php',
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
          //$(button).insertAfter($('#btnVerify'));
          setTimeout(function() {$('#myVerifyModal').modal('hide');}, 2000);
          var addAlertOnTop = ['<div class="alert alert-success alert-dismissable" id="alertTop"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', response, '</div>'].join('');
          $('.jumbotron.text-center').prepend(addAlertOnTop);
          if (sendSMSStatus < 1){
            $('#btnResendSMS').prop('disabled', true);
          }
          $('#mobileVerified').text("Verified!");
          if ($('#mobileVerified').text() == "Verified!"){
            $('#pickupForm').submit();
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
          //alert('fail sendSMS' + sendSMSStatus);
          $('#btnVerify').prop('disabled', true);
                    
        });
        return false;
      }); //end verifyForm submit

      /* FORM SUBMIT */
      // Get the form.
      var form = $('#pickupForm');

      $('#btnSubmit').click(function(){
        $(form).data('bootstrapValidator').validate();

        if ($(form).data('bootstrapValidator').isValid()){
          //create a non-closable modal
          $('#myVerifyModal').modal({backdrop: 'static'});
          //show modal (create new modal for submitting password for verification)
          $('#myVerifyModal').modal('show');
          sendSMS();
          
        }
        
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
          url: '/courier/clem/wordpress/wp-content/themes/twentyfourteen-child/formProcess.php',
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
          $(formMessages).html(response);

          //show modal
          $('#myModal').modal('show');
          //setTimeout(function() {$('#myModal').modal('hide');}, 4000);
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
            // setTimeout(function() {$('#myModal').modal('hide');}, 4000);
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
        var pickupDateVal = $("#pickupDate").val(); 
        var dtCurrentDate = new Date(rawDate.getFullYear(), rawDate.getMonth(), rawDate.getDate()); 
        pickupDateParts = pickupDateVal.split("/");

        if  (pickupDateVal != "") { //init delivery date/time
          pickupDateDay = pickupDateParts[0];
          pickupDateMth = +pickupDateParts[1] - 1;
          pickupDateYear = pickupDateParts[2]; 

          var pickupTimeVal = $("#pickupTime").val();
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
        var pickupDateVal = $("#pickupDate").val();
        var deliveryDateVal = $("#deliveryDate").val(); 
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
            var pickupTimeVal = $("#pickupTime").val();
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
      jQuery(document).ready(function ($){
        initDatepicker();
        initTime();
        $('#pickupDate').datepicker({
          format: "dd/mm/yyyy",
          startDate: strPickupStartDate,
          endDate: strPickupEndDate,
          autoclose: true
        });

        $( "#pickupDate" ).keydown(function(e) {
          e.preventDefault();
        });

        $( "#deliveryDate" ).keydown(function(e) {
          e.preventDefault();
        });

        $("#pickupDate").focus(function(){ //de-validate floor & unit no fields to eliminate datepicker bug
          $('#pickupForm')
              // Get the bootstrapValidator instance
              .data('bootstrapValidator')
              // Mark the field as not validated
              .updateStatus('floorNo', 'NOT_VALIDATED', null);
          $('#pickupForm')
              // Get the bootstrapValidator instance
              .data('bootstrapValidator')
              // Mark the field as not validated
              .updateStatus('unitNo', 'NOT_VALIDATED', null);
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
      jQuery(document).ready(function($) {
        $('#verifyForm').bootstrapValidator({
            message: 'This value is not valid',
            excluded: [':disabled', ':hidden', ':not(:visible)'],
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
                  valid: 'glyphicon glyphicon-ok1',
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
                postalAdd: {
                    message: 'The postal code is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Please click on the "Lookup Address" button to verify your postal code.'
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
            if (data.field == 'postalCode') {
                // The postal code is not valid
                $('#lookupBtn').prop('disabled', true).removeClass('btn-success btn-warning').addClass('btn-warning');
            }
        })
        .on('success.field.bv', function(e, data) {
            if (data.field == 'postalCode') {
                // The postal code is valid
                $('#lookupBtn').prop('disabled', false).removeClass('btn-success btn-warning').addClass('btn-info');
            }
        });
        $('#lookupBtn').prop('disabled', true);//disable onload
    });
});