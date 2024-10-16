document.addEventListener("DOMContentLoaded", function() {
    var field_new_btn = document.getElementById('field-new-btn');
    var field_form = document.querySelector('.field-modal-wrapper form');

    field_new_btn.addEventListener('click', () => {
        Button_Function.new_btn_event();
    });


    field_form.addEventListener('submit', (e) => {
        Field_Request.addEntry(e, field_form);
    })

    Button_Function.rmv_btn_add_event();
});


const Button_Function = (function() {
    const modal_wrapper = document.querySelector('.field-modal-wrapper');
    const modal_container = document.querySelector('.field-modal-wrapper .modal-container');
    var rmv_field_btns = null;

    function new_btn_event() {
        modal_container.style.opacity = '1';
        modal_container.style.visibility = 'visible';
        modal_container.style.transform = 'scale(1)';

        modal_wrapper.style.opacity = '1';
        modal_wrapper.style.visibility = 'visible';
        modal_wrapper.style.transform = 'scale(1)';
    }


    function remove_btn_event(btn) {
        let current_row_element = btn.parentElement.parentElement;
        Popup1.show_confirm_dialog("Are you sure you want to remove it?", () => Field_Request.deleteEntry(btn.id, current_row_element));
    }

    function init_rmv_field_btns() {
        return document.querySelectorAll('.remove-field-btn');
    }

    function rmv_btn_add_event() {
        rmv_field_btns = init_rmv_field_btns();
        rmv_field_btns.forEach(btn => {
            btn.addEventListener('click', () => remove_btn_event(btn))
        })
    }

    return {
        new_btn_event,
        rmv_btn_add_event
    }
})();


const DOM_Manipulate = (function() {
    var field_table_body = document.querySelector('#theme-table tbody');
    var field_select = document.querySelector('.field-select select');
    var field_select_form = document.querySelector('.field-select-form select');
    
    function add_new_field_row(data) {
        field_table_body.innerHTML += `
                                    <tr>
                                    <td>
                                        <img src='${data.icon}' alt='icon'>
                                    </td>
                                    <td>${data.title}</td>
                                    <td><button class='remove-field-btn' id='${data.id}'>
                                            <i class='fas fa-trash'></i>
                                        </button></td></tr>
                                    `;
    }

    function add_new_option_select(data) {
        field_select.innerHTML += `<option value='${data.id}'>${data.title}</option>`
        field_select_form.innerHTML += `<option value='${data.id}'>${data.title}</option>`
        console.log(field_select_form);
    }

    function remove_option_select(id) {
        const current_option = field_select.querySelector(`option[value="${id}"]`)
        const form_current_option = field_select_form.querySelector(`option[value="${id}"]`)
        current_option.remove();
        form_current_option.remove();
    }

    return {
        add_new_field_row,
        add_new_option_select,
        remove_option_select
    }
})();



const Field_Request = (function() {

    function deleteEntry(id, current_row_el) {
            var xhr = new XMLHttpRequest();
            
            xhr.open("POST", "/youth_econ/api/business_api.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Show response
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        Popup1.show_message(response.message, 'success');
                        current_row_el.remove();
                        DOM_Manipulate.remove_option_select(id);
                    }else {
                        Popup1.show_message(response.message, 'error');
                    }
                }
            };
            
            // Send the request with the ID of the entry to delete
            xhr.send("field-id=" + id); 
    }

    function addEntry(event, form) {
        event.preventDefault();

        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', "/youth_econ/api/business_api.php", true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        Popup1.show_message(response.message, 'success');

                        let data = response.last_added_data;
                        DOM_Manipulate.add_new_field_row(data);
                        DOM_Manipulate.add_new_option_select(data);

                        Button_Function.rmv_btn_add_event();
                                    
                        document.querySelector('#selected-icon').style.display = 'none';
                        document.querySelector('#selected-icon-input').value = '';
                        form.reset();

                    } else {
                        Popup1.show_message(response.message, 'error');
                    }
                } else {
                    console.error('Error:', xhr.status);
                }
            }
        };

        xhr.send(formData);
    }

    return {
        deleteEntry,
        addEntry
    }
})();


const Popup1 = (function() {
    function show_message(msg, icon) {
        Swal.fire({
            position: "top right",
            icon: icon,
            title: msg,
            showConfirmButton: false,
            timer: 1500
        });
    }

    function show_confirm_dialog(msg, callback) {
        Swal.fire({
            text: msg,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                callback();
            }
        });
    }

    return {
        show_message,
        show_confirm_dialog
    }
})();