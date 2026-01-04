
<?php
if(!defined('ABSPATH')) exit;

/*------- Form UI for Creating New Form -------*/

?>
<div class="wrap">
    <h1>Create a New Form </h1>

    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" >
        <?php wp_nonce_field('dp_save_form', 'dp_nonce'); ?>
        <input type="hidden" name="action" value="dp_save_form">

        <table class="form-table">
            <tr>
                <th>Form Name</th>
                <td><input type="text" name="form_name" required></td>
            </tr>
        </table>


        <h2>Fields</h2>
        <div id="dp-fields"></div>


        <button type="button" class="button" id="add-field">Add New Field</button>
        <br><br>
        <button type="submit" class="button button-primary">Save Form</button>
    </form>
</div>


<script>

        document.getElementById('add-field').addEventListener('click', function() {
        addField();
        });


        let fieldIndex = 0;

        function addField(field = {}) {
            document.getElementById('dp-fields').insertAdjacentHTML(
                'beforeend',
                `
                <div class="dp-field" style="border:1px solid skyblue; padding:10px; margin-bottom:10px;">
                    <select name="fields[${fieldIndex}][type]">
                        <option value="text">Text</option>
                        <option value="email">Email</option>
                        <option value="number">Number</option>
                        <option value="date">Date</option>
                        <option value="textarea">Textarea</option>
                    </select>

                    <input type="text" name="fields[${fieldIndex}][label]" value="${field.label || ''}">
                    <input type="text" name="fields[${fieldIndex}][name]" value="${field.name || ''}">
                    <input type="checkbox" name="fields[${fieldIndex}][required]" ${field.required ? 'checked' : ''}>
                </div>
                `
            );
            fieldIndex++;
        }

</script>




<?php


if ( isset($_GET['saved']) ) : ?>
    <div class="notice notice-success is-dismissible">
        <p> Form saved successfully 🎉 </p>
    </div>
<?php endif; ?>




