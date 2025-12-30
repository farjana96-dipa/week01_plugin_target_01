



<div class="wrap">
    <h1>Create Form</h1>

    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
        <?php wp_nonce_field('dp_save_form', 'dp_nonce'); ?>
        <input type="hidden" name="action" value="dp_save_form">

        <table class="form-table">
            <tr>
                <th>Form Name</th>
                <td><input type="text" name="form_name" required></td>
            </tr>
        </table>

        <h2>Fields</h2>
        <div id="cfb-fields"></div>

        <button type="button" class="button" id="add-field">Add Field</button>
        <br><br>
        <button type="submit" class="button button-primary">Save Form</button>
    </form>
</div>






<script>
let fieldIndex = 0;

document.getElementById('add-field').addEventListener('click', function() {
    const container = document.getElementById('cfb-fields');

    container.insertAdjacentHTML('beforeend', `
        <div style="border:1px solid #ddd;padding:10px;margin-bottom:10px;">
            <select name="fields[${fieldIndex}][type]">
                <option value="text">Text</option>
                <option value="email">Email</option>
                <option value="number">Number</option>
                <option value="textarea">Textarea</option>
            </select>

            <input type="text" name="fields[${fieldIndex}][label]" placeholder="Label" required>
            <input type="text" name="fields[${fieldIndex}][name]" placeholder="Field Name" required>

            <label>
                <input type="checkbox" name="fields[${fieldIndex}][required]"> Required
            </label>
        </div>
    `);

    fieldIndex++;
});
</script>



<?php if ( isset($_GET['saved']) ) : ?>
    <div class="notice notice-success is-dismissible">
        <p>Form saved successfully 🎉</p>
    </div>
<?php endif; ?>
