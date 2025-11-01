/**
 * E-Learning Platform - Main JavaScript
 * Features: Dark/Light Mode, AJAX, Animations
 */

// Theme Management
const themeToggle = document.getElementById('theme-toggle');
const body = document.body;

// Load saved theme
const savedTheme = localStorage.getItem('theme') || 'light';
body.setAttribute('data-theme', savedTheme);
updateThemeIcon(savedTheme);

// Theme toggle event
if (themeToggle) {
    themeToggle.addEventListener('click', () => {
        const currentTheme = body.getAttribute('data-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        
        body.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
        
        // Show notification
        showNotification('?? chuy?n sang ch? ?? ' + (newTheme === 'dark' ? 't?i' : 's?ng'), 'info');
    });
}

function updateThemeIcon(theme) {
    if (themeToggle) {
        const icon = themeToggle.querySelector('i');
        if (icon) {
            icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }
    }
}

// Notification System
function showNotification(message, type = 'info') {
    const types = {
        'success': 'success',
        'error': 'error',
        'warning': 'warning',
        'info': 'info'
    };
    
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: types[type] || 'info',
        title: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
}

// AJAX Helper
async function fetchAPI(url, method = 'GET', data = null) {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };
    
    if (data && method !== 'GET') {
        options.body = JSON.stringify(data);
    }
    
    try {
        const response = await fetch(url, options);
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('API Error:', error);
        showNotification('C? l?i x?y ra. Vui l?ng th? l?i.', 'error');
        return null;
    }
}

// Form Validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}

// Complete Lesson
async function completeLesson(lessonId) {
    const result = await fetchAPI(BASE_URL + 'course/completeLesson/' + lessonId, 'POST');
    
    if (result && result.success) {
        showNotification(result.message, 'success');
        
        if (result.xp_earned) {
            showNotification('B?n nh?n ???c ' + result.xp_earned + ' XP!', 'success');
        }
        
        if (result.course_completed) {
            Swal.fire({
                icon: 'success',
                title: 'Ch?c m?ng!',
                text: 'B?n ?? ho?n th?nh kh?a h?c. Ch?ng ch? ?? ???c t?o!',
                confirmButtonText: 'Xem ch?ng ch?'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = BASE_URL + 'auth/profile';
                }
            });
        }
        
        // Reload page to update progress
        setTimeout(() => {
            location.reload();
        }, 1500);
    }
}

// Quiz Timer
let quizTimer = null;
let quizTimeLeft = 0;

function startQuizTimer(duration) {
    quizTimeLeft = duration * 60; // Convert to seconds
    
    quizTimer = setInterval(() => {
        quizTimeLeft--;
        
        const minutes = Math.floor(quizTimeLeft / 60);
        const seconds = quizTimeLeft % 60;
        
        const timerDisplay = document.getElementById('quiz-timer');
        if (timerDisplay) {
            timerDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        }
        
        if (quizTimeLeft <= 60) {
            timerDisplay.classList.add('text-danger');
        }
        
        if (quizTimeLeft <= 0) {
            clearInterval(quizTimer);
            submitQuiz();
        }
    }, 1000);
}

// Submit Quiz
async function submitQuiz() {
    const form = document.getElementById('quiz-form');
    if (!form) return;
    
    const formData = new FormData(form);
    const answers = {};
    
    for (let [key, value] of formData.entries()) {
        if (key.startsWith('answers[')) {
            const questionId = key.match(/\d+/)[0];
            answers[questionId] = value;
        }
    }
    
    // Show loading
    Swal.fire({
        title: '?ang ch?m b?i...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    const quizId = form.dataset.quizId;
    const result = await fetchAPI(BASE_URL + 'quiz/submit/' + quizId, 'POST', { answers });
    
    if (result && result.success) {
        Swal.close();
        window.location.href = result.redirect;
    }
}

// Chat System
let lastMessageId = 0;
let chatPollInterval = null;

function initChat(contactId) {
    // Poll for new messages every 3 seconds
    chatPollInterval = setInterval(() => {
        fetchNewMessages(contactId);
    }, 3000);
}

async function fetchNewMessages(contactId) {
    const result = await fetchAPI(
        `${BASE_URL}chat/getNew?contact_id=${contactId}&last_id=${lastMessageId}`
    );
    
    if (result && result.success && result.messages.length > 0) {
        const chatMessages = document.getElementById('chat-messages');
        
        result.messages.forEach(msg => {
            appendMessage(msg);
            lastMessageId = msg.id;
        });
        
        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
}

function appendMessage(message) {
    const chatMessages = document.getElementById('chat-messages');
    const isOwn = message.sender_id == currentUserId;
    
    const messageDiv = document.createElement('div');
    messageDiv.className = 'chat-message' + (isOwn ? ' own' : '');
    messageDiv.innerHTML = `
        <img src="${BASE_URL}public/uploads/avatars/${message.sender_avatar}" 
             class="avatar" alt="Avatar"
             onerror="this.src='${BASE_URL}public/images/default-avatar.png'">
        <div class="bubble">
            <p class="mb-0">${escapeHtml(message.message)}</p>
            <small class="text-muted">${formatTime(message.created_at)}</small>
        </div>
    `;
    
    chatMessages.appendChild(messageDiv);
}

async function sendMessage(contactId) {
    const messageInput = document.getElementById('message-input');
    const message = messageInput.value.trim();
    
    if (!message) return;
    
    const result = await fetchAPI(BASE_URL + 'chat/send', 'POST', {
        receiver_id: contactId,
        message: message
    });
    
    if (result && result.success) {
        messageInput.value = '';
        
        // Append message immediately
        appendMessage({
            id: result.message_id,
            sender_id: currentUserId,
            sender_avatar: currentUserAvatar,
            message: message,
            created_at: result.created_at
        });
        
        lastMessageId = result.message_id;
        
        // Scroll to bottom
        const chatMessages = document.getElementById('chat-messages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
}

// Utility Functions
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

function formatTime(datetime) {
    const date = new Date(datetime);
    const now = new Date();
    const diff = now - date;
    
    if (diff < 60000) return 'V?a xong';
    if (diff < 3600000) return Math.floor(diff / 60000) + ' ph?t tr??c';
    if (diff < 86400000) return Math.floor(diff / 3600000) + ' gi? tr??c';
    
    return date.toLocaleDateString('vi-VN');
}

// Image Preview
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Confirm Dialog
function confirmAction(message, callback) {
    Swal.fire({
        title: 'X?c nh?n',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '??ng ?',
        cancelButtonText: 'H?y'
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}

// Copy to Clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showNotification('?? sao ch?p!', 'success');
    });
}

// Smooth Scroll to Element
function scrollToElement(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// Back to Top Button
const backToTopButton = document.createElement('button');
backToTopButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
backToTopButton.className = 'btn btn-primary position-fixed bottom-0 end-0 m-4';
backToTopButton.style.display = 'none';
backToTopButton.style.zIndex = '9999';
backToTopButton.onclick = () => window.scrollTo({ top: 0, behavior: 'smooth' });
document.body.appendChild(backToTopButton);

window.addEventListener('scroll', () => {
    if (window.pageYOffset > 300) {
        backToTopButton.style.display = 'block';
    } else {
        backToTopButton.style.display = 'none';
    }
});

// Auto-dismiss alerts
document.addEventListener('DOMContentLoaded', () => {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const closeButton = alert.querySelector('.btn-close');
            if (closeButton) closeButton.click();
        }, 5000);
    });
});

// Initialize tooltips
document.addEventListener('DOMContentLoaded', () => {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
});

// Console Welcome Message
console.log('%c?? E-Learning Platform v1.0.0', 'color: #667eea; font-size: 20px; font-weight: bold;');
console.log('%cBuilt with ?? for education', 'color: #764ba2; font-size: 14px;');
