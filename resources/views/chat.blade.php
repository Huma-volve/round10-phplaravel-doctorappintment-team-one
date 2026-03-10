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

        .patient-item.unread {
            background-color: #fff3f3;
            /* لون فاتح يشير لوجود رسالة جديدة */
            transition: background 0.3s;
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
        // تمرير معرف المستخدم من Laravel
        window.currentUserId = {{ $currentUserId ?? 'null' }};

        document.addEventListener('DOMContentLoaded', function() {

            // عناصر DOM
            const sendBtn = document.getElementById('sendBtn');
            const messageInput = document.getElementById('messageInput');
            const chatMessages = document.getElementById('chatMessages');
            const conversationsList = document.getElementById('conversationsList');
            const headerName = document.getElementById('headerName');
            const headerAvatar = document.getElementById('headerAvatar');

            // الإعدادات العامة
            const currentUserId = window.currentUserId ?? null;
            const apiBaseUrl = '/api/v1/conversations';
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            const fetchHeaders = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            };

            // المتغيرات
            let currentConversationId = null;
            let pollingInterval = null;
            let lastMessageId = null;
            let echoChannel = null;
            const renderedMessageIds = new Set(); // تتبع الرسائل المعروضة لتجنب التكرار

            // وظائف مساعدة
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

            function renderMessage(msg) {
                // تجنب تكرار الرسائل
                if (renderedMessageIds.has(msg.id)) {
                    return;
                }
                renderedMessageIds.add(msg.id);

                const isMine = Number(msg.sender_user_id) === Number(currentUserId);
                const className = isMine ? 'my-message' : 'their-message';
                const time = new Date(msg.sent_at_utc).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                const html = `
                <div class="message ${className} ${!isMine && !msg.read_at_utc ? 'unread' : 'read'}" data-id="${msg.id}">
                    ${msg.body ?? ''}
                    <span class="message-time">${time}</span>
                </div>
            `;
                chatMessages.insertAdjacentHTML('beforeend', html);
            }

            async function fetchConversations() {
                conversationsList.innerHTML = '<li class="loading">Loading conversations...</li>';

                try {
                    const res = await fetch(apiBaseUrl, {
                        headers: fetchHeaders,
                        credentials: 'same-origin'
                    });
                    if (!res.ok) throw new Error('Failed to fetch conversations');
                    const conversations = await res.json();

                    conversationsList.innerHTML = '';
                    if (!conversations.length) {
                        conversationsList.innerHTML = '<li class="loading">No conversations</li>';
                        return;
                    }

                    conversations.forEach(conv => {
                        const li = document.createElement('li');
                        li.className = 'patient-item';
                        li.dataset.id = conv.id;

                        // إنشاء عنصر عداد الرسائل غير المقروءة
                        let unreadBadge = '';
                        if (conv.unread_count > 0) {
                            unreadBadge =
                                `<span class="unread-badge" style="background: #dc3545; color: white; border-radius: 50%; padding: 2px 6px; font-size: 11px; margin-right: 5px;">${conv.unread_count}</span>`;
                        }

                        li.innerHTML = `
                        <div class="avatar">${getInitial(conv.user_name)}</div>
                        <div style="flex:1">
                            <strong>${conv.user_name}</strong><br>
                            <small>${conv.last_message ?? ''}</small>
                        </div>
                        ${unreadBadge}
                        <small>${formatTime(conv.last_message_at)}</small>
                    `;
                        li.addEventListener('click', () => openConversation(conv.id, conv.user_name));
                        conversationsList.appendChild(li);
                    });

                    // فتح أول محادثة افتراضيًا
                    // openConversation(conversations[0].id, conversations[0].user_name);

                } catch (error) {
                    console.error(error);
                    conversationsList.innerHTML = '<li class="loading">Error loading conversations</li>';
                }
            }

            function setActiveConversation(id) {
                document.querySelectorAll('.patient-item').forEach(item => item.classList.remove('active'));
                const active = document.querySelector(`[data-id="${id}"]`);
                if (active) active.classList.add('active');
            }

            async function fetchMessages(conversationId) {
                chatMessages.innerHTML = '<div class="loading">Loading messages...</div>';

                // مسح مجموعة الرسائل المعروضة عند فتح محادثة جديدة
                renderedMessageIds.clear();

                try {
                    const res = await fetch(`${apiBaseUrl}/${conversationId}/messages`, {
                        headers: fetchHeaders,
                        credentials: 'same-origin'
                    });
                    if (!res.ok) throw new Error('Failed to fetch messages');
                    const data = await res.json();
                    const messages = data.data ?? [];

                    chatMessages.innerHTML = '';
                    if (!messages.length) {
                        chatMessages.innerHTML = '<div class="loading">No messages yet</div>';
                        return;
                    }

                    messages.reverse().forEach(renderMessage);
                    lastMessageId = messages[messages.length - 1]?.id ?? null;
                    scrollToBottom();
                } catch (error) {
                    console.error(error);
                    chatMessages.innerHTML = '<div class="loading">Error loading messages</div>';
                }
            }

            async function markConversationAsRead(conversationId) {
                try {
                    await fetch(`${apiBaseUrl}/${conversationId}/read`, {
                        method: 'POST',
                        headers: fetchHeaders,
                        credentials: 'same-origin'
                    });

                    // إزالة حالة unread من الرسائل في المحادثة
                    document.querySelectorAll('.message.unread').forEach(msg => msg.classList.replace('unread',
                        'read'));

                    // إزالة اللون الأحمر والعداد من القائمة الجانبية
                    updateUnreadCountInList(conversationId, 0);
                } catch (error) {
                    console.error('Failed to mark conversation as read:', error);
                }
            }
            // وظيفة لتحديث عداد الرسائل غير المقروءة في القائمة الجانبية
            function updateUnreadCountInList(conversationId, count) {
                const item = document.querySelector(`.patient-item[data-id="${conversationId}"]`);
                if (!item) return;

                let badge = item.querySelector('.unread-badge');

                if (count > 0) {
                    if (!badge) {
                        badge = document.createElement('span');
                        badge.className = 'unread-badge';
                        badge.style.cssText =
                            'background: #dc3545; color: white; border-radius: 50%; padding: 2px 6px; font-size: 11px; margin-right: 5px;';
                        item.appendChild(badge);
                    }
                    badge.textContent = count;
                    // تلوين المحادثة نفسها
                    item.classList.add('unread');
                } else {
                    if (badge) badge.remove();
                    // إزالة اللون عند عدم وجود رسائل جديدة
                    item.classList.remove('unread');
                }
            }

            // وظيفة لتحديث آخر رسالة في القائمة الجانبية
            function updateConversationInList(conversationId, lastMessage, time) {
                const item = document.querySelector(`.patient-item[data-id="${conversationId}"]`);
                if (!item) return;

                const messageEl = item.querySelector('small');
                if (messageEl) {
                    messageEl.textContent = lastMessage;
                }

                const timeEl = item.querySelectorAll('small');
                if (timeEl.length > 1) {
                    timeEl[1].textContent = formatTime(time);
                }
            }

            // وظيفة لإظهار إشعار برسالة جديدة
            function showNotification(userName, message) {
                // إشعار في عنوان الصفحة
                const originalTitle = document.title;
                document.title = `💬 ${userName}: ${message.substring(0, 30)}...`;

                // إعادة عنوان الصفحة بعد 3 ثوانٍ
                setTimeout(() => {
                    document.title = originalTitle;
                }, 3000);
            }

            function startPolling(conversationId) {
                currentConversationId = conversationId;
                lastMessageId = null;

                if (pollingInterval) clearInterval(pollingInterval);
                pollingInterval = setInterval(async () => {
                    if (!currentConversationId) return;

                    try {
                        const res = await fetch(`${apiBaseUrl}/${currentConversationId}/messages`, {
                            headers: fetchHeaders,
                            credentials: 'same-origin'
                        });
                        if (!res.ok) throw new Error('Failed to poll messages');
                        const data = await res.json();
                        const messages = data.data ?? [];

                        messages.reverse().forEach(msg => {
                            if (msg.id > (lastMessageId ?? 0)) {
                                renderMessage(msg);
                                lastMessageId = msg.id;
                            }
                        });
                        scrollToBottom();
                    } catch (error) {
                        console.error('Polling error:', error);
                    }
                }, 2000);
            }

            async function openConversation(conversationId, userName) {
                // ترك القناة السابقة
                if (echoChannel) {
                    echoChannel.stopListening('.message.sent');
                    echoChannel = null;
                }

                currentConversationId = conversationId;
                setActiveConversation(conversationId);
                headerName.textContent = userName ?? 'Unknown';
                headerAvatar.textContent = getInitial(userName);
                messageInput.disabled = false;
                sendBtn.disabled = false;

                await fetchMessages(conversationId);
                await markConversationAsRead(conversationId);
                startPolling(conversationId);

                // الاشتراك في Echo للمحادثة الحالية
                echoChannel = window.Echo.private(`conversation.${conversationId}`)
                    .listen('.message.sent', (e) => {
                        if (Number(e.sender_id) !== Number(currentUserId)) {
                            renderMessage(e);
                            scrollToBottom();
                            // تحديث آخر رسالة في القائمة
                            updateConversationInList(conversationId, e.body, e.sent_at_utc);
                        }
                    });
            }

            async function sendMessage() {
                const text = messageInput.value.trim();
                if (!text || !currentConversationId) return;

                sendBtn.disabled = true; // منع إرسال أكثر من مرة
                messageInput.value = '';

                try {
                    const res = await fetch(`${apiBaseUrl}/${currentConversationId}/messages`, {
                        method: 'POST',
                        headers: fetchHeaders,
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            body: text
                        })
                    });
                    if (!res.ok) throw new Error('Failed to send message');
                    const data = await res.json();
                    renderMessage(data.data ?? data);
                    scrollToBottom();
                } catch (error) {
                    console.error(error);
                    alert('Failed to send message');
                } finally {
                    sendBtn.disabled = false;
                }
            }

            // أحداث
            sendBtn.addEventListener('click', sendMessage);
            messageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') sendMessage();
            });

            // بدء التطبيق
            fetchConversations();

            // تحديث قائمة المحادثات كل 10 ثوانٍ للكشف عن رسائل جديدة من محادثات أخرى
            setInterval(() => {
                fetchConversations();
            }, 10000);
        });
    </script>
@endsection
