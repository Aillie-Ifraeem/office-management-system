const modal = document.getElementById("assignTaskModal");
const assignedToInput = document.getElementById("assignedTo");

//open popup screen
document.addEventListener("click", (e)=>{

if(!e.target.classList.contains("assignTaskBtn")) return;

const empId = e.target.getAttribute("data-emp-id");
assignedToInput.value = empId;

modal.style.display = "block";

});

function closeModal(){
    modal.style.display = "none";
}



document.getElementById("assignTaskForm").addEventListener("submit", async (e) => {
   
    e.preventDefault();

    const formData = new FormData(e.target);

    const response = await fetch("assign_task.php",{
        method: "POST",
        body: formData
    });

    const result = await response.json();
    
    alert(result.message);

    if (result.status === "success") {
        closeModal();
        e.target.reset();

    }

});