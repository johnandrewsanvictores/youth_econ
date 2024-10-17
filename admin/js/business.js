const select_field_node = document.querySelector('.field-select select');
const business_form = document.getElementById('bs-form');
const form_title = document.querySelector('.bs-form-header h5');
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
                url: "../../api/business_api.php",
                type: "post",
                data: {'action' : "datatableDisplay", "selected_field" : select_field_node.value},
            },
            // ajax: "../../api/business_datatable_api.php",
            "columns": [
                { "data": "id", visible: false },
                { "data": "name" },
                { "data": "field" },
                { "data": "location" },
                { "data": "contact_number" },
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
    var bus_new_btn = document.querySelector('#bus-new-btn');
    var bus_edit_btn  = document.querySelector('#edit-btn');
    var bus_rmv_btn  = document.querySelector('#remove-btn');

    function add_events() {
        bus_new_btn.addEventListener('click', Form.add_data_event);
        bus_edit_btn.addEventListener('click', Form.edit_data_event);
        bus_rmv_btn.addEventListener('click', remove_data_event);
        select_field_node.addEventListener('change', select_field_change_event);
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
        Popup1.show_confirm_dialog("Are you sure you want to delete it?", () => Request_Business.removeData(ids));
    }

    function select_field_change_event() {
        table.context[0].ajax.data = {'action' : "datatableDisplay", "selected_field" : select_field_node.value}
        table.draw();
    }

    return {
        add_events
    }

})();


const Form = (function() {

    function add_events() {
        business_form.addEventListener('submit', (e) => Request_Business.submitData(e));
    }

    function add_data_event() {
        form_title.textContent = "Add Business";
        showBSModal();
    }

    async function edit_data_event(e) {
        const selected_rows = Table.getSelectedRow(table);
        const img_input = document.getElementById('input-file-btn');
    
        img_input.required = false;
        
    
        form_title.textContent = "Update Business";
          
        if(selected_rows.length != 1) {
            Popup1.show_message('Please ensure only one row is selected.', 'warning') ;
            Table.deselect_all_selected_row();
            return;
        }
    
        const data = await Request_Business.get_specific_business_data(selected_rows[0].id);
        Form.fill_info(data);

        showBSModal();
    }

    function fill_info(response) {
        
        var data = response[0];
        var soc_meds = response.social_media;
        const img_preview = document.querySelector('.logo-container img');
        const name = document.querySelector('input[name="business_name"]');
        const select_field = document.querySelector('.field-select-form select[name="field"]');
        const contact_number = document.querySelector('input[name="bus_contact_num"');
        const description = document.querySelector('.desc-container textarea');
        const location = document.querySelector('.location-output-container input');

        const fb_input = document.querySelector('input[name="facebook"]');
        const ig_input = document.querySelector('input[name="instagram"]');
        const tt_input = document.querySelector('input[name="tiktok"]');

        const id_input = document.querySelector('input[name="business_id"]');

        old_img = data.logo;
        img_preview.src = "../" + data.logo;
        name.value = data.name;
        select_field.value = data.field_id;
        contact_number.value = data.contact_number;
        description.value = data.description;
        location.value = data.location;

        id_input.value = data.id;

        var latlang= data.location.split(',');

        addMarker(latlang[0], latlang[1], "Coordinates: " + latlang[0] + ", " + latlang[1]);

        soc_meds.forEach(soc_med => {
            switch(soc_med.social_media_id) {
                case '1':
                    fb_input.value = soc_med.link;
                    break;
                case '2':
                    ig_input.value = soc_med.link;
                    break;
                case '3':
                    tt_input.value = soc_med.link;
                    break;
            }
        });

    }

    return {
        fill_info,
        add_events,
        add_data_event,
        edit_data_event
    }
})();


const Request_Business = (function() {
    function submitData(event) {
        event.preventDefault();
        
        if(form_title.textContent === "Add Business") {
            addData();
        }else if(form_title.textContent === "Update Business") {
            updateData();
        }
    }

    function addData() {
        const formData = new FormData(business_form);
        console.log(formData);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/youth_econ/api/business_api.php', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        table.context[0].ajax.data = {'action' : "datatableDisplay", "selected_field" : select_field_node.value}
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
        const formData = new FormData(business_form);
        formData.append('action', 'updateBusiness');
        formData.append('img_src', document.querySelector('.logo-container img').src);
        formData.append('old_img_src', old_img);

        console.log(document.querySelector('.logo-container img').src);
        console.log(old_img);


        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/youth_econ/api/business_api.php', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        table.context[0].ajax.data = {'action' : "datatableDisplay", "selected_field" : select_field_node.value}
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

        const requestBody = 'ids=' + JSON.stringify(ids) + `&action=remove`;
        xhr.open('POST', '/youth_econ/api/business_api.php', true);
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
            xhr.open('POST', '/youth_econ/api/business_api.php', true);
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
