import $ from "jquery";

$(document).ready(() => {
    $("button[name='edit_form[submitButton]']").click(event => {
        event.preventDefault();

        const $button = $(event.target);

        const $form = $button.closest('form');
        const formDataArray = $form.serializeArray();
        const formDataObject = {};

        for (const item of formDataArray) {
            formDataObject[item.name] = item.value;
            formDataObject[item.description] = item.description;
            formDataObject[item.id] = item.id;
        }

        const $card = $form.closest('.card');

        $.ajax({
            method: 'POST',
            url: '',
            data: formDataObject
        }).done(() => {
            console.log(formDataObject)

            $card.find('[data-item-name]').html(formDataObject['edit_form[name]']);
            $card.find('[data-item-description]').html(formDataObject['edit_form[description]']);
            $card.find('.modal').modal('hide');
        });
    });
});