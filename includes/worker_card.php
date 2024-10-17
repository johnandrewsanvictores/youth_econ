<div class="workerCard">


    <div class="picture">

        <img src="../public/images/logo.jpg" alt="">

    </div>


    <div class="worker_profile">


        <div class="name_title">
            <p id="nickName">Rhesty</p>
            <p id="userSkill">Actor</p>
        </div>



        <div class="contact_info">

            <div id="phoneNum">
                <i class="fas fa-phone-alt"></i>
                <p>(+63) 9320 343 3450</p>
            </div>

            <div id="socMed">
                <i class="fab fa-facebook"></i>
                <p>User Full Name</p>
            </div>
            <div id="eMail">
                <i class="fas fa-envelope"></i>
                <p>userfullname@gmail.com</p>
            </div>


        </div>


    </div>


</div>

<style>

.workerCard {
    background-color: var(--bg-grey);
    display: flex;
    width: 100%;
    max-width: 30em;
    border-radius: 1em;
    padding-left: 1em;
}

.picture {
    background-color: var(--primary);
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    max-width:  10em;
    border-radius: 1em 0 0 1em;
    padding: 1em;

}

.picture img {
    width: 100%;
    max-width: 169px;
    aspect-ratio: 1/1;
    border-radius: 50%;
}

.worker_profile {
    display: flex;
    flex-direction: column;
    gap: 2em;
    padding:  1em;
    width: 100%;

}

.name_title {
    display: flex;
    flex-direction: column;
    gap: 0.5em;
    color: var(--primary)
}


.contact_info {
    display: flex;
    flex-direction: column;
    gap: 1em;
    color:  var(--font-dark);

}

.contact_info div {
    display: flex;
    align-items: center;
    gap: 0.5em;
}


</style>