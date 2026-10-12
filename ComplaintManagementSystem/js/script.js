function validateRegister() {
    const name = document.getElementById("name").value.trim();
    const password = document.getElementById("password").value;

    if (name.length < 3) {
        alert("Name must contain at least 3 characters.");
        return false;
    }

    if (password.length < 6) {
        alert("Password must contain at least 6 characters.");
        return false;
    }

    return true;
}

function validateComplaint() {
    const title = document.getElementById("title").value.trim();
    const description = document.getElementById("description").value.trim();

    if (title.length < 5) {
        alert("Complaint title must contain at least 5 characters.");
        return false;
    }

    if (description.length < 10) {
        alert("Please provide a more detailed complaint description.");
        return false;
    }

    return true;
}
