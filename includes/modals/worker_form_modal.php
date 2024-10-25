<section class="worker-form-wrapper">
    <div class="worker-form-main-container">
        <div class="worker-form-header">
            <h5>ADD WORKER</h5>
            <button id="close-worker-form"><i class='fas fa-times'></i></button>
        </div>
        <div class="worker-form-container">
            <form action="" id="worker-form" method="POST" enctype="multipart/form-data">
                <div class="left-form">
                    <div class="logo-container">
                        <label>Profile Picture <span class="red-text">*</span></label>
                        <img id="profile-pic-preview" src="../images/placeholder.svg" alt="preview logo img">
                        <div class="input-file-div">
                            <label for="input-file-btn" class="custom-file-upload">Browse</label>
                            <input type="file" name="profile-pic" id="input-file-btn" required>
                        </div>
                    </div>

                    <div>
                        <input type="hidden" name="worker_id" id="worker-id" />
                        <label>Name <span class="red-text">*</span></label>
                        <input type="text" name="worker_name" id="worker-name" required>
                    </div>

                    <div>
                        <label>Age <span class="red-text">*</span></label>
                        <input type="number" name="age" id="worker-age" required>
                    </div>

                    <div>
                        <label>Job/s <span class="red-text">*</span></label>
                        <?php include('../../includes/job_select_tag.php'); ?>
                    </div>

                    <div>
                        <label>Contact Number <span class="red-text">*</span></label>
                        <input type="tel" name="worker_contact_num" id="worker-contact-num" pattern="09\d{2} \d{3} \d{4}" placeholder="09XX XXX XXXX" maxlength="13" required>
                    </div>
                </div>

                <div class="right-form">
                    <div class="educ-container">
                        <label>Current Education Level <span class="red-text">*</span></label>
                        <div class="educ-subcontainer">
                            <label>
                                <input type="radio" name="educ_level" value="graduate" id="educ-graduate">
                                <span>Graduate</span>
                            </label>
                            <label>
                                <input type="radio" name="educ_level" value="undergraduate" id="educ-undergraduate">
                                <span>Undergraduate</span>
                            </label>
                            <label>
                                <input type="radio" name="educ_level" value="highschool" id="educ-highschool">
                                <span>Highschool</span>
                            </label>
                        </div>
                    </div>

                    <div class="desc-container">
                        <label>Introduction <span class="red-text">*</span></label>
                        <textarea name="intro" id="worker-intro" required></textarea>
                    </div>

                    <div class="social-links-container">
                        <label>Social Media Links</label>
                        <div>
                            <div><i class="fab fa-facebook-f"></i> <span class="white-text">*</span></div>
                            <input type="url" name="facebook" id="worker-facebook" placeholder="Enter worker fb profile link">
                        </div>
                        <div>
                            <div><i class="fas fa-envelope"></i></div>
                            <input type="email" name="email" id="worker-email" placeholder="Enter worker email address">
                        </div>
                    </div>
                </div>

                <button type="submit" id="submit-worker-form">Submit</button>
            </form>
        </div>
    </div>
</section>


<style>
    .worker-form-wrapper {
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

    .worker-form-main-container {
        width: min(70em, 100%);
        height: min(42em, 100%);
        background-color: var(--bg-white);
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        border-radius: 20px;

        transform: scale(0);
        opacity: 0;
        visibility: hidden;
        transition: 0.3s all ease-out;
    }

    input[type="file"] {
        display: none;
        /* Hide the default file input */
    }

    .custom-file-upload {
        display: inline-block;
        padding: 10px 20px;
        cursor: pointer;
        background-color: var(--primary);
        color: white;
        border-radius: 5px;
        text-align: center;
    }

    .worker-form-header {
        width: 100%;
        text-align: center;
        position: relative;
        padding: 1.5em;
        background-color: var(--primary);
        border-radius: 20px 20px 0 0;
    }

    .worker-form-header h5 {
        color: var(--font-white) !important;
    }

    #close-worker-form {
        background: var(--bg-white);
        width: 2em;
        height: 2em;
        border-radius: 50%;
        border: none;
        color: var(--primary);
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
    }

    .logo-container img {
        width: 5em;
        height: 5em;
        border-radius: 50%;
    }

    .worker-form-container {
        height: 100%;
        display: flex;
        align-items: center;
    }

    #worker-form {
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1.75fr;
        gap: 3em;
        padding: 0em 2em;
    }

    #worker-form .left-form {
        display: flex;
        flex-direction: column;
        gap: 1em;
    }

    #worker-form .left-form input[type="text"],
    #worker-form .left-form input[type="number"],
    #worker-form .left-form input[type="tel"],
    #worker-form .right-form input[type="url"],
    #worker-form .right-form input[type="email"],
    .location-output-container input {
        width: 100%;
        padding: 1em 1em;
        border-radius: 5px;
    }

    #worker-form label {
        font-size: var(--small);
    }

    .logo-container {
        display: grid;
        grid-template-columns: 5em 1fr;
        align-items: center;
        gap: 0.5em;

    }

    .logo-container label {
        grid-column: 1/3;
    }


    .social-links-container {
        display: flex;
        flex-direction: column;
        gap: 1em;
    }

    .social-links-container>div {
        display: flex;
        background-color: var(--bg-white);
    }

    .social-links-container>div input {
        outline: none;
        border: none;
    }

    .social-links-container>div>div {
        background-color: var(--primary);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        aspect-ratio: 1/1;
        color: var(--font-white);
    }

    .right-form {
        display: flex;
        flex-direction: column;
        gap: 1em;
        width: 100%;
    }

    .desc-container {
        display: flex;
        flex-direction: column;
        gap: 0.5em;
    }

    .desc-container textarea {
        width: 100%;
        height: 10em;
        resize: none;
        border-radius: 5px;
        padding: 0.5em 1em;
    }








    #submit-worker-form,
    #edit-worker-form-btn {
        padding: 0.5em 1em;
        border-radius: 5px;
        background-color: var(--primary);
        color: var(--font-white);
        cursor: pointer;
        margin-top: -1.5em;
    }


    @media (max-width: 768px) {
        #worker-form {
            grid-template-columns: 1fr;
            gap: 1em;
            padding: 1em;
        }

        #submit-worker-form {
            margin-top: 0;
        }


        .worker-form-container {
            overflow: auto;
            justify-content: flex-start;
            align-items: flex-start;
            padding: 2em;
            width: 100%;
        }

        .worker-form-main-container,
        .worker-form-header {
            border-radius: 0;
        }
    }

    @media (max-width: 768px) {
        .worker-form-container {
            padding: 0em;
        }
    }





    /** Radio container styles*/


    .educ-subcontainer {
        display: flex;
        flex-wrap: wrap;
        flex-direction: column;
    }

    .educ-subcontainer label {
        display: flex;
        cursor: pointer;
        font-weight: 500;
        position: relative;
        overflow: hidden;
        margin-bottom: 0.375em;
    }

    /* Accessible outline (Remove comment to use) */
    /*
label:focus-within {
	outline: .125em solid #00005c;
}
*/

    .educ-subcontainer label input {
        position: absolute;
        left: -9999px;
    }

    .educ-subcontainer label input:checked+span {
        background-color: var(--secondary);
        color: var(--font-white);
        /* Manually mixing #fff and #00005c */
    }

    .educ-subcontainer label input:checked+span:before {
        box-shadow: inset 0 0 0 0.4375em var(--primary);
    }

    .educ-subcontainer label span {
        display: flex;
        align-items: center;
        padding: 0.375em 0.75em 0.375em 0.375em;
        border-radius: 99em;
        transition: 0.25s ease;
    }

    .educ-subcontainer label span:hover {
        background-color: #e5e5ec;
        /* Manually mixing #fff and #00005c */
    }

    .educ-subcontainer label span:before {
        display: flex;
        flex-shrink: 0;
        content: "";
        background-color: #fff;
        width: 1.5em;
        height: 1.5em;
        border-radius: 50%;
        margin-right: 0.375em;
        transition: 0.25s ease;
        box-shadow: inset 0 0 0 0.125em #00005c;
    }

    /* Codepen specific styling */
    .educ-container {
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 0.5em;
    }
</style>


<script>
    const bs_wrapper = document.querySelector('.worker-form-wrapper');
    const bs_container = document.querySelector('.worker-form-main-container');
    const bs_close_btn = document.querySelector('#close-worker-form');

    const profile_pic_btn = document.querySelector('#input-file-btn');

    bs_close_btn.addEventListener('click', closeWorkerModal);
    profile_pic_btn.addEventListener('change', (event) => setProfile(event));

    function closeWorkerModal() {
        // fieldModalWrapper.querySelector('form').reset();

        bs_container.style.opacity = '0';
        bs_container.style.visibility = 'hidden';
        bs_container.style.transform = 'scale(0)';

        bs_wrapper.style.opacity = '0';
        bs_wrapper.style.visibility = 'hidden';
        bs_wrapper.style.transform = 'scale(0)';

        reset_worker_form();
    }

    function showWorkerModal() {
        // fieldModalWrapper.querySelector('form').reset();

        bs_container.style.opacity = '1';
        bs_container.style.visibility = 'visible';
        bs_container.style.transform = 'scale(1)';

        bs_wrapper.style.opacity = '1';
        bs_wrapper.style.visibility = 'visible';
        bs_wrapper.style.transform = 'scale(1)';
    }

    function setProfile(e) {
        const img_preview = document.querySelector('.logo-container img');
        const file = e.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img_preview.src = e.target.result;
            };
            reader.readAsDataURL(file); // Convert file to base64 string
        }
    }

    function reset_worker_form() {
        document.querySelector('#worker-form').reset();
        document.querySelector('#worker-id').value = null;
        document.querySelector('.logo-container img').src = "../images/placeholder.svg";
        document.querySelector('.job-select-wrap button').textContent = "Select";
    }
</script>