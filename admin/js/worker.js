const select_job_node = document.querySelector('.job-select select');
const worker_form = document.getElementById('worker-form');
const form_title = document.querySelector('.worker-form-header h5');
var table = null;

var old_img = null; //this is the src of the old img if override in edit.

document.addEventListener("DOMContentLoaded", function() {
    table = Table.initDataTable();
    Table.addSelectEvent(table);

    Controls.add_events();
    Form.add_events();
});


const Table = (function() {
    function initDataTable() {
        return new DataTable('#example', {
            scrollX: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "../../api/worker_api.php",
                type: "post",
                data: {'action' : "datatableDisplay", "selected_job" : select_job_node.value},
            },

            "columns": [
                { "data": "id", visible: false },
                { "data": "name" },
                { "data": "age" },
                { "data": "job_titles" },
                { "data": "contact_number" },
                { "data": "email" },
                { "data": "education_level" },
            ]
        });
    }


    function addSelectEvent(table) {
        table.on('click', 'tbody tr', function (e) {
            e.currentTarget.classList.toggle('selected');
        });
    }

    function getSelectedRow(table) {
        return table.rows('.selected').data()
    }

    function removeRow(table) {
        table.row('.selected').remove().draw(false);
    }

    function deselect_all_selected_row() {
        document.querySelectorAll('tbody tr.selected').forEach(el => el.classList.remove('selected'));
    }

    function select_all_rows() {
        document.querySelectorAll('tbody tr').forEach(row => row.classList.add('selected'));
    }


    return {
        initDataTable,
        addSelectEvent,
        getSelectedRow,
        removeRow,
        deselect_all_selected_row,
        select_all_rows
    }
})();


const Controls = (function() {
    var worker_new_btn = document.querySelector('#worker-new-btn');
    var worker_edit_btn  = document.querySelector('#edit-btn');
    var worker_rmv_btn  = document.querySelector('#remove-btn');

    function add_events() {
        worker_new_btn.addEventListener('click', Form.add_data_event);
        worker_edit_btn.addEventListener('click', Form.edit_data_event);
        worker_rmv_btn.addEventListener('click', remove_data_event);
        select_job_node.addEventListener('change', select_job_change_event);
    }

    function get_selected_ids() {
        var datas = Table.getSelectedRow(table);
        var ids = [];
        for(let i =0; i < datas.length; i++ ) {
            ids.push(datas[i].id);
        }

        return ids;
    }

    function remove_data_event() {
        const selected_rows = Table.getSelectedRow(table);

        if(selected_rows.length < 1) {
            Popup1.show_message('Please ensure at least one row is selected.', 'warning') ;
            return;
        }

        const ids = get_selected_ids();
        Popup1.show_confirm_dialog("Are you sure you want to delete it?", () => Request_Worker.removeData(ids));
    }

    function select_job_change_event() {
        table.context[0].ajax.data = {'action' : "datatableDisplay", "selected_job" : select_job_node.value}
        table.draw();
    }

    return {
        add_events
    }

})(); 


const Form = (function() {

    function add_events() {
        worker_form.addEventListener('submit', (e) => Request_Worker.submitData(e));
    }

    function add_data_event() {
        form_title.textContent = "ADD WORKER INFORMATION";
        showBSModal();
    }

    async function edit_data_event(e) {
        const selected_rows = Table.getSelectedRow(table);
        const img_input = document.getElementById('input-file-btn');
    
        img_input.required = false;
        
    
        form_title.textContent = "UPDATE WORKER INFORMATION";
          
        if(selected_rows.length != 1) {
            Popup1.show_message('Please ensure only one row is selected.', 'warning') ;
            Table.deselect_all_selected_row();
            return;
        }
    
        const data = await Request_Worker.get_specific_business_data(selected_rows[0].id);
        Form.fill_info(data);

        showBSModal();
    }

    function fill_info(response) {
        
        var data = response[0];
        console.log(data);
        // var soc_meds = response.social_media;
        // const img_preview = document.querySelector('.logo-container img');
        // const name = document.querySelector('input[name="business_name"]');
        // const select_job = document.querySelector('.job-select-form select[name="job"]');
        // const contact_number = document.querySelector('input[name="bus_contact_num"');
        // const description = document.querySelector('.desc-container textarea');
        // const location = document.querySelector('.location-output-container input');

        // const fb_input = document.querySelector('input[name="facebook"]');
        // const ig_input = document.querySelector('input[name="instagram"]');
        // const tt_input = document.querySelector('input[name="tiktok"]');

        // const id_input = document.querySelector('input[name="business_id"]');

        // old_img = data.logo;
        // img_preview.src = "../" + data.logo;
        // name.value = data.name;
        // select_job.value = data.job_id;
        // contact_number.value = data.contact_number;
        // description.value = data.description;
        // location.value = data.location;

        // id_input.value = data.id;

        // var latlang= data.location.split(',');

        // addMarker(latlang[0], latlang[1], "Coordinates: " + latlang[0] + ", " + latlang[1]);

        // soc_meds.forEach(soc_med => {
        //     switch(soc_med.social_media_id) {
        //         case '1':
        //             fb_input.value = soc_med.link;
        //             break;
        //         case '2':
        //             ig_input.value = soc_med.link;
        //             break;
        //         case '3':
        //             tt_input.value = soc_med.link;
        //             break;
        //     }
        // });

    }

    return {
        fill_info,
        add_events,
        add_data_event,
        edit_data_event
    }
})();


const Request_Worker = (function() {
    function submitData(event) {
        event.preventDefault();
        
        if(form_title.textContent === "ADD WORKER INFORMATION") {
            addData();
        }else if(form_title.textContent === "Update Business") {
            updateData();
        }
    }

    function addData() {
        const formData = new FormData(worker_form);
        formData.append("action", "addWorker");
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/youth_econ/api/worker_api.php', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        table.context[0].ajax.data = {'action' : "datatableDisplay", "selected_job" : select_job_node.value}
                        table.draw();
                        Popup1.show_message(response.message, 'success');
                        reset_bs_form();
                        // PopUp.closeModal();
                        Table.deselect_all_selected_row();
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

    function updateData() {
        const formData = new FormData(worker_form);
        formData.append('action', 'updateBusiness');
        formData.append('img_src', document.querySelector('.logo-container img').src);
        formData.append('old_img_src', old_img);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/youth_econ/api/business_api.php', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        table.context[0].ajax.data = {'action' : "datatableDisplay", "selected_job" : select_job_node.value}
                        table.draw();
                        Popup1.show_message(response.message, 'success');
                        reset_bs_form();
                        closeBSModal();
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

    function removeData(ids) {
        const xhr = new XMLHttpRequest();
        console.log(JSON.stringify(ids));


        const requestBody = 'ids=' + JSON.stringify(ids) + `&action=remove`;
        xhr.open('POST', '/youth_econ/api/worker_api.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        Table.deselect_all_selected_row();
                        table.row('.selected').remove().draw(false);
                        
                        Popup1.show_message(response.message, 'success');

                    } else {
                        Popup1.show_message(response.message, 'error');
                    }
                } else {
                    PopUp.showMessage('Error occurred while processing your request.', 'error');
                }
            }
        };
        xhr.send(requestBody);
    }

    function get_specific_business_data(id) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
    
            const requestBody = 'id=' + id + '&action=get_specific_data'; // Serialize array to JSON string
            xhr.open('POST', '/youth_econ/api/worker_api.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            resolve(response.data);
                        } else {
                            reject(response.message || 'An error occurred');
                        }
                    } else {
                        reject('Error occurred while processing your request');
                    }
                }
            };
    
            xhr.send(requestBody);
        });
    }


    return {
        submitData,
        removeData,
        get_specific_business_data
    }
})();
