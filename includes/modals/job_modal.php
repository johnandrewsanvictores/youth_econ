<div class="job-modal-wrapper">
    <div class="modal-container">
        <div>
            <button id="job-modal-close-btn"><i class="fas fa-times"></i></button>
            <h5>Add Job</h5>
        </div>
        <form action="" method="POST">
            <div>
                <label for="title_job">Title: <span class="red-text">*</span></label>
                <input type="text" name="title_job" id="title-job" required>
            </div>

            <div>
                <p>Business Field <span class="red-text">*</span></p>
                <div class="field-select">
                    <select name="field" required>
                        <?php
                        $businessModel = new BusinessModel($connection);
                        $businessFields = $businessModel->getBusinessFields();

                        if ($businessFields) {
                            foreach ($businessFields as $field) {
                                $id = htmlspecialchars($field['id']);
                                $title = htmlspecialchars($field['title']);

                                echo "<option value='$id'>$title</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <button type="submit" name="add_job_btn">Add</button>
        </form>
    </div>
</div>

<style>
    select {
        /* Reset Select */
        appearance: none;
        outline: 10px red;
        border: 0;
        box-shadow: none;
        /* Personalize */
        flex: 1;
        padding: 0 1em;
        color: #fff;
        background-color: var(--primary);
        background-image: none;
        cursor: pointer;
    }

    /* Remove IE arrow */
    select::-ms-expand {
        display: none;
    }

    /* Custom Select wrapper */
    .field-select {
        position: relative;
        display: flex;
        width: 15em;
        height: 3em;
        border-radius: .25em;
        overflow: hidden;
    }

    /* Arrow */
    .field-select::after {
        content: '\25BC';
        position: absolute;
        top: 0;
        right: 0;
        padding: 1em;
        background-color: var(--dark-primary);
        transition: .25s all ease;
        pointer-events: none;
        color: var(--primary);
    }

    /* Transition */
    .field-select:hover::after {
        color: var(--tertiary);
    }

    option:hover {
        background-color: var(--primary);
    }





    .red-text {
        color: var(--red);
    }

    .job-modal-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        background-color: rgba(0, 0, 0, 0.8);
        width: 100vw;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        transform: scale(0);
        opacity: 0;
        visibility: hidden;
    }

    .job-modal-wrapper .modal-container {
        width: 100%;
        max-width: 30em;
        background-color: var(--bg-white);
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        transform: scale(0);
        opacity: 0;
        visibility: hidden;
        transition: 0.3s all ease-out;
    }

    .job-modal-wrapper .modal-container>div {
        background-color: var(--primary);
        width: 100%;
        padding: 1em;
        color: var(--font-white);
        position: relative;
    }

    .job-modal-wrapper .modal-container>div h5 {
        color: var(--font-white);
        text-align: center;
    }

    #job-modal-close-btn {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        width: 2em;
        height: 2em;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        outline: none;
        border: none;
        cursor: pointer;
        background-color: var(--bg-white);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #job-modal-close-btn i {
        color: var(--primary);
        font-size: 1.4rem;
    }

    .icon-input-container {
        display: flex;
        flex-direction: column;
        gap: 0.5em;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 1em;
        width: 100%;
        padding: 2em;
        justify-content: center;

    }

    form>div {
        display: flex;
        flex-direction: column;
    }

    #title-job {
        width: 100%;
    }

    .selection-icons-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(30px, 30px));
        gap: 1em 0.5em;
        padding: 1em;
        border-radius: 5px;
    }

    .selection-container-wrapper {
        background-color: #fff;
        height: 15em;
        border-radius: 5px;
        overflow: auto;
    }

    #selected-icon {
        display: none;
    }

    .job-icon {
        width: 30px;
        height: 30px;
        cursor: pointer;
    }

    .job-icon:hover {
        background-color: var(--secondary);
    }

    .job-modal-wrapper input {
        padding: 0.5em 1em;
        border-radius: 5px;
    }

    .job-modal-wrapper button {
        padding: 0.5em 1em;
        border-radius: 5px;
        background-color: var(--primary);
        color: var(--font-white);
        width: 100%;
        max-width: 10em;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const jobModule = (function() {
            const selectionIconContainer = document.querySelector('.selection-icons-container');
            const jobModalCloseBtn = document.querySelector('#job-modal-close-btn');
            const jobModalWrapper = document.querySelector('.job-modal-wrapper');
            const selectedIcon = document.querySelector('#selected-icon');
            const selectedIconInput = document.querySelector('#selected-icon-input');
            const modal_container = document.querySelector('.job-modal-wrapper .modal-container');


            function init() {
                jobModalCloseBtn.addEventListener('click', closeModal);
            }

            function closeModal() {
                jobModalWrapper.querySelector('form').reset();

                modal_container.style.opacity = '0';
                modal_container.style.visibility = 'hidden';
                modal_container.style.transform = 'scale(0)';

                jobModalWrapper.style.opacity = '0';
                jobModalWrapper.style.visibility = 'hidden';
                jobModalWrapper.style.transform = 'scale(0)';
            }

            return {
                init
            };
        })();

        jobModule.init();
    });
</script>