<html>

<head>
    <title></title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="//envato.froid.works/plugins/froiden-helper/helper.css">
    <style>
        .invalid-feedback {
            color: darkred;
        }

        .margin-top {
            margin: 20px 0
        }

        .padding-5 {
            padding: 5px
        }
    </style>
</head>

<body>
<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="mt-5">Verify Your Purchase</h1>
            <p class="bg-primary padding-5">For Domain:- {{ \request()->getHost() }}12</p>

            <p class="margin-top">
                <span class="label label-warning">ALERT</span>
                Contact your admin if you are not the admin to verify the purchase.

            </p>

            <p class="margin-top">
                <span class="label label-danger">NOTE</span>
                Click <a
                    href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-"
                    target="_blank">this link</a> to find your purchase code

            </p>

            <div id="response-message"></div>

            <form action="" id="verify-form">
                <div class="form-body">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Enter your envato purchase code</label>
                                <input type="text" id="purchase_code" name="purchase_code" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="form-group">
                                <button class="btn btn-success" type="button" id="verify-purchase"
                                        onclick="validateCode();return false;">Verify
                                </button>
                            </div>
                        </div>
                        <!--/span-->
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{!! HTML::script("js/jquery-3.6.0.min.js") !!}
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="//envato.froid.works/plugins/froiden-helper/helper.js"></script>

<script>
    function validateCode() {
        $.easyAjax({
            type: 'POST',
            url: "{{ route('purchase-verified') }}",
            data: $("#verify-form").serialize(),
            container: "#verify-form",
            messagePosition: 'inline'
        });
        return false;
    }


</script>

</body>

</html>
