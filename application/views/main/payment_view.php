<div class="p10">
    <div class="row">
        <table class="table table-bordered">
            <tr>
                <td>Application NO</td>
                <td><?= $apply_details[0]->app_id; ?></td>
            </tr>
            <tr>
                <td>Visa Type</td>
                <td><?= apptype($apply_details[0]->app_type); ?></td>
            </tr>
            <tr>
                <td>No of Enties</td>
                <td><?= $apply_details[0]->no_of_entries; ?></td>
            </tr>
            <tr>
                <td>Amount (USD)</td>
                <td>$<?= apptype_amount($apply_details[0]->app_type); ?></td>
            </tr>
            <tr>
                <td>
                    <img src="<?= base_url('public/img/paypal.png') ?>" alt="PayPal - The safer, easier way to pay online" height="30">
                </td>
                <td>
                    <form class="form-horizontal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
                        <!-- <input type="hidden" name="cmd" value="_cart"> -->
                        <input type="hidden" name="cmd" value="_xclick">
                        <input type="hidden" name="business" value="touristvisa701@gmail.com">
                        <input type="hidden" name="item_name" value="<?= apptype($apply_details[0]->app_type); ?>">
                        <input type="hidden" name="item_number" value="<?= $this->session->userdata('application_id'); ?>">
                        <input type="hidden" name="amount" value="<?= apptype_amount($apply_details[0]->app_type); ?>">
                        <input type="hidden" name="currency_code" value="USD">
                        <input type='hidden' name='cancel_return' value='<?= base_url('main/payment_faild') ?>'>
                        <input type='hidden' name='return' value='<?= base_url('main/payment_success') ?>'>
                        <!--<input type="image" name="submit" border="0" src="<?= base_url('public/img/paypal.png') ?>" alt="PayPal - The safer, easier way to pay online" width="24%" height="66"/>-->
                        <img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
                        <button name="submit" type="submit" class="btn btn-primary pl30 pr30">Pay With Paypal</button>
                    </form>
                </td>
            </tr>
        </table>
    </div>
</div>
