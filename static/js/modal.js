function showModal(name)
{
    let modal = document.getElementById(name);
    modal.style.display = "block";

    window.onclick = (event) => {
        if(event.target == modal)
            modal.style.display = "none";
    };
}