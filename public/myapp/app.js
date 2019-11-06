if (!localStorage.getItem('auth-id')) {
    location.href = '/login.html';
}

axios.defaults.headers.common = {
    "auth-id": localStorage.getItem('auth-id')
};


axios.get('/api/me').then(function (response) {
    document.getElementById('links').style.display = 'initial';
    let user = response.data;
    if (!user.is_super_admin) {
        document.getElementById('users_link').style.display = 'none';
    }
}).catch(function (error) {
    if (error.response.status == 401) {
        window.location.href = '/guest.html';
    }
});

function logout() {
    localStorage.removeItem('auth-id');
    location.href = '/login.html';
}


