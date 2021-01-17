// import $ from "jquery";
//
// $(document).ready(() => {
//     $("button[name='edit_form[submitButton]']").click(event => {
//         event.preventDefault();
//
//         const $button = $(event.target);
//
//         // if (!confirm("Are you sure you want to edit this item?")) {
//         //     return;
//         // }
//
//         const $form = $button.closest('form');
//         const formDataArray = $form.serializeArray();
//         const formDataObject = {};
//
//         console.log(formDataArray)
//
//         for (const item of formDataArray) {
//             formDataObject[item.name] = item.value;
//             formDataObject[item.description] = item.description;
//             formDataObject[item.id] = item.id;
//         }
//
//         $.ajax({
//             method: 'POST',
//             url: '',
//             data: formDataObject
//         }).done(() => {
//             $form.closest('.card').parent();
//         });
//     });
// });