document.addEventListener("submit",async (e) => {
if (!e.target.classList.contains("forwardTask")) return;

    e.preventDefault();

    const formData = new FormData(e.target);

    const response = await fetch("forward_to_manager.php",{
        method: "POST",
        body: formData
    });

    const result = await response.json();

    alert(result.message);

    if (result.status === "success") {
        location.reload();
    }
     
})