<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>بروز رسانی وضعیت سفارش</title>
</head>
<body dir="rtl" style="text-align: right !important; font-family: 'Comic Sans MS' !important">
<h2>سلام <?php echo explode(' ',$order->user->name)[0]  ?></h2>

<p>
    سفارشت ارسال شد.
</p>

<p>
    شماره سفارش:
   <span dir="ltr"><?php echo $order->code  ?></span>
</p>

<p>
    کد رهگیری پست:
    <?php echo $order->post_tracking_number  ?>
</p>

<p>
    از خریدت متشکریم
    <br>
    RXShop
</p>

</body>
</html>
