const apiUrl = "http://localhost/api_crud/api";

// FUNCION PARA OBTENER USUARIOS
async function fetchUsers() {
    const response = await fetch(`${apiUrl}/users`);
    const users = await response.json();

    const tbody = document.querySelector("#usersTable tbody");
    tbody.innerHTML = ""; 
    users.forEach(user => {
        const row = `
            <tr>
                <td>${user.id}</td>
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>${user.age}</td>
                <td>
                    <button onclick="showUser(${user.id})" class="btn btn-info btn-sm">Mostrar</button>
                    <button onclick="deleteUser(${user.id})" class="btn btn-danger btn-sm">Eliminar</button>
                    <button onclick="editUser(${user.id})" class="btn btn-warning btn-sm">Editar</button>
                </td>
            </tr>`;
        tbody.insertAdjacentHTML('beforeend', row);
    });
}

// CREACION DE USUARIOS
document.getElementById("userForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const userData = {
        name: document.getElementById("name").value,
        email: document.getElementById("email").value,
        age: document.getElementById("age").value
    };

    await fetch(`${apiUrl}/user`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(userData)
    });
    
    fetchUsers();
});