<?php $id = str_replace('_', '-', $name); ?>
<div class="myupload img">
  <p>ドラッグ&ドロップまたは <label for="<?php echo $id; ?>" class="btn btn-outline-primary btn-sm mb-0"><i class="fas fa-folder-open"></i>ファイルを選択</label></p>
  <input type="file" <?php echo !empty($required) ? 'data-rule-required="#' . $id . '-x:blank"' : ''; ?> name="<?php echo $name; ?>" id="<?php echo $id; ?>" hidden accept="<?php echo isset($accept) ? $accept : 'image/*'; ?>">
  <input type="text" hidden name="<?php echo $name; ?>_old" value="<?php echo $value; ?>">
  <input type="hidden" id="<?php echo $id; ?>-x" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if (isset($path)) : ?>data-path="<?php echo $path; ?>" <?php endif; ?>>
</div>