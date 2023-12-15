

<div class="tkt-departments wrap nosubsub">

    <h1 class="wp-heading-inline">پنل مدیریت صبا</h1>

    <hr class="wp-header-end">
    <div id="ajax-response"></div>
    <div id="col-container" class="wp-clearfix">
        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">


                    <form id="saba-panel" method="post">
                        <p class="submit">
                            <input type="submit" name="manual" class="button button-primary" value="آپدیت دستی">
                        </p>
                        <?php wp_nonce_field('saba_panel', 'saba_panel_nonce', false); ?>

                        <h2>تنظیمات صبا</h2>
                        <div class="form-field">
                            <label for="saba-user">نام کاربری</label>
                            <input type="text" class="small-text" name="saba-user" id="saba-user" value="<?= esc_attr(sba_settings('sba-user')) ?>" required>
                        </div>
                        <div class="form-field">
                            <label for="saba-password">گذرواژه</label>
                            <input type="text" class="small-text" name="saba-password" id="saba-password" value="<?= esc_attr(sba_settings('sba-pass')) ?>" required>
                        </div>
                        <div class="form-field">
                            <label for="saba-server">آدرس سرور</label>
                            <input type="text" class="small-text" name="saba-server" id="saba-server" value="<?= esc_attr(sba_settings('sba-server')) ?>" required>
                        </div>

                        <p class="submit">
                            <input type="submit" name="submit" class="button button-primary" value="ویرایش">
                        </p>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>