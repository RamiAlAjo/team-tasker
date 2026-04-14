// Simple client-side JavaScript for task management

class TaskManager {
    constructor() {
        this.currentUser = null;
        this.tasks = [];
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.checkAuthStatus();
    }

    setupEventListeners() {
        // Login form
        const loginForm = document.getElementById('login-form');
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleLogin();
            });
        }

        // Register form
        const registerForm = document.getElementById('register-form');
        if (registerForm) {
            registerForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleRegister();
            });
        }

        // Add task form
        const addTaskForm = document.getElementById('add-task-form');
        if (addTaskForm) {
            addTaskForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleAddTask(e);
            });
        }

        // Logout button
        const logoutBtn = document.getElementById('logout-btn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', () => this.handleLogout());
        }
    }

    checkAuthStatus() {
        const token = localStorage.getItem('token');
        if (token) {
            this.currentUser = { token };
            this.showDashboard();
        } else {
            this.showLogin();
        }
    }

    showPage(pageId) {
        document.querySelectorAll('.page').forEach(page => {
            page.classList.remove('active');
        });
        document.getElementById(pageId).classList.add('active');
    }

    showLogin() {
        this.showPage('login-page');
    }

    showRegister() {
        this.showPage('register-page');
    }

    showDashboard() {
        this.showPage('dashboard-page');
        this.loadTasks();
        this.updateStats();
    }

    async handleLogin() {
        const form = document.getElementById('login-form');
        const email = form.querySelector('input[type="email"]').value;
        const password = form.querySelector('input[type="password"]').value;

        try {
            // Simulate API call
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            });

            if (response.ok) {
                const data = await response.json();
                this.currentUser = { token: data.token };
                localStorage.setItem('token', data.token);
                this.showDashboard();
            } else {
                alert('Login failed');
            }
        } catch (error) {
            console.error('Login error:', error);
        }
    }

    async handleRegister() {
        const form = document.getElementById('register-form');
        const name = form.querySelectorAll('input')[0].value;
        const email = form.querySelectorAll('input')[1].value;
        const password = form.querySelectorAll('input')[2].value;
        const confirmPassword = form.querySelectorAll('input')[3].value;

        if (password !== confirmPassword) {
            alert('Passwords do not match');
            return;
        }

        try {
            // Simulate API call
            const response = await fetch('/api/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, email, password })
            });

            if (response.ok) {
                alert('Registration successful! Please login.');
                this.showLogin();
            } else {
                alert('Registration failed');
            }
        } catch (error) {
            console.error('Registration error:', error);
        }
    }

    async handleAddTask(e) {
        const form = e.target;
        const title = form.querySelector('input[type="text"]').value;
        const description = form.querySelector('textarea').value;

        try {
            const response = await fetch('/api/tasks', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${this.currentUser.token}`
                },
                body: JSON.stringify({ title, description })
            });

            if (response.ok) {
                form.reset();
                this.loadTasks();
                this.updateStats();
            } else {
                alert('Failed to add task');
            }
        } catch (error) {
            console.error('Add task error:', error);
        }
    }

    async loadTasks() {
        try {
            const response = await fetch('/api/tasks', {
                headers: { 'Authorization': `Bearer ${this.currentUser.token}` }
            });

            if (response.ok) {
                this.tasks = await response.json();
                this.renderTasks();
            }
        } catch (error) {
            console.error('Load tasks error:', error);
        }
    }

    renderTasks() {
        const container = document.getElementById('tasks-container');
        if (!container) return;

        container.innerHTML = this.tasks.map(task => `
            <div class="task-card">
                <h3>${task.title}</h3>
                <p>${task.description || 'No description'}</p>
                <span class="status ${task.status}">${task.status}</span>
            </div>
        `).join('');
    }

    updateStats() {
        const total = this.tasks.length;
        const completed = this.tasks.filter(t => t.status === 'done').length;
        const pending = total - completed;

        document.getElementById('total-tasks').textContent = total;
        document.getElementById('completed-tasks').textContent = completed;
        document.getElementById('pending-tasks').textContent = pending;
    }

    async handleLogout() {
        try {
            await fetch('/api/logout', {
                method: 'POST',
                headers: { 'Authorization': `Bearer ${this.currentUser.token}` }
            });

            localStorage.removeItem('token');
            this.currentUser = null;
            this.showLogin();
        } catch (error) {
            console.error('Logout error:', error);
        }
    }
}

// Initialize app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new TaskManager();
});

// Navigation helpers
function navigateTo(pageId) {
    document.querySelectorAll('.page').forEach(page => {
        page.classList.remove('active');
    });
    document.getElementById(pageId).classList.add('active');
}

// Form validation helpers
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePassword(password) {
    return password.length >= 6;
}

// Export for use in other scripts
window.TaskManager = TaskManager;
window.navigateTo = navigateTo;