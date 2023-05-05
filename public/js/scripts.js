const editButtons = document.querySelectorAll(".edit");
editButtons.forEach((button) => {
    button.addEventListener("click", function () {
        const row = this.parentNode.parentNode;
        const nameCell = row.querySelector(".editable:nth-child(3)");
        const dobCell = row.querySelector(".editable:nth-child(4)");
        const phoneCell = row.querySelector(".editable:nth-child(6)");

        const editButton = row.querySelector(".edit");

        if (nameCell.contentEditable === "false") {
            nameCell.contentEditable = "true";
            dobCell.innerHTML =
                '<input type="date" value="' + dobCell.textContent + '">';
            phoneCell.innerHTML =
                '<input type="number" value="' +
                phoneCell.textContent +
                '" min="0" max="999999999999">';

            editButton.textContent = "Save";
            nameCell.focus();
        } else {
            nameCell.contentEditable = "false";
            dobCell.innerHTML = dobCell.firstChild.value;
            phoneCell.innerHTML = phoneCell.firstChild.value;

            editButton.textContent = "Edit";

            const id = this.getAttribute("data-id");
            const name = nameCell.textContent;
            const dob = dobCell.textContent;
            const phone = phoneCell.textContent;

            const formData = new FormData();
            formData.append("_token", "{{ csrf_token() }}");
            formData.append("_method", "PUT");
            formData.append("name", name);
            formData.append("date_of_birth", dob);
            formData.append("phone_number", phone);

            fetch('{{ route("users.update", ":id") }}'.replace(":id", id), {
                method: "POST",
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    nameCell.textContent = data.name;
                    dobCell.innerHTML = data.date_of_birth;
                    phoneCell.innerHTML = data.phone_number;
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        }
    });
});
