@extends('master')

@section('title', 'Chat')

@section('content')

    <!-- تأكد من وجود توكن الحماية الخاص بـ Laravel في الـ head في ملف master -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS Styles -->
    <style>
        :root {
            --primary-color: #007bff;
            --bg-color: #f4f7f6;
            --chat-bg: #e5ddd5;
            --my-msg-bg: #dcf8c6;
            --their-msg-bg: #ffffff;
            --text-dark: #333;
            --border-color: #ddd;
        }

        .chat-wrapper {
            display: flex;
            height: 75vh;
            min-height: 500px;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin: 20px 0;
        }

        /* Sidebar (Patients List) */
        .chat-sidebar {
            width: 300px;
            background: var(--bg-color);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 20px;
            background: #fff;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar-header h3 {
            margin: 0;
            font-size: 18px;
            color: var(--text-dark);
        }

        .patient-list {
            list-style: none;
            padding: 0;
            margin: 0;
            overflow-y: auto;
        }

        .patient-item {
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background 0.3s;
        }

        .patient-item:hover,
        .patient-item.active {
            background: #e9ecef;
        }

        .avatar {
            width: 40px;
            height: 40px;
            background: #ccc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        /* Main Chat Area */
        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: var(--chat-bg);
        }

        .chat-header {
            padding: 20px;
            background: #fff;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .chat-header h4 {
            margin: 0;
            color: var(--text-dark);
        }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* Message Bubbles */
        .message {
            max-width: 60%;
            padding: 10px 15px;
            border-radius: 8px;
            position: relative;
            font-size: 15px;
            line-height: 1.4;
        }

        /* الرسائل من الطبيب */
        .message.their-message {
            background: var(--their-msg-bg);
            margin-right: auto;
            /* تحديد الجهة اليسرى */
            border-bottom-left-radius: 0;
        }

        /* الرسائل من المريض */
        .message.my-message {
            background: var(--my-msg-bg);
            margin-left: auto;
            /* تحديد الجهة اليمنى */
            border-bottom-right-radius: 0;
        }

        .message-time {
            font-size: 11px;
            color: #777;
            display: block;
            margin-top: 5px;
            text-align: right;
        }

        /* الرسائل الغير مقروءة */
        .message.unread {
            background-color: #f8d7da;
            /* لون خلفية يشير إلى الرسائل الغير مقروءة */
            border-left: 4px solid #dc3545;
            /* حافة حمراء على الجانب للإشارة إلى أن الرسالة غير مقروءة */
        }

        /* الرسائل المقروءة */
        .message.read {
            background-color: var(--my-msg-bg);
            border-left: none;
        }

        /* عند الضغط على الرسالة لتغيير لونها */
        .message:active {
            background-color: #e2e6ea;
            /* تغيير الخلفية عند الضغط */
        }

        /* Input Area */
        .chat-footer {
            padding: 15px;
            background: #f0f0f0;
            display: flex;
            gap: 10px;
        }

        .chat-footer input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 20px;
            outline: none;
            font-size: 15px;
        }

        .chat-footer button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0 20px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        .chat-footer button:hover {
            background: #0056b3;
        }

        .loading {
            text-align: center;
            color: #777;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>

    <!-- HTML Structure -->
    <div class="chat-wrapper" id="chatApp">

        <!-- Sidebar: Patients -->
        <aside class="chat-sidebar">
            <div class="sidebar-header">
                <h3>Conversations</h3>
            </div>
            <ul class="patient-list" id="conversationsList">
                <li class="loading">Loading conversations...</li>
            </ul>
        </aside>

        <!-- Chat Area -->
        <main class="chat-main">
            <header class="chat-header">
                <div class="avatar" id="headerAvatar" style="background:#28a745;">?</div>
                <div>
                    <h4 id="headerName">Select a conversation</h4>
                </div>
            </header>

            <div class="chat-messages" id="chatMessages">
                <div class="loading">Select a conversation to start messaging...</div>
            </div>

            <footer class="chat-footer">
                <input type="text" id="messageInput" placeholder="Type your message here..." disabled>
                <button id="sendBtn" disabled>Send</button>
            </footer>
        </main>

    </div>

    <script>
        // Pass user ID from Laravel to JavaScript
        window.currentUserId = {{ $currentUserId ?? 'null' }};

        document.addEventListener('DOMContentLoaded', function() {

            const currentUserId = window.currentUserId ?? null;
            let currentConversationId = null;

            const apiBaseUrl = '/api/v1/conversations';
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            const fetchHeaders = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            };

            const sendBtn = document.getElementById('sendBtn');
            const messageInput = document.getElementById('messageInput');
            const chatMessages = document.getElementById('chatMessages');
            const conversationsList = document.getElementById('conversationsList');
            const headerName = document.getElementById('headerName');
            const headerAvatar = document.getElementById('headerAvatar');

            function scrollToBottom() {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            function getInitial(name) {
                if (!name) return '?';
                return name.charAt(0).toUpperCase();
            }

            function formatTime(time) {
                if (!time) return '';
                const date = new Date(time);
                return date.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            function setActiveConversation(id) {
                document.querySelectorAll('.patient-item').forEach(item => {
                    item.classList.remove('active');
                });

                const active = document.querySelector(`[data-id="${id}"]`);
                if (active) active.classList.add('active');
            }

            async function fetchConversations() {

                conversationsList.innerHTML = '<li class="loading">Loading conversations...</li>';

                try {

                    const response = await fetch(apiBaseUrl, {
                        method: 'GET',
                        headers: fetchHeaders,
                        credentials: 'same-origin'
                    });

                    if (!response.ok) throw new Error('Failed to fetch conversations');

                    const conversations = await response.json();

                    conversationsList.innerHTML = '';

                    if (!conversations.length) {
                        conversationsList.innerHTML = '<li class="loading">No conversations</li>';
                        return;
                    }

                    conversations.forEach(conversation => {

                        const li = document.createElement('li');
                        li.className = 'patient-item';
                        li.dataset.id = conversation.id;

                        li.innerHTML = `
                    <div class="avatar">${getInitial(conversation.user_name)}</div>
                    <div style="flex:1">
                        <strong>${conversation.user_name}</strong><br>
                        <small>${conversation.last_message ?? ''}</small>
                    </div>
                    <small>${formatTime(conversation.last_message_at)}</small>
                `;

                        // عند الضغط على المحادثة، يتم تفعيل فتح المحادثة
                        li.addEventListener('click', () => {
                            openConversation(conversation.id, conversation.user_name);
                        });

                        conversationsList.appendChild(li);
                    });
                    openConversation(conversations[0].id, conversations[0].user_name);

                } catch (error) {

                    console.error(error);
                    conversationsList.innerHTML = '<li class="loading">Error loading conversations</li>';

                }
            }

            async function openConversation(conversationId, userName) {
                currentConversationId = conversationId;
                headerName.textContent = userName ?? 'Unknown';
                headerAvatar.textContent = getInitial(userName);

                setActiveConversation(conversationId);

                // تمكين حقل الإدخال وإظهار زر الإرسال
                messageInput.disabled = false;
                sendBtn.disabled = false;

                await fetchMessages(conversationId);
            }

            async function fetchMessages(conversationId) {
                chatMessages.innerHTML = '<div class="loading">Loading messages...</div>';

                try {
                    const response = await fetch(`${apiBaseUrl}/${conversationId}/messages`, {
                        method: 'GET',
                        headers: fetchHeaders,
                        credentials: 'same-origin'
                    });

                    if (!response.ok) throw new Error('Failed to fetch messages');

                    const data = await response.json();
                    const messages = data.data ?? [];

                    chatMessages.innerHTML = ''; // تفريغ الرسائل القديمة

                    if (!messages.length) {
                        chatMessages.innerHTML = '<div class="loading">No messages yet</div>';
                        return;
                    }

                    // عكس ترتيب الرسائل بحيث تكون الأحدث في الأعلى
                    messages.reverse().forEach(renderMessage); // عكس ترتيب الرسائل قبل عرضها

                    scrollToBottom();
                } catch (error) {
                    console.error(error);
                    chatMessages.innerHTML = '<div class="loading">Error loading messages</div>';
                }
            }

            function renderMessage(msg) {
                const isMine = Number(msg.sender_user_id) === Number(currentUserId); // التحقق من هوية المرسل
                const className = isMine ? 'my-message' : 'their-message'; // تخصيص الجهة بناءً على المرسل

                const time = new Date(msg.sent_at_utc).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                const html = `
        <div class="message ${className}">
            ${msg.body ?? ''}
            <span class="message-time">${time}</span>
        </div>
    `;

                chatMessages.insertAdjacentHTML('beforeend', html); // إضافة الرسالة إلى نافذة المحادثة
            }

            async function sendMessage() {

                const text = messageInput.value.trim();

                if (!text || !currentConversationId) return;

                messageInput.value = '';

                try {

                    const response = await fetch(`${apiBaseUrl}/${currentConversationId}/messages`, {
                        method: 'POST',
                        headers: fetchHeaders,
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            body: text
                        })
                    });

                    if (!response.ok) throw new Error('Failed to send message');

                    const res = await response.json();
                    const message = res.data ?? res;

                    renderMessage(message);
                    scrollToBottom();
                } catch (error) {

                    console.error(error);
                    alert('Failed to send message');

                }
            }

            sendBtn.addEventListener('click', sendMessage);

            messageInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') sendMessage();
            });

            fetchConversations();

        });

        // إضافة كلاس unread أو read عند تحميل الرسائل
        function renderMessage(msg) {
            const isUnread = msg.read_at_utc === null; // تحقق إذا كانت الرسالة غير مقروءة
            const className = isUnread ? 'message unread' : 'message read'; // تحديد اللون بناءً على حالة القراءة

            const time = new Date(msg.sent_at_utc).toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });

            const html = `
        <div class="${className}" data-id="${msg.id}">
            ${msg.body ?? ''}
            <span class="message-time">${time}</span>
        </div>
    `;

            chatMessages.insertAdjacentHTML('beforeend', html);

            // إضافة حدث عند الضغط على الرسالة
            document.querySelector(`[data-id="${msg.id}"]`).addEventListener('click', function() {
                markAsRead(msg.id);
            });
        }

        // وظيفة لتغيير الحالة إلى "مقروءة"
        async function markAsRead(messageId) {
            try {
                const response = await fetch(`/api/v1/messages/${messageId}/markAsRead`, {
                    method: 'PATCH',
                    headers: fetchHeaders,
                    credentials: 'same-origin',
                });

                if (!response.ok) throw new Error('Failed to mark as read');

                // عند النجاح، نقوم بتحديث الرسالة لتصبح مقروءة
                const messageElement = document.querySelector(`[data-id="${messageId}"]`);
                if (messageElement) {
                    messageElement.classList.remove('unread');
                    messageElement.classList.add('read');
                }
            } catch (error) {
                console.error(error);
            }
        }
    </script>
@endsection
