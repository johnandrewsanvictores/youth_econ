<div class="job-modal-wrapper">
    <div class="modal-container">
        <button id="job-modal-close-btn"><i class="fas fa-times-circle"></i></button>
        <h5>Add Job</h5>
        <form action="" method="POST">
            <div>
                <label for="title_job">Title: <span class="red-text">*</span></label>
                <input type="text" name="title_job" id="title-job" required>
            </div>
            <button type="submit" name="add_job_btn">Add</button>
        </form>
    </div>
</div>

<style>
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
        height: 36em;
        background-color: var(--bg-white);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2em;
        padding: 2em;
        position: relative;
        transform: scale(0);
        opacity: 0;
        visibility: hidden;
        transition: 0.3s all ease-out;
    }

    #job-modal-close-btn {
        position: absolute;
        top: -20px;
        right: -30px;
        width: 3em;
        height: 3em;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        outline: none;
        border: none;
        cursor: pointer;
    }

    #job-modal-close-btn i {
        color: var(--font-white);
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
        gap: 0.5em;
        width: 100%;
    }

    #title-job {
        width: 100%;
        max-width: 20em;
        margin-left: 1em;
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
                selectedIcon.style.display = 'none';
                selectedIconInput.value = '';
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