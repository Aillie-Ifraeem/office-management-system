console.log("JS loaded");


document.addEventListener("submit", async (e) =>{
    if (!e.target.classList.contains("submitForm")) return;

    e.preventDefault();

    const formData = new FormData(e.target);

    const response = await fetch("submit_task.php",{
        method: "POST",
        body: formData
    });

    const result = await response.json();

    alert(result.message);

    if(result.status === "success"){
        loadTasks();
    }
})