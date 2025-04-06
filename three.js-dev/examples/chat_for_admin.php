<style>
.chat-popup {
    position: absolute; */
    height: 100%;
    width: 99%;
    border-radius: 10px;
    height: 100%;
    z-index: 1000;
    border-radius: 4px;
}
#chat-user-name {color:white;}
.sent{
   
    display: flex;
    width: 100%;
    margin-bottom: 10px;
    flex-direction: row;
    justify-content: flex-end;}

.sent p{
    color:black;
   }
.message {
    color:black;
    width: 100%; 
    margin-bottom: 10px;
}

.text p {
    word-wrap: break-word; 
    white-space: pre-wrap;
}
.message {
    display: flex;
    align-items: flex-end;
    margin-bottom: 15px;
}

.message .text {
    background-color:rgba(224, 224, 224, 0.67);
    padding: 10px;
    border-radius: 10px;
    max-width: 80%;
    position: relative;
}

.message.sent .text {
    background-color: #4a90e2;
    color: black;
}


.chat-popup .close-btn {
    align-self: flex-end;
    margin: 5px;
    cursor: pointer;
    font-size: 20px;
    color: #fff;
    background: none;
    border: none; 
}


.user-list {
    width: 30%; 

    color: #fff;
    overflow-y: auto;
    padding: 10px; 
 
    overflow-x: hidden; 
}
.user-list ul {
    margin-left: 18px;
}
.user-list h2 {
    text-align: center;
    margin-bottom: 10px; 
}

.user-list ul {
    list-style: none;
    padding: 0; 
}



.user-list ul li:hover {
    background-color: #4a4f57;
}


.chat-container {
    width: 100%;
    display: flex;
    flex-direction: column;
}


.chat-header {
    background-color: #202225;
    padding: 10px;
    color: #fff;
    text-align: center;
    border-bottom: 1px solid #3a3d41;
}


.chat-window {
    flex-grow: 1;
    padding: 10px;
    overflow-y: auto;
    
    overflow-wrap: break-word; 
    word-wrap: break-word; 
    width: 100%; 
    max-width: 100%;
}


.chat-input {
    display: flex;
    padding: 10px;
    background-color: #202225;
    border-top: 1px solid #3a3d41;
}

.chat-input input {
    flex-grow: 1;
    padding: 10px; 
    border: none;
    border-radius: 5px;
    background-color: #3a3d41;
    color: #fff;
    outline: none;
}

.send-btn {
    background-color: #4a90e2;
    border: none;
    padding: 10px 15px;
    margin-left: 10px; 
    border-radius: 5px;
    color: #fff;
    cursor: pointer;
}

.send-btn:hover {
    background-color: #357abf;
}

.close-btn {
    background: transparent; 
    color: #fff; 
    border: none; 
    font-size: 24px; 
    cursor: pointer; 
    position: absolute; 
    top: 5px; 
    right: 5px; 
    padding: 0; 
    line-height: 1;
}

.close-btn:hover {
    color: #ff4d4d; 
}

.chat-icon {
    
	border: 0px solid rgb(114, 79, 79);
	background: rgb(0, 110, 255);

    position: fixed;
    bottom: 20px;
    right: 20px;

    border-radius: 50%;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: 0.5s;
}
.chat-icon:hover {
    
    transform: translate(0,-3px);
}
.chat-icon img {
    width: 30px;
    height: 30px;
}


@media (max-width: 768px) {
    .user-list {
        width: 100%;
        font-size:5vw;       
        height: 100%;
    }

    .chat-container {
        width: 100%;
        height: 100%; 
    }

    .chat-popup {
        width: 96%;
        height: 100%;
    }

    .send-btn {
        padding: 8px 12px;
    }

    .chat-icon {
        width: 50px; 
        height: 50px;
    }

    .chat-icon img {
        width: 25px; 
        height: 25px;
    }
}


@media (max-width: 480px) {
    .chat-popup {
        margin-left: 26px;
        width: 89%;
        max-width: none;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: absolute;
 
    }

    .chat-container {
        height: 66.67%;
        display: flex;
        flex-direction: column;
    }

    .chat-header,
    .chat-input {
        padding: 5px;
    }

    .send-btn {
        padding: 6px 10px; 
    }

    .close-btn {
        font-size: 18px; 
    }
}
.delete-btn:hover {
            background-color: #d43f3f;
        }

.chat-container {
    width: 100%; 
    max-width: 100%; 
    height: 100%; 
    max-height: 100%; 
    overflow-y: auto;
    box-sizing: border-box; 
}

.chat-window {
    width: 100%; 
    max-width: 100%; 
    overflow-wrap: break-word; 
    word-wrap: break-word; 
}

.message {
    color:black;
    width: 100%;
    margin-bottom: 10px; 
}

.text p {
    word-wrap: break-word; 
    white-space: pre-wrap; 
}
.received p,span{
    color : black;
}
.user-list ul li {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px;
    background-color: #3a3d41;
    margin-bottom: 5px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    max-width: 100%;
    box-sizing: border-box;
    overflow: hidden;
    gap: 8px;
}

.user-name {
    flex-grow: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    min-width: 0; 
}

.delete-btn-chat {
    background-color: #ff4d4d;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    flex-shrink: 0;
}


.chat-placeholder {
    font-family: Arial, sans-serif;
    font-size: 16px;
    color: #555;
    text-align: center;
    width: 100%; 
}

    </style>
    <div class="chat-popup" id="chat-popup" style="display:none;">
    <div class="user-list">
        <p style="margin-left:24px;">Пользователи</p>
        <ul id="user-list">
        </ul>
    </div>
    <div class="chat-container">
    <div class="chat-header">
    Чат с пользователем: <span id="chat-user-name" data-user-id=""></span>
    </div>
        <div class="chat-window" id="chat-window">
        <span class="chat-placeholder">Выберите пользователя</span>
        </div>
        <div class="chat-input">
            <input type="text" id="message-input" placeholder="Введите сообщение...">
            <button class="send-btn" id="send-btn">Отправить</button>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const chatPopup = document.getElementById('chat-popup');
    chatPopup.style.display = 'flex'; 

    fetch('get_messages_for_admin.php')
        .then(response => response.json())
        .then(users => {
            const userList = document.getElementById('user-list');
            userList.innerHTML = ''; 
            if (users.length > 0) {
                const firstUser = users[0];
                loadChatForUser(firstUser.user_id, firstUser.name);
            }
            users.forEach(user => {
                const userItem = document.createElement('li');
                userItem.textContent = user.name;
                userItem.setAttribute('data-user-id', user.user_id);
                userItem.setAttribute('data-last-message', user.last_message);

                console.log('User ID: ' + user.user_id + ', Last message: ' + user.last_message);

                if (user.last_message === '0') {
                    userItem.style.backgroundColor = 'red';
                } else {
                    userItem.style.backgroundColor = '';
                }
                const deleteBtn = document.createElement('button');
                deleteBtn.textContent = 'x';
                deleteBtn.classList.add('delete-btn-chat');
                deleteBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    removeChatFromList(user.user_id);
                });

                userItem.appendChild(deleteBtn);

                userItem.addEventListener('click', () => {
                    markMessagesAsRead(user.user_id); 
                    loadChatForUser(user.user_id, user.name);
                    userItem.style.backgroundColor = ''; 
                });
                userList.appendChild(userItem);
            });
        })
        .catch(error => {
            console.error('Error fetching users:', error);
        });

    function markMessagesAsRead(userId) {
        fetch('mark_message_as_read.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'user_id': userId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status !== 'success') {
                console.error('Ошибка при отметке сообщений как прочитанных:', data.message);
            }
        })
        .catch(error => console.error('Ошибка:', error));
    }
    document.getElementById('send-btn').addEventListener('click', () => {
    const userId = document.getElementById('chat-user-name').dataset.userId;
    const message = document.getElementById('message-input').value;

    if (!userId || !message) {
        alert('Не выбран пользователь или сообщение пустое.');
        return;
    }

    fetch('send_message_from_admin.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            'user_id': userId,
            'message': message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('message-input').value = ''; 
            
            loadChatForUser(userId, document.getElementById('chat-user-name').textContent);
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Ошибка:', error));
    });

    function removeChatFromList(userId) {
        const userItem = document.querySelector(`li[data-user-id='${userId}']`);
        if (userItem) {
            userItem.remove();
            fetch('delete_chat.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ 'user_id': userId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        console.log(`Чат с ${userId} Удалено успешно.`);
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Ошибка удаления :', error));
        }
    }
    function loadChatForUser(userId, userName) {
        document.getElementById('chat-user-name').textContent = userName;
        document.getElementById('chat-user-name').dataset.userId = userId;
        fetch(`get_messages.php?user_id=${userId}`)
            .then(response => response.json())
            .then(messages => {
                const chatWindow = document.getElementById('chat-window');
                chatWindow.innerHTML = '';
                messages.sort((a, b) => new Date(a.timestamp) - new Date(b.timestamp));
                messages.forEach(message => {
                    const messageDiv = document.createElement('div');
                    messageDiv.classList.add('message');

                    if (message.sender_role == userId) {
                        messageDiv.classList.add('sent');
                    } else {
                        messageDiv.classList.add('received');
                    }
                    const textDiv = document.createElement('div');
                    textDiv.classList.add('text');
                    textDiv.innerHTML = `<p>${message.message}</p><span class="time">${message.timestamp}</span>`;
                    const linkedMessage = message.message.replace(
                    /(https?:\/\/[^\s]+)/g, 
                    '<a href="$1" target="_blank">$1</a>' 
                );
                textDiv.innerHTML = `<p>${linkedMessage}</p><span class="time">${message.timestamp}</span>`;
                    messageDiv.appendChild(textDiv);
                    chatWindow.appendChild(messageDiv);
                });

                chatWindow.scrollTop = chatWindow.scrollHeight;
            });
    }
});
</script>
