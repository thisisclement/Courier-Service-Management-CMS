<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form</title>

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
  <body>
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
              <div class="col-lg-2">
                <small id="mobileVerified" style="color:green"></small>
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
                <button type="button" class="btn btn-info btn-sm" id="lookupBtn">Lookup Address</button>
              </div>
              <input id="latlng" value="" style="display:none">
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
              <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2>Pickup request confirmed!</h2>
              </div>
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
      $chars = "0123456789";
      $password = substr(str_shuffle( $chars ), 0, $length );
      return $password;
    }
    $key = random_password(6);
    ?>
  </body>
</html>

<script type="text/javascript">
 alert("hey");
 
</script>