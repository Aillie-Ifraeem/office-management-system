

document.querySelectorAll(".submitTaskBtn").forEach(btn=>{
btn.addEventListener("click", () =>{
document.getElementById("managerTaskId").value = btn.dataset.taskId;
document.getElementById("tlSubmitModal").style.display = "block";
});
});

function closeTLModal() {
    document.getElementById("tlSubmitModal").style.display = "none";
}


document.getElementById("tlSubmitForm").addEventListener("submit", async (e) => {
   
    e.preventDefault();

    const formData = new FormData(e.target);

    const response = await fetch("submit_tl_task.php",{
        method: "POST",
        body: formData
    });

    const result = await response.json();
    
    alert(result.message);

    if (result.status === "success") {
        closeTLModal();
        e.target.reset();
        location.reload();

    }

});