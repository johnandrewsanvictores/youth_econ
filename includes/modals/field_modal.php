<div class="field-modal-wrapper">
    <div class="modal-container">
        <button id="field-modal-close-btn"><i class="fas fa-times-circle"></i></button>
        <h5>Add New Business Type</h5>
        <form action="" method="POST">
            <div>
                <label for="title_field">Title: <span class="red-text">*</span></label>
                <input type="text" name="title_field" id="title-field" required>
            </div>

            <div class="icon-input-container">
                <label>Choose Icons: <span class="red-text">*</span></label>
                <input type="search" name="icon-search" id="icon-search" placeholder="Search">
                <input type="hidden" name="selected_icon" id="selected-icon-input" required>
                <div class=" selection-container-wrapper">
                    <div class="selection-icons-container">
                    </div>
                </div>

                <div class="select-icon-container">
                    <p>Selected Icon:</p>
                    <img src="" alt="icon" id="selected-icon" class="field-icon">
                </div>
            </div>

            <button type="submit" name="add_field_btn">Add</button>
        </form>
    </div>
</div>

<style>
    .red-text {
        color: var(--red);
    }

    .field-modal-wrapper {
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

    .field-modal-wrapper .modal-container {
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

    #field-modal-close-btn {
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

    #field-modal-close-btn i {
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

    #title-field {
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

    .field-icon {
        width: 30px;
        height: 30px;
        cursor: pointer;
    }

    .field-icon:hover {
        background-color: var(--secondary);
    }

    .field-modal-wrapper input {
        padding: 0.5em 1em;
        border-radius: 5px;
    }

    .field-modal-wrapper button {
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
        const IconModule = (function() {
            const iconSearchInput = document.querySelector('#icon-search');
            const selectionIconContainer = document.querySelector('.selection-icons-container');
            const fieldModalCloseBtn = document.querySelector('#field-modal-close-btn');
            const fieldModalWrapper = document.querySelector('.field-modal-wrapper');
            const selectedIcon = document.querySelector('#selected-icon');
            const selectedIconInput = document.querySelector('#selected-icon-input');
            const modal_container = document.querySelector('.field-modal-wrapper .modal-container');


            function init() {
                fieldModalCloseBtn.addEventListener('click', closeModal);
                iconSearchInput.addEventListener('input', handleIconSearch);
            }

            function closeModal() {
                selectedIcon.style.display = 'none';
                selectedIconInput.value = '';
                fieldModalWrapper.querySelector('form').reset();

                modal_container.style.opacity = '0';
                modal_container.style.visibility = 'hidden';
                modal_container.style.transform = 'scale(0)';

                fieldModalWrapper.style.opacity = '0';
                fieldModalWrapper.style.visibility = 'hidden';
                fieldModalWrapper.style.transform = 'scale(0)';
            }

            async function handleIconSearch(e) {
                const validInput = /^[a-zA-Z\s]*$/;
                const inputValue = iconSearchInput.value;

                selectionIconContainer.innerHTML = '';

                if (validInput.test(inputValue) || e.inputType.includes("delete")) {
                    const data = await fetchIcons(inputValue);
                    displayIcons(data);
                }
            }

            async function fetchIcons(query) {
                const url = `https://api.iconify.design/search?query=${query}`;

                try {
                    const response = await fetch(url);
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    const data = await response.json();
                    return data['icons'];
                } catch (error) {
                    console.error('There was a problem with the fetch operation:', error);
                    document.getElementById("result").innerText = 'Error: ' + error.message; // Display error message
                }
            }

            function displayIcons(data) {
                if (Array.isArray(data)) {
                    if (data.length === 0) {
                        selectionIconContainer.innerHTML += '<p>No icons found!</p>';
                    } else {
                        data.forEach(iconName => {
                            const imgElement = document.createElement('img');
                            imgElement.src = `https://api.iconify.design/${iconName}.svg`;
                            imgElement.className = 'field-icon';
                            imgElement.addEventListener('click', () => selectIcon(imgElement.src));
                            selectionIconContainer.appendChild(imgElement);
                        });
                    }
                }
            }

            function selectIcon(iconSrc) {
                selectedIcon.style.display = "block";
                selectedIcon.src = iconSrc;
                selectedIconInput.value = iconSrc;
            }

            return {
                init
            };
        })();

        IconModule.init();
    });
</script>