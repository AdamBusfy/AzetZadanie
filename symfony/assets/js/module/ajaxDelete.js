import $ from "jquery";

$(document).ready(() => {
    $("button[name='delete_form[submitButton]']").click(event => {
        event.preventDefault();

        const $button = $(event.target);

        if (!confirm("Are you sure you want to delete this item?")) {
            return;
        }

        const $form = $button.closest('form');
        const formDataArray = $form.serializeArray();
        const formDataObject = {};

        for (const item of formDataArray) {
            formDataObject[item.name] = item.value;
        }

        $.ajax({
            method: 'POST',
            url: '',
            data: formDataObject
        }).done(() => {
            $form.closest('.card').parent().remove();
        });
    });
});