const adoptFormPopup = document.getElementById("adopt-form-popup");
const closePopupBtn = document.getElementById("close-popup");
const adoptForm = document.getElementById("adoptForm");
const petIdInput = document.getElementById("pet_id");


function showAdoptForm(petId) {
    adoptFormPopup.style.display = "block";
    petIdInput.value = petId;
}


closePopupBtn.onclick = function () {
    adoptFormPopup.style.display = "none";
};


window.onclick = function (event) {
    if (event.target == adoptFormPopup) {
        adoptFormPopup.style.display = "none";
    }
};


document.querySelectorAll('.adopt-btn').forEach(button => {
    button.addEventListener('click', function () {
        const petId = this.getAttribute('data-pet-id');
        showAdoptForm(petId);
    });
});
