jQuery(document).ready(function ($) {

    let ownerIndex = $('#dp-owner-wrapper .dp-owner-item').length;

    $(document).on('click', '#dp-add-owner', function (e) {
        e.preventDefault();

        const html = `
            <div class="dp-owner-item" style="border:1px solid #ddd;padding:10px;margin-bottom:10px;">

                <div class="dp-owner-row">
                    <label>Name</label><br>
                    <input type="text" name="dp_owners[${ownerIndex}][name]" />
                </div>

                <div class="dp-owner-row">
                    <label>Phone</label><br>
                    <input type="text" name="dp_owners[${ownerIndex}][phone]" />
                </div>

                <div class="dp-owner-row">
                    <label>Email</label><br>
                    <input type="text" name="dp_owners[${ownerIndex}][email]" />
                </div>

                <button type="button" class="button dp-remove-owner">Remove</button>
            </div>
        `;

        $('#dp-owner-wrapper').append(html);
        ownerIndex++;
    });

    $(document).on('click', '.dp-remove-owner', function () {
        $(this).closest('.dp-owner-item').remove();
    });

});
