if (!localStorage.getItem('auth-id')) {
    location.href = '/login.html';
}

axios.defaults.headers.common = {
    "auth-id": localStorage.getItem('auth-id')
};


axios.get('/api/me').then(function (response) {
    document.getElementById('links').style.display = 'block';
    document.getElementById('app').style.display = 'block';
    let user = response.data;
    if (!user.is_super_admin) {
        document.getElementById('users_link').style.display = 'none';
    }

}).catch(function (error) {
    // if (error.response.status == 404) {
        localStorage.removeItem('auth-id');
        window.location.href = '/login.html';
    // }
});

function logout() {
    localStorage.removeItem('auth-id');
    location.href = '/login.html';
}


