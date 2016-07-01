
<?php include 'header.php';?>

            <p>Hi <?php $model->table_ld_display_name;?>,</p>
            <p>You are responsible account manager from this movement and can login with your below given details.</p>
            <h1>Your details</h1>
            <div>Email: <?php echo $model->table_ld_email_id;?>.</div>

            <div>User Name: <?php echo $model->table_ld_user_name;?>.</div>

            <div>Password: <?php echo $model->table_ld_user_pwd;?>.</div>


            <div>Account Valid till : <?php echo $model->table_account_validity;?>.</div>

            <!-- button -->

            <table class="btn-primary" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>

                </td>
              </tr>
            </table>
            <!-- /button -->
            <p>Thanks, have a lovely day.</p>
            <p><a href="http://twitter.com/leemunroe">Banner Management</a></p>

<?php include 'footer.php';?>


