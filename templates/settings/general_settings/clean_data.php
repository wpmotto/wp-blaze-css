<input type="checkbox" id="<?php echo $id ?>" name="<?php echo $name ?>" value="1" <?php echo checked( 1, $this->get_option('clean_data'), false );
 ?> />
<label for="<?php echo $name ?>">
    This will cleanup and delete all plugin data after deactivating the plugin.
</label>