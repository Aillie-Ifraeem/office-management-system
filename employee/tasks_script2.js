async function loadTasks(){

    try{
        const response = await fetch("tasks_process.php");
        const data = await response.json();
        
        const taskList = document.getElementById("taskList");

        if (data.status !== "success") {
            taskList.innerHTML = "failed to load tasks";
            return;
        }

        if (data.tasks.length === 0) {
            taskList.innerHTML = "no tasks assigned";
            return;
        }

        let html = "<ul>";

        data.tasks.forEach(task => {
            let submissionUI = "";

            // If task already submitted
            if (task.file_path) {
                const fileName = task.file_path.split("/").pop();

                submissionUI = `
                    <p>
                        <strong>Uploaded file:</strong>
                        <a href="${task.file_path}" target="_blank">
                            ${fileName}
                        </a><br>
                        <small>Submitted at: ${task.submitted_at}</small>
                    </p>
                `;
            } 
            // Not submitted yet
            else {
                submissionUI = `
                    <button onclick="toggleSubmitForm(${task.id})">
                        Submit Work
                    </button>

                    <div id="submitForm-${task.id}" style="display:none;">
                        <form class="submitForm" data-task-id="${task.id}" enctype="multipart/form-data">
                            <input type="hidden" name="task_id" value="${task.id}">
                            <input type="file" name="task_file" required>
                            <textarea name="remarks" placeholder="Remarks (optional)"></textarea>
                            <button type="submit">Submit Task</button>
                        </form>
                    </div>
                `;
            }

            html += `
                <li>
                    <strong>${task.title}</strong><br>
                    ${task.description}<br>
                    <strong>Status:</strong> ${task.status}<br><br>
                    ${submissionUI}
                </li>
                <hr>
            `;
        });

        html += "</ul>";
        taskList.innerHTML = html;
    }catch(error){
        console.error(error);
    }
}

function toggleSubmitForm(taskId) {
    const form = document.getElementById(`submitForm-${taskId}`);
    form.style.display = form.style.display === "none" ? "block" : "none";
}





loadTasks();

//id="submitForm-${task.id}" style="display:none;