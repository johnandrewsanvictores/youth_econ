<div class="job-select-wrap">
    <button>Select</button>
    <div class="select">
        <?php
        $workerModel = new WorkerModel($connection);
        $jobs = $workerModel->getJob();

        if ($jobs) {
            foreach ($jobs as $job) {
                $id = htmlspecialchars($job['id']);
                $title = htmlspecialchars($job['job_title']);

                echo "
                    <label for='$title-$id'>
                        <input value='$id' id='$title-$id' type='checkbox' name='jobs[]'>
                        $title
                    </label>
                ";
            }
        }
        ?>
    </div>
</div>

<style>
    .job-select-wrap {
        width: 100%;
        position: relative;
    }

    .job-select-wrap .select {
        display: none;
        background-color: #fff;
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        position: absolute;
        height: 10em;
        overflow: auto;
        z-index: 5;

    }

    .job-select-wrap label {
        display: block;
        cursor: pointer;
        margin-bottom: 10px;
    }

    .job-select-wrap label:hover,
    .job-select-wrap label:focus {
        color: var(--secondary);
    }

    .job-select-wrap button {
        /* Block */
        display: block;
        width: 100%;
        height: 45px;
        padding: 6px 12px;
        position: relative;

        /* Style */
        background-color: var(--secondary);
        border-radius: 0;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        border: none;
        /* Text */
        text-align: left;
        line-height: 1.42857;
        color: var(--font-white);
        border-radius: 5px 5px 0 0;
    }

    .job-select-wrap button:after {
        font-size: 12px;
        display: inline-block;
        width: 17px;
        height: 100%;
        line-height: 42px;
        text-align: center;
        content: '\25BC';
        position: absolute;
        right: 0px;
        top: 0px;
        background-color: var(--primary);
        border-radius: 0 5px 5px 0;
    }

    .job-select-wrap button.afk-active:after {
        content: '\25B2';
    }

    .job-select-wrap button:hover,
    .job-select-wrap button:focus {
        background-color: var(--secondary);
        border-color: var(--secondary);
        color: #fff;
    }

    .job-select-wrap button:hover:after,
    .job-select-wrap button:focus:after {
        background-color: var(--primary);
        /* Manually darkened var(--secondary) */
        border-color: var(--primary);
        /* Manually darkened var(--secondary) */
        color: #fff;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.job-select-wrap button');
        const checkboxes = document.querySelectorAll('.job-select-wrap input[type="checkbox"]');

        buttons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const selectDiv = this.nextElementSibling;

                if (selectDiv && selectDiv.classList.contains('select')) {
                    selectDiv.style.display = (selectDiv.style.display === 'block') ? 'none' : 'block';
                }

                this.classList.toggle('active'); // Toggle the arrow by toggling 'active' class
            });
        });

        // Update button text based on selected checkboxes
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                updateButtonText(this.closest('.job-select-wrap'));
            });
        });

        function updateButtonText(wrapper) {
            const button = wrapper.querySelector('button');
            const selectedCheckboxes = wrapper.querySelectorAll('input[type="checkbox"]:checked');
            const selectedTitles = Array.from(selectedCheckboxes).map(cb => cb.parentElement.textContent.trim());

            if (selectedTitles.length === 0) {
                button.textContent = 'Select'; // Default text
            } else if (selectedTitles.length >= 3) {
                button.textContent = `${selectedTitles.length} jobs selected`; // If 3 or more selected
            } else {
                button.textContent = selectedTitles.join(', '); // Join selected titles with comma
            }
        }
    });
</script>