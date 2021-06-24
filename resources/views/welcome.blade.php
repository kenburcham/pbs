<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

        <title>PBS Exercise</title>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Arial', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: top;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
        <script>
            $(document).ready(function(){
                getValues()
                $("#calculate").click(function(event){
                    event.preventDefault();
                    const currency_date = $("input[id=currency_date]").val();
                    const currency_code = $("input[id=currency_code]").val();
                    const amount = $("input[id=amount]").val();
                    $.ajax({
                        url: "/api/v1/calculate",
                        type:"POST",
                        data:{
                            currency_date:currency_date,
                            currency_code:currency_code,
                            amount:amount
                        },
                        success: handleCalcSuccess,
                        error: handleCalcError
                    });
                });
                function handleCalcSuccess(data) {
                    $('#success_model').modal();
                }
                function handleCalcError(data) {
                    $('.error_msg').append('An error occured. ' + data.responseText);
                    $('#error_model').modal();
                }
                function getValues() {
                   $.ajax({
                        url: "/api/v1/currency-history",
                        type:"GET",
                        success: handleHistorySuccess,
                        error: handleHistoryError
                    });
                }
                function handleHistorySuccess(data) {
                    for(let i = 0; i < data.length; i++) {
                        let tempDate = data[i]['currency_date'].toString();
                        let formatDate = tempDate.substr(5,2) + '/' + tempDate.substr(8,2) + '/' + tempDate.substr(0,4)
                        if (data[i]['success']) {
                            $('#history_values').append('<tr><td>' + 'Yes' +'</td><td>' + formatDate +'</td><td>' + data[i]['currency_code'] +'</td><td>' + data[i]['currency_name'] +'</td><td>' + data[i]['amount'] +'</td></tr>' );
                        } else {
                            $('#history_values').append('<tr><td>' + 'No' +'</td><td>' + formatDate +'</td><td colspan="3">' + data[i]['message'] );
                        }
                        for(let j = 0; j < data[i]['values'].length; j++) {
                            let calcCurrency = data[i];
                           $('#history_values').append('<tr><td></td><td></td><td>' + data[i]['values'][j]['currency_code'] +'</td><td>' + data[i]['values'][j]['currency_name'] +'</td><td>' + data[i]['values'][j]['amount'] +'</td></tr>' );

                        }
                    }
                }
                function handleHistoryError(data) {
                    $('#history_values').append('<tr><td>' + 'An error occured.' +'</td><td></td><td></td><td></td><td></td></tr>' );
                }
            });
        </script>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    PBS Exercise
                </div>
                <div>
                    <h1>Calculate Curreny Exchanges</h1>
                </div>
                <span class="success" style="color:green; margin-top:10px; margin-bottom: 10px;"></span>
               <div>
                    <form id="currency_caludation_form">
                        <div class="form-group row">
                            <label for="Amount" class="col-sm-2 col-form-label">Date:</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" id="currency_date">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="currency_code"  class="col-sm-2 col-form-label">Currency:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="currency_code" placeholder="USD">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="amount"  class="col-sm-2 col-form-label">Amount:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="amount" placeholder="1.0">
                            </div>
                        </div>
                        <button id="calculate" type="submit" class="btn btn-default">Calculate</button>
                        <button id="reset_form" type="reset" class="btn btn-default">Reset</button>
                    </form>
                </div>
                <div style="margin-top: 100px;">
                    <hr>
                    <h1>Results</h1>
                </div>
                <div>
                    <table border="1">
                        <tr>
                            <th>Success</th>
                            <th>Date</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Amount</th>
                        </tr>
                        <tbody id="history_values"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal fade" id="success_model" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Success</h4>
                        </div>
                        <div class="modal-body">
                          <p>Currency was saved!</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="error_model" role="dialog">
                <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Error</h4>
                        </div>
                        <div class="modal-body">
                            <p class="error_msg"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
