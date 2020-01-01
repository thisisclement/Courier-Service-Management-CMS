jQuery(document).ready(function($){
        initialize();
        $('#lookupBtn').click(function() {
          codeAddress('#postalCode', '#latlng', '#postalAdd', 'postalCode');
          // $('#myVerifyModal').modal('show');
        });
        $('#delLookupBtn').click(function() {
          codeAddress('#delPostalCode', '#delLatlng', '#delPostalAdd', 'delPostalCode');
        });
        var slotLimit = 100;
        var jobsArray = {};
        var districtArray = {};
        var jobErr = [];
        $.getJSON( pickupForm.jobprocessurl, function( data ) { //get job timeslot json file           
            
            $.each( data, function( key, val ) {
              var items = {};
              var slot1 = val["12:00:00"];
              var slot2 = val["17:00:00"];
              var slot3 = val["19:00:00"];
              console.log(key);
              console.log(slot1);
              console.log(slot2);
              console.log(slot3);
              console.log("End of Data..\n\n");
              if (slot1 >= slotLimit){                
                items['12:00:00'] = slot1;
                jobsArray[key] = items;
              }

              if (slot2 >= slotLimit){
                items['17:00:00'] = slot2;
                jobsArray[key] = items;
              }

              if (slot3 >= slotLimit){
                items['19:00:00'] = slot3;
                jobsArray[key] = items;
              }
              console.log(jobsArray);
            });
          });
        $.getJSON( pickupForm.districturl, function( data ) { //get job timeslot json file           
            
            $.each( data, function( key, val ) {
              districtArray[key] = val; 
            });
            // console.log(districtArray["data"]);
          });
    /* ---------------------------------------------------- START OF GOOGLEMAPS JS ------------------------------------------------------------- */
      //geocode
      var geocoder;
      var map;
      function initialize() {
        geocoder = new google.maps.Geocoder();
        //var latlng = new google.maps.LatLng(-34.397, 150.644);
      }
      function codeAddress(postalid, latlngid, addressid, codename) {
        var latlng;
        var twodigits = Math.floor($(postalid).val()/10000); //first two digits of 6 digits
        function pad (str, max) {
          str = str.toString();
          return str.length < max ? pad("0" + str, max) : str;
        }
        if(twodigits < 10){ 
            twodigits = pad(twodigits, 2);//for single digits, add a zero in front
        }
        console.log(districtArray["data"].length);
        console.log(twodigits);

        for (var i = 0; i < districtArray["data"].length; i++){
          if (districtArray["data"][i]["postalcode"] == twodigits){
            console.log(districtArray["data"][i]["available"].length);
            if (districtArray["data"][i]["available"].length != 0){
              for (var x = 0; x < districtArray["data"][i]["available"].length; x++){
                console.log(districtArray["data"][i]["available"][x]["postalcode"] + "=>" + $(postalid).val());
                if (districtArray["data"][i]["available"][x]["postalcode"] == $(postalid).val()){
                  latlng = districtArray["data"][i]["available"][x]["lat"].toString() + ", " + districtArray["data"][i]["available"][x]["lng"]; 
                  $(addressid).val(districtArray["data"][i]["available"][x]["address"]);
                  $(latlngid).val(latlng);
                  console.log(districtArray["data"][i]["available"][x]["address"]);
                  return true;
                }
              }

              
            }
          }
        }//for loop
        console.log("geocoding..");
        var address = "singapore " + $(postalid).val();
        geocoder.geocode( { 'address': address, 'country': 'SG'}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {          
          console.log("lat lng");
          console.log(results[0].geometry.location);
          $(latlngid).val(results[0].geometry.location);
          var input = $(latlngid).val();
          console.log(input);
          var str3 = input.match(/\((.*?)\)/)[1];
          var latlngStr = str3.split(',', 2);
          $(latlngid).val(latlngStr);
        //   $('#lat').val(lat);
        // // $('#lng').val(lng);

          codeLatLng(latlngStr, addressid, codename);
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
      }
      function codeLatLng(latlngStr, addressid, codename) {
        // var input = $('#latlng').val();
        // var str3 = input.match(/\((.*?)\)/)[1];
        // var latlngStr = str3.split(',', 2);
        console.log(addressid);
        console.log(codename);
        console.log(addressid.split('#')[1]);
        var lat = parseFloat(latlngStr[0]);
        var lng = parseFloat(latlngStr[1]);

        // $('#lat').val(lat);
        // $('#lng').val(lng);
        
        var latlng = new google.maps.LatLng(lat, lng);
        console.log("in codeLatLng: "+latlng);
        geocoder.geocode({'latLng': latlng}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
            //$('#addressResult').val() = results[0].formatted_address; //full address
            //alert(results[0].address_components[4].long_name);
            // console.log(results[0].address_components[4].long_name);
            // console.log((results[0].formatted_address).split(","));
            // console.log(((results[0].formatted_address).split(",")[1]).indexOf("Singapore"));
              if ( ((results[0].formatted_address).split(",")[1]).indexOf("Singapore") >= 0) {
                //just street name
                //$('#postalAdd').val((results[0].address_components[0].long_name).concat(" ".concat(results[0].address_components[1].long_name)));
                $(addressid).val((results[0].formatted_address).split(",")[0]);
                $('#pickupForm')
                  // Get the bootstrapValidator instance
                  .data('bootstrapValidator')
                  // Mark the field as not validated, so it'll be re-validated when the user clicks on Lookup address btn
                  .updateStatus(addressid.split('#')[1], 'NOT_VALIDATED', null)
                  // Validate the field
                  .validateField(addressid.split('#')[1]);
                  $('#pickupForm')
                  // Get the bootstrapValidator instance
                  .data('bootstrapValidator')
                  // Mark the field as not validated, so it'll be re-validated when the user clicks on Lookup address btn
                  .updateStatus(addressid.split('#')[1], 'NOT_VALIDATED', null)
              }


              else {
                alert("This is not a valid Singapore postal code.");
                $(addressid).val("");
                $('#pickupForm')
                  // Get the bootstrapValidator instance
                  .data('bootstrapValidator')
                  // Mark the field as not validated, so it'll be re-validated after user keys in invalid postal code
                  .updateStatus(codename, 'NOT_VALIDATED', null)
                  // Validate the field
                  .validateField(codename);
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
      var ctrlKey = 17, cmdKey = 91, vKey = 86, cKey = 67, aKey = 65, spaceKey = 32;

      function numericField(field){        
        $(field).keydown(function(event) { //only allow numeric entry
          var ctrlDown = event.ctrlKey||event.metaKey; 
          // Allow only backspace, delete, arrow keys left and right, tab
          if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 9
            || (ctrlDown && event.keyCode==vKey) || (ctrlDown && event.keyCode==cKey) || (ctrlDown && event.keyCode==aKey)) {
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

       function emailField(field){        
        $(field).keydown(function(event) { //only allow numeric entry
          // Ensure that no spaces and stop the keypress
          if (event.keyCode == 32) {
            event.preventDefault(); 
          } 
        });
      }

      function textonlyField(field, spaceKey){
        if(typeof(spaceKey)==='undefined') spaceKey=16;
        $(field).keydown(function(event) { //only allow text entry
          var ctrlDown = event.ctrlKey||event.metaKey;
          // Allow only backspace, delete, arrow keys left and right tab
          if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 9
            || (ctrlDown && event.keyCode==vKey) || (ctrlDown && event.keyCode==cKey) || (ctrlDown && event.keyCode==aKey
              || event.keyCode == spaceKey)) {
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
      $('#delPostalAdd').attr('readonly', true);
      
      /* init text-only fields */
      textonlyField('input[name="firstName"]');
      textonlyField('input[name="lastName"]');
      textonlyField('input[name="pickupContactName"]', spaceKey);
      textonlyField('input[name="delContactName"]', spaceKey);

      emailField('input[name="emailAdd"]');

      /* init numeric fields */
      numericField('#postalCode');
      numericField('#delPostalCode');
      numericField('#telNo');
      numericField('input[name="floorNo"]');
      numericField('input[name="unitNo"]');
      numericField('input[name="delFloorNo"]');
      numericField('input[name="delUnitNo"]');
      numericField('input[name="pickupContactNo"]');
      numericField('input[name="delContactNo"]');

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
          //url: '/courier/clem/wordpress/wp-content/themes/spacious-child/misc/sendSMS.php',
          url: pickupForm.sendsmsurl,
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
        if ($('#verifyForm').data('bootstrapValidator').isValid()) { 
          $('#verifyForm').submit();
          var submitNo = 0;
          submitNo++;
          console.log('submit ' + submitNo);

        }
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
          //url: '/courier/clem/wordpress/wp-content/themes/spacious-child/misc/verifyKey.php',
          url: pickupForm.verifykeyurl,
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
          var pickupDate = $('#pickupDate').val();
          var deliveryDate = $('#deliveryDate').val();
          var pickupTime = $('#pickupTime').val();
          var deliveryTime = $('#deliveryTime').val();

          $.each(jobsArray, function(key, value){

            if(key == pickupDate && (pickupTime in value)){
              console.log((pickupTime in value));
              console.log(value[pickupTime]);
              alert(key + "=>"+ value[pickupTime]);
              jobErr[0] = true;
            }
            if(key == deliveryDate && (deliveryTime in value)){
              console.log((deliveryTime in value));
              console.log(value[deliveryTime]);
              alert(key + "=>"+ value[deliveryTime]);
              jobErr[1] = true;
            }
          });

          if(jobErr[0] == true && jobErr[1] == true){
            $('#pickupDate').val("");
            $(form).data('bootstrapValidator')
            .updateStatus('pickupDate', 'NOT_VALIDATED', null);
            // $(form).data('bootstrapValidator').updateMessage('pickupDate', 'bootstrapValidator', 'Timeslot full!');
            $(form).data('bootstrapValidator').validateField('pickupDate');
            
            $('#pickupTime').val("");
            $(form).data('bootstrapValidator')
            .updateStatus('pickupTime', 'NOT_VALIDATED', null);
            $('#deliveryDate').val("");
            $(form).data('bootstrapValidator')
            .updateStatus('deliveryDate', 'NOT_VALIDATED', null);
            $('#deliveryTime').val("");
            $(form).data('bootstrapValidator')
            .updateStatus('deliveryTime', 'NOT_VALIDATED', null);
            $('#pickupDate').focus();
            alert("Pickup & Delivery timeslot full, please choose another");
          }

          else if (jobErr[0] == true){
            $('#pickupDate').val("");
            $(form).data('bootstrapValidator')
            .updateStatus('pickupDate', 'NOT_VALIDATED', null);
            $(form).data('bootstrapValidator').validateField('pickupDate');
            $('#pickupTime').val("");
            $(form).data('bootstrapValidator')
            .updateStatus('pickupTime', 'NOT_VALIDATED', null);
            alert("Pickup timeslot full, please choose another");
          }

          else if (jobErr[1] == true){
            $('#deliveryDate').val("");
            $(form).data('bootstrapValidator')
            .updateStatus('deliveryDate', 'NOT_VALIDATED', null);
            $('#deliveryTime').val("");
            $(form).data('bootstrapValidator')
            .updateStatus('deliveryTime', 'NOT_VALIDATED', null);
            alert("Delivery timeslot full, please choose another");
          }

          if (jobErr[0] != true && jobErr[1] != true){
            // alert('sendSMS');
            //create a non-closable modal
            $('#myVerifyModal').modal({backdrop: 'static'});
            //show modal (create new modal for submitting password for verification)
            $('#myVerifyModal').modal('show');
            sendSMS(); 
          } 
          //reset                 
          jobErr[0] = false;
          jobErr[1] = false;
        }
        
      }); //btnSubmit.click()

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
          //url: '/courier/clem/wordpress/wp-content/themes/spacious-child/misc/formProcess.php',
          url: pickupForm.formprocessurl,
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
          //if current time > 1500 then set start date +1
          if(Date.parse(mth_names[rawDate.getMonth()] + " " + rawDate.getDate() + " " + rawDate.getFullYear() + " " + 
            rawDate.getHours() + ":" + rawDate.getMinutes() + ":" + rawDate.getSeconds() + ":" + rawDate.getMilliseconds()) > 
            Date.parse(mth_names[rawDate.getMonth()] + " " + rawDate.getDate() + " " + rawDate.getFullYear() + " " + "15:00:00:00"))
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
          orientation: "bottom auto",
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
          $('#pickupForm')
              // Get the bootstrapValidator instance
              .data('bootstrapValidator')
              // Mark the field as not validated
              .updateStatus('deliveryTime', 'NOT_VALIDATED', null);
          //set the updated delivery start date and end dates 
          $('#deliveryDate').datepicker('setStartDate', strDeliveryStartDate);
          $('#deliveryDate').datepicker('setEndDate', strDeliveryEndDate);
          $('#deliveryDate').val("");
          $('#pickupForm')
              // Get the bootstrapValidator instance
              .data('bootstrapValidator')
              // Mark the field as not validated
              .updateStatus('deliveryDate', 'NOT_VALIDATED', null);
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
            $('#pickupForm')
              // Get the bootstrapValidator instance
              .data('bootstrapValidator')
              // Mark the field as not validated
              .updateStatus('deliveryTime', 'NOT_VALIDATED', null);
            $('#pickupForm')
              // Get the bootstrapValidator instance
              .data('bootstrapValidator')
              // Mark the field as not validated
              .updateStatus('deliveryDate', 'NOT_VALIDATED', null);
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
                            message: 'The mobile number is not a valid Singapore-registered mobile number'
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
                pickupContactNo: {
                    message: 'The contact number is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The contact number is required and cannot be empty'
                        },
                        regexp: {
                            regexp: /^[689]\d{7}$/,
                            message: 'The contact number is not a valid Singapore-registered number'
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
                delContactName: {
                    message: 'The name is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Delivery contact name is required and cannot be empty'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z ]+$/,
                            message: 'Only letters are allowed'
                        }
                    }
                },
                delContactNo: {
                    message: 'The contact number is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The contact number is required and cannot be empty'
                        },
                        regexp: {
                            regexp: /^[689]\d{7}$/,
                            message: 'The contact number is not a valid Singapore-registered number'
                        }
                    }
                },
                delPostalCode: {
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
                delPostalAdd: {
                    message: 'The postal code is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Please click on the "Lookup Address" button to verify your postal code.'
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
            if (data.field == 'delPostalCode') {
                // The postal code is not valid
                $('#delLookupBtn').prop('disabled', true).removeClass('btn-success btn-warning').addClass('btn-warning');
            }
        })
        .on('error.validator.bv', function(e, data) {  
          if (jobErr[0] == true){     
            data.bv.updateMessage('pickupDate', data.validator, 'Pickup slot full!');
          }
          if (jobErr[1] == true){     
            data.bv.updateMessage('deliveryDate', data.validator, 'Delivery slot full!');
          }
        })
        .on('success.field.bv', function(e, data) {
            if (data.field == 'postalCode') {
                // The postal code is valid
                $('#lookupBtn').prop('disabled', false).removeClass('btn-success btn-warning').addClass('btn-info');
            }
            if (data.field == 'delPostalCode') {
                // The postal code is valid
                $('#delLookupBtn').prop('disabled', false).removeClass('btn-success btn-warning').addClass('btn-info');
            }
            if (data.field == 'pickupDate') {
              var timeslot1 = false;
              var timeslot2 = false;
              var timeslot3 = false;
              //pickupdate is valid, disable full timeslots
              if ($('#pickupDate').val() in jobsArray){
                if ('12:00:00' in jobsArray[$('#pickupDate').val()]){
                  $("#pickupTime option[value='12:00:00']").prop("disabled", true);
                  timeslot1 = true;
                }
                if ('17:00:00' in jobsArray[$('#pickupDate').val()]){
                  $("#pickupTime option[value='17:00:00']").prop("disabled", true);
                  timeslot2 = true;
                }
                if ('19:00:00' in jobsArray[$('#pickupDate').val()]){
                  $("#pickupTime option[value='19:00:00']").prop("disabled", true);                
                  timeslot3 = true;
                }

                if (timeslot1 == true && timeslot2 == true && timeslot3 == true){
                  $(form).bootstrapValidator('updateStatus', 'pickupDate', 'INVALID');
                    data.element
                    .data('bv.messages')
                    // Hide existing messages
                    .find('.help-block[data-bv-validator="notEmpty"]').hide()
                  data.bv.updateMessage('pickupDate', data.validator, 'All timeslots in chosen Pickup Date are full! Please choose another date.');
                  $('#pickupTime').val("");
                }
              }
            } 
            if (data.field == 'deliveryDate') {
              //delivery date is valid, disable full timeslots
              var deltimeslot1 = false;
              var deltimeslot2 = false;
              var deltimeslot3 = false;
              if ($('#deliveryDate').val() in jobsArray){
                if ('12:00:00' in jobsArray[$('#deliveryDate').val()]){
                  $("#deliveryTime option[value='12:00:00']").prop("disabled", true);
                  deltimeslot1 = true;
                }
                if ('17:00:00' in jobsArray[$('#deliveryDate').val()]){
                  $("#deliveryTime option[value='17:00:00']").prop("disabled", true);
                  deltimeslot2 = true;
                }
                if ('19:00:00' in jobsArray[$('#deliveryDate').val()]){
                  $("#deliveryTime option[value='19:00:00']").prop("disabled", true);                
                  deltimeslot3 = true;
                }

                if (deltimeslot1 == true && deltimeslot2 == true && deltimeslot3 == true){
                  $(form).bootstrapValidator('updateStatus', 'deliveryDate', 'INVALID');
                    data.element
                    .data('bv.messages')
                    // Hide existing messages
                    .find('.help-block[data-bv-validator="notEmpty"]').hide()
                  data.bv.updateMessage('deliveryDate', data.validator, 'All timeslots in chosen Delivery Date are full! Please choose another date.');
                  $('#deliveryTime').val("");
                }
              }              

            }
        });
        $('#lookupBtn').prop('disabled', true);//disable onload
        $('#delLookupBtn').prop('disabled', true);//disable onload
    });
});