const select_field_node = document.querySelector('.field-select select')

const table = new DataTable('#example', {
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

table.on('click', 'tbody tr', function(e) {
    e.currentTarget.classList.toggle('selected');
});

// document.querySelector('#button').addEventListener('click', function() {
//     alert(table.rows('.selected').data().length + ' row(s) selected');
// });



select_field_node.addEventListener('change', () => {
    table.context[0].ajax.data = {'action' : "datatableDisplay", "selected_field" : select_field_node.value}
        // Update DataTable data
        table.draw();

    console.log(select_field_node.value);
})

var bus_new_btn = document.querySelector('#bus-new-btn');
bus_new_btn.addEventListener('click', showBSModal);

const business_form = document.getElementById('bs-form');
console.log(business_form);
business_form.addEventListener('submit', (e) => Request_Business.submitData(e))


const Request_Business = (function() {
    function submitData(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
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
                        event.target.reset();
                        document.querySelector('.logo-container img').src = "../images/placeholder.svg";
                        resetMap() //business_form_modal function
                        // PopUp.closeModal();
                        // Table.deselect_all_selected_row();
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
        submitData
    }
})();
