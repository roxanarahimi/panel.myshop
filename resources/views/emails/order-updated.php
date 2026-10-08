<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>بروز رسانی وضعیت سفارش</title>
    <style>
        * {
            text-align: right !important;
            direction: rtl !important;
            font-family: Tahoma, Rosemary !important
        }
    </style>
</head>
<body>
<div style="width: 100%; text-align: right; background-color: #F8F9FB">
    <img src="https://panel.rxshop.ir/img/rx.jpg" width="150px" alt="">
</div>
<div class="row" style="width:100%; padding: 20px">
    <div class="col-12" style="width:100%;!important">

        <h2>سلام <?php echo explode(' ', $order->user->name)[0] ?> عزیز </h2>

        <p>
            سفارشت ارسال شد.
        </p>

        <p>
            شماره سفارش:
            <span dir="ltr"><?php echo $order->code ?></span>
        </p>

        <p>
            کد رهگیری پست:
            <?php echo $order->post_tracking_number ?>
        </p>
        <a href="https://rxshop.ir/factor/<?php echo $order->code ?>" class="btn btn-sm btn-block bg-primary d-block text-light">مشاهده فاکتور</a>

    </div>
</div>
<p style="width: 100% ; text-align: left !important"><a href="https://rxshop.ir" style="color: black; text-align: left !important;margin: 0 !important">https://rxshop.ir</a></p>
<div style="width: 100%; background-color: #F8F9FB; padding: 10px 0">
    <p style="width: 100%;text-align: left !important;margin: 0 !important">از خریدت متشکریم</p>
    <p style="width: 100%;text-align: left !important;margin: 0 !important">RXShop</p>
</div>
</body>
</html>
