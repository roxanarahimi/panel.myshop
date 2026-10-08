<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.rtl.min.css"
          integrity="sha384-CfCrinSRH2IR6a4e6fy2q6ioOX7O6Mtm1L9vRvFZ1trBncWmMePhzvafv7oIcWiW" crossorigin="anonymous">
    <title>سفارش ثبت شد</title>
    <style>
        * {
            text-align: right !important;
            direction: rtl !important;
            font-family: Tahoma, serif !important
        }
    </style>
</head>
<body>
<div class="row justify-content-center w-100">
<div class="col-12 col-md-8" style="width:100%; max-width: 500px !important; margin:0 auto !important">

    <h2>سلام <?php echo explode(' ', $order->user->name)[0] ?> عزیز </h2>

        <p>
            سفارشت ثبت شد.
        </p>


        <div class="">
            <table class="table table-borderless mb-0 w-100" style="border: none !important">
                <tbody class="">
                <tr class="">
                    <th scope="col">شماره سفارش</th>
                    <td scope="row"><?php echo $order->code ?></td>
                </tr>
                <tr class="">

                    <th scope="col">تاریخ ثبت</th>
                    <td><?php echo explode(' ', $order->payed_at)[0] ?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <table class="table border mb-0 rounded-3" style="width: 100%; border: 1px solid lightgrey; border-radius: 2px !important">
            <tbody>
            <?php foreach ($order->items as $item) { ?>
                <tr class="">
                    <td class="position-relative">
                        <a href="'https://rxshop.ir/product/'<?php echo $item->product->info->slug ?>"
                           style="width: 80px">
                            <img class="" width="80px" height="80px"
                                 src="<?php echo 'https://panel.rxshop.ir/storage/' . $item->product->info->images[0] ?>"/>
                        </a>
                        <div class="text-center text-left " style=" text-align:left !important; position: absolute; bottom: 30px; left: 30px">
                            <div class="cart-badge-2"><?php echo $item->quantity ?></div>
                        </div>
                    </td>
                    <td class="text-right align-content-start align-top">
                        <div class="h-100">
                            <div class=" text-right">
                                <?php echo $item->product->info->title ?>
                            </div>
                            <small class=" text-right mb-3">
                                <?php echo $item->product->size ?>
                            </small>
                            <?php if ($item->off > 0) { ?>
                                <div class="text-right text-black-50">
                                    <small><i class="bi bi-coin ms-2"></i><?php echo $item->price ?></small>
                                </div>
                                <div class="text-right text-black-50">
                                    <small> <i class="bi bi-gift ms-2"></i><?php echo $item->off ?>%</small>
                                </div>
                            <?php } ?>
                            <div class="text-right">
                                <i class="bi bi-cash-stack ms-2"></i><?php echo $item->price * (1 - $item->off / 100) * $item->quantity ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div class="border border-top-0 ">
            <table class="table table-borderless" style="width: 100%;border:none !important">
                <tbody class="">
                <tr class="">
                    <th class="text-center">جمع کل</th>
                    <th class="text-center">تخفیف کل</th>
                    <th class="text-center">هزینه ارسال</th>
                    <th class="text-center">مبلغ نهایی</th>
                </tr>
                <tr class="">
                    <td class="text-center"><?php echo $order->total_amount ?></td>
                    <td class="text-center"><?php echo $order->total_off ?></td>
                    <td class="text-center"><?php echo $order->delivery_amount ?></td>
                    <td class="text-center"><?php echo $order->amount ?></td>
                </tr>

                </tbody>
            </table>
        </div>
        <a href="https://rxshop.ir/factor/<?php echo $order->code ?>"
           class="btn btn-sm btn-block bg-primary d-block text-light">مشاهده فاکتور</a>


        <p>
            از خریدت متشکریم
            <br>
            RXShop
        </p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>
