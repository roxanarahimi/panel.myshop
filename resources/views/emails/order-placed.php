<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">



    <title>بروز رسانی وضعیت سفارش</title>
</head>
<body dir="rtl" style="text-align: right !important; font-family: Tahoma, Rosemary !important">
<h2>سلام <?php echo explode(' ',$order->user->name)[0]  ?></h2>

<p>
    سفارشت ثبت شد.
</p>
<p>
    شماره سفارش:
   <span dir="ltr"><?php echo $order->code  ?></span>
</p>

<div class="border border-bottom-0">
    <table class="table table-borderless mb-0 w-100">
        <tbody class="">
        <tr class="">
            <th scope="col">شماره سفارش</th>
            <td scope="row"><?php echo $order->code?></td>
            <th scope="col">تاریخ ثبت</th>
            <td><?php echo  $order->payed_at?></td>
        </tr>
        <tr class="">
        </tr>
        <tr class="">
            <th scope="col">وضعیت</th>
            <td><span class="badge bg-warning"><?php echo $order->status?></span></td>
        </tr>
        </tbody>
    </table>
</div>
<table class="table border mb-0" style="width: 100% !important" >
    <tbody>
    <?php foreach ($order->items as $item){ ?>
    <tr class="">
        <td class="position-relative" style="width: 120px">
            <a href="'https://rxshop.ir/product/'<?php echo $item->product->info->slug ?>"  style="width: 80px">
                <img class="" width="80px" height="80px" src="<?php echo 'https://panel.rxshop.ir/storage/'.$item->product->info->images[0]?>" />
            </a>
            <div class="text-center text-left " style="position: absolute; bottom: 30px; left: 30px">
                <div class="cart-badge-2"><?php echo $item->quantity ?></div>
            </div>
        </td>
        <td class="text-right align-content-start align-top" style="width: 150px" >
            <div class="h-100">
                <div class=" text-right" >
                    <?php echo $item->product->info->title ?>
                </div>
                <small class=" text-right mb-3" >
                    <?php echo $item->product->size ?>
                </small>
                <div class="text-right text-black-50">
                    <small><i class="bi bi-coin ms-2"></i><?php echo $item->price?></small>
                </div>
                <?php if($item->off>0){ ?>
                <div class="text-right text-black-50" data-data="$item->off">
                    <small> <i class="bi bi-gift ms-2"></i><?php echo $item->off?>%</small>
                </div>
                <?php } ?>
                <div class="text-right" data-data="$item->amount">
                    <i class="bi bi-cash-stack ms-2"></i><?php echo $item->price*(1-$item->off/100)*$item->quantity?>
                </div>
            </div>
        </td>
    </tr>
    <?php } ?>
    </tbody>
</table>
<div class="border border-top-0 ">
    <table class="table table-borderless w-100">
        <tbody class="">
        <tr class="">
            <th class="text-center">جمع کل</th>
            <th class="text-center">تخفیف کل</th>
            <th class="text-center">هزینه ارسال</th>
            <th class="text-center">مبلغ نهایی</th>
        </tr>
        <tr class="">
            <td class="text-center"><?php echo $order->total_amount?></td>
            <td class="text-center"><?php echo $order->total_off?></td>
            <td class="text-center"><?php echo $order->delivery_amount?></td>
            <td class="text-center"><?php echo $order->amount?></td>
        </tr>

        </tbody>
    </table>
</div>
<a href="https://rxshop.ir/factor/'<?php echo $order->code ?>" class="btn btn-sm btn-block bg-primary d-block text-light" >مشاهده فاکتور</a>


<p>
    از خریدت متشکریم
    <br>
    RXShop
</p>

</body>
</html>
