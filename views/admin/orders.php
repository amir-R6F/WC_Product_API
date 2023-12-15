

<div class="col-wrap">
    <table class="wp-list-table widefat fixed striped">
        <thead>
        <tr>
            <th scope="col" class="manage-column">sku</th>
            <th scope="col" class="manage-column">تعداد</th>
            <th scope="col" class="manage-column">id مشتری</th>
            <th scope="col" class="manage-column">قیمت محصول</th>
            <th scope="col" class="manage-column">باکس</th>
            <th scope="col" class="manage-column">شماره فاکتور</th>
            <th scope="col" class="manage-column">میزان تخفیف</th>
            <th scope="col" class="manage-column">تاریخ</th>
        </tr>
        </thead>
        <tbody id="the-list">

        <?php foreach ($orders as $order): ?>
            <tr>
                <td>
                    <?= $order->sku ?>
                </td>
                <td>
                    <?= $order->count ?>
                </td>
                <td>
                    <?= $order->customer ?>
                </td>
                <td>
                    <?= $order->price ?>
                </td>
                <td>
                    <?= $order->box ?>
                </td>
                <td>
                    <?= $order->factor_number ?>
                </td>
                <td>
                    <?= $order->offer ?>
                </td>
                <td>
                    <?= $order->create_date ?>
                </td>
            </tr>
        <?php endforeach; ?>


        </tbody>
        <tfoot>
        <tr>
            <th scope="col" class="manage-column">sku</th>
            <th scope="col" class="manage-column">تعداد</th>
            <th scope="col" class="manage-column">id مشتری</th>
            <th scope="col" class="manage-column">قیمت محصول</th>
            <th scope="col" class="manage-column">باکس</th>
            <th scope="col" class="manage-column">شماره فاکتور</th>
            <th scope="col" class="manage-column">میزان تخفیف</th>
            <th scope="col" class="manage-column">تاریخ</th>
        </tr>
        </tfoot>
    </table>
</div>
