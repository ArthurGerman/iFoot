function showModal(id) {
    const modal = document.getElementById(id);
    modal.showModal();
}

function closeModal(id, isEdit = false) {
    const modal = document.getElementById(id);
    if(!isEdit){
        const inputs = document.querySelectorAll(`#${id} input`)
        inputs.forEach(input => input.value = "")
    }
    modal.close();
}