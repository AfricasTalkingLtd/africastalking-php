<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Africa's Talking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/paper/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/tomorrow.min.css" />
    <link href="public/css/style.css" rel="stylesheet" type="text/css"/>
</head>
<body>

<div class="container">
    <div class="header clearfix">
        <nav>
            <ul class="nav nav-pills pull-right">
                <li role="presentation" class="active"><a href="#">Home</a></li>
                <li role="presentation"><a href="http://docs.africastalking.com" target="_blank">Docs</a></li>
            </ul>
        </nav>
        <h3 class="text-muted">Africa's Talking</h3>
    </div>

    <br/>
    <br/>

    <div class="col-md-6">
        <pre><span id="response"></span></pre>
    </div>

    <div class="col-md-6">
        <p class="lead">Try clicking some buttons</p>
        <div class="col-sm-6 col-offset-sm-3">
            <input id="phone" type="text" class="form-control" placeholder="Your phone number">
        </div>
        <p><a class="btn btn-success btn-sm" role="button" id="signUp">Send SMS</a></p>
        <div class="col-sm-6 col-offset-sm-3">
            <input id="amount" type="text" class="form-control" placeholder="Amount e.g. USD 35">
        </div>
        <p><a class="btn btn-success btn-sm" role="button" id="airtime">Airtime</a></p>
        <div class="col-sm-6 col-offset-sm-3">
            <input id="mobileCheckoutAmount" type="text" class="form-control" placeholder="Amount e.g. KES 4456">
        </div>
        <p><a class="btn btn-success btn-sm" role="button" id="mobileCheckout">Mobile Checkout</a></p>
        <div class="col-sm-6 col-offset-sm-3">
            <input id="mobileB2CAmount" type="text" class="form-control" placeholder="Amount e.g. KES 4456">
        </div>
        <p><a class="btn btn-success btn-sm" role="button" id="mobileB2C">Mobile B2C</a></p>
    </div>

</div> <!-- /container -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/languages/json.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/languages/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/languages/xml.min.js"></script>
<script src="public/js/main.js"></script>
</body>
</html>