<input type="checkbox" id="<?php echo $id ?>" name="<?php echo $name ?>" value="1" <?php echo checked( 1, $this->get_option('log_for_all'), false );
 ?> />
<label for="<?php echo $name ?>">
    Log for logged in users as well.
</label>
