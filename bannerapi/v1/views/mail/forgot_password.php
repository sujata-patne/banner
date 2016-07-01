
<?php include 'header.php';?>

            <p>Hi <?php $model->ld_display_name;?>,</p>
            <p>Your account details are updated and it's details as below.</p>
            <h1>Your details</h1>
            <div>Email: <?php echo $model->ld_email_id;?>.</div>

            <div>User Name: <?php echo $model->ld_user_name;?>.</div>

            <div>Password: <?php echo $model->ld_user_pwd;?>.</div>

            <!-- button -->

            <table class="btn-primary" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>

                </td>
              </tr>
            </table>
            <!-- /button -->
            <p>Thanks, have a lovely day.</p>
            <p><a href="">Banner Management</a></p>

<?php include 'footer.php';?>


