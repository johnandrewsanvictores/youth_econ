document.addEventListener("DOMContentLoaded", function() {
    var job_new_btn = document.getElementById('job-new-btn');
    var job_form = document.querySelector('.job-modal-wrapper form');

    job_new_btn.addEventListener('click', () => {
        Button_Function.new_btn_event();
    });


    job_form.addEventListener('submit', (e) => {
        Job_Request.addEntry(e, job_form);
    })

    Button_Function.rmv_btn_add_event();
});


const Button_Function = (function() {
    const modal_wrapper = document.querySelector('.job-modal-wrapper');
    const modal_container = document.querySelector('.job-modal-wrapper .modal-container');
    var rmv_job_btns = null;

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
        Popup1.show_confirm_dialog("Are you sure you want to remove it?", () => Job_Request.deleteEntry(btn.id, current_row_element));
    }

    function init_rmv_job_btns() {
        return document.querySelectorAll('.remove-job-btn');
    }

    function rmv_btn_add_event() {
        rmv_job_btns = init_rmv_job_btns();
        rmv_job_btns.forEach(btn => {
            btn.addEventListener('click', () => remove_btn_event(btn))
        })
    }

    return {
        new_btn_event,
        rmv_btn_add_event
    }
})();


const DOM_Manipulate = (function() {
    var job_table_body = document.querySelector('#theme-table tbody');
    var job_select = document.querySelector('.job-select select');
    // var job_select_form = document.querySelector('.job-select-form select');
    
    function add_new_job_row(data) {
        job_table_body.innerHTML += `
                                    <tr>
                                    <td>${data.job_title}</td>
                                    <td>${data.field_title}</td>
                                    <td><button class='remove-job-btn' id='${data.id}'>
                                            <i class='fas fa-trash'></i>
                                        </button></td></tr>
                                    `;
    }

    // function add_new_option_select(data) {
    //     job_select.innerHTML += `<option value='${data.id}'>${data.title}</option>`
    //     job_select_form.innerHTML += `<option value='${data.id}'>${data.title}</option>`
    // }

    // function remove_option_select(id) {
    //     const current_option = job_select.querySelector(`option[value="${id}"]`)
    //     const form_current_option = job_select_form.querySelector(`option[value="${id}"]`)
    //     current_option.remove();
    //     form_current_option.remove();
    // }

    return {
        add_new_job_row,
        // add_new_option_select,
        // remove_option_select
    }
})();



const Job_Request = (function() {

    function deleteEntry(id, current_row_el) {
            var xhr = new XMLHttpRequest();
            
            xhr.open("POST", "/youth_econ/api/worker_api.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Show response
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        Popup1.show_message(response.message, 'success');
                        current_row_el.remove();
                        // DOM_Manipulate.remove_option_select(id);
                    }else {
                        Popup1.show_message(response.message, 'error');
                    }
                }
            };
            
            // Send the request with the ID of the entry to delete
            xhr.send("job-id=" + id); 
    }

    function addEntry(event, form) {
        event.preventDefault();

        const formData = new FormData(form);
        formData.append("action", "addJob");

        const xhr = new XMLHttpRequest();
        xhr.open('POST', "/youth_econ/api/worker_api.php", true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        Popup1.show_message(response.message, 'success');

                        let data = response.last_added_data;
                        DOM_Manipulate.add_new_job_row(data);
                        // DOM_Manipulate.add_new_option_select(data);

                        Button_Function.rmv_btn_add_event();
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