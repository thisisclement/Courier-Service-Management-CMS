function getCurrentDate()
    {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yy = today.getFullYear();

        if(dd<10) {
            dd='0'+dd
        }

        if(mm<10) {
            mm='0'+mm
        }

        current = dd+'/'+mm+'/'+yy;
        return current;

    }



    function splitDates(date)
    {

        datesplit = date.split('/');

        dateformat = new Date(datesplit[2],datesplit[1]-1,datesplit[0]);

        return dateformat;

    }



    function getDays(firstDate,secondDate)
    {
        var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds

        var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)));

        return diffDays + 1;//+1 because the day itself is considered 1
    }






    jQuery(document).ready(function ($) {

        //$("#datedisplay").hide();

        $("#datepicker").datepicker({
            dateFormat: "dd/mm/yy",
            minDate: 0,
            changeMonth: true,
            changeYear: true,
            showOn: "button",
            buttonImage: sickapp.calendarurl,
            buttonImageOnly: true
        });



        $.getJSON(sickapp.couriersupdatejsurl, function(obj)
        {

            $.each(obj.couriers, function(key, value)
            {

                var option = $('<option />').val(value.cid).text(value.cid + " " + value.name);
                $("#ddList").append(option);

                //console.log(value.cid);


            });


            //$("#ddList")[0].selectedIndex = 0;



            $("#datepicker").on("change", function(){


                $("#confirmdisplay").show();


                $.each(obj.couriers, function(key, value)
                {

                    if(value.cid == $("#ddList").val())
                    {

                        //http://stackoverflow.com/questions/2627473/how-to-calculate-the-number-of-days-between-two-dates-using-javascript
                        //http://stackoverflow.com/questions/1531093/how-to-get-current-date-in-javascript
                        var todaydate = splitDates(getCurrentDate());
                        var getdatepickerdate = splitDates($("#datepicker").val());
                        var getnumdays = getDays(todaydate,getdatepickerdate);
                        //alert(getnumdays);


                        $("#confirmdisplay").html(

                            "<b>Confirm Information?</b><br/>ID: " + value.cid + "<br/>Name: " + value.name + "<br/>Sick Leaves: " + getnumdays + " Days<br/>Expiry Until: " + $("#datepicker").val()

                        );

                        $("#showbuttons").show();


                        //store to file then redirect somewhere
                        $("#confirmbtn").on("click", function(){




                            //store to file

                            value["sick status"] = "1";
                            value["sick leaves"] = getnumdays.toString();
                            value["sick expiry date"] = $("#datepicker").val();


                            $("#ddList").prop("disabled",true);

                            $("#showbuttons").hide();


                            $.ajax({
                                url: sickapp.couriersupdatephpurl,
                                data: {request: obj},
                                type: 'post',
                                dataType: 'json',
                                success: function(data) {

                                    //console.log(data);

                                    //if use redirect don't need this
                                    //$("#confirmdisplay").html("");
                                    //$("#showbuttons").hide();


                                    //redirect immediately
                                    //window.location.replace("http://localhost/fyp/");

                                    $("#confirmdisplay").html(data["response"]);


                                    //redirect after 2s
                                    setTimeout(function() {
                                        window.location.replace(sickapp.topermalink);
                                        //window.location.replace("http://localhost/fyp/");
                                        //window.location.href = "http://localhost/fyp/";
                                    }, 2000);




                                },
                                error: function(err)
                                {
                                    console.log(err.responseText);
                                }
                            });








                        });




                    }


                });

            });










        });




        /*$('#ddList').on('change', function(){

            alert($(this).val());

        });*/



        $("#ddList").on("change", function(){


            $("#confirmdisplay").hide();
            $("#datepicker").val("");
            $("#datedisplay").show();
            $("#showbuttons").hide();


        });




        $("#nobtn").on("click", function(){


            $("#showbuttons").hide();
            $("#confirmdisplay").hide();
            $("#datepicker").val("");


        });















    });