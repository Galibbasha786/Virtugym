@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Messages 💬</h1>
    
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="grid md:grid-cols-3 h-[600px]">
            <!-- Conversations List -->
            <div class="border-r bg-gray-50">
                <div class="p-4 border-b bg-white">
                    <h2 class="font-bold">Conversations</h2>
                </div>
                <div class="overflow-y-auto h-[540px]">
                    @php
                        use App\Models\Message;
                        use App\Models\User;
                        $uniqueUsers = [];
                        $convList = [];
                        if(isset($conversations)) {
                            foreach($conversations as $conv) {
                                $otherId = ($conv->sender_id == Auth::id()) ? $conv->receiver_id : $conv->sender_id;
                                if(!in_array($otherId, $uniqueUsers)) {
                                    $uniqueUsers[] = $otherId;
                                    $otherUser = User::find($otherId);
                                    if($otherUser) {
                                        $unread = Message::where('receiver_id', Auth::id())
                                            ->where('sender_id', $otherId)
                                            ->where('is_read', false)
                                            ->count();
                                        $convList[] = ['user' => $otherUser, 'last_message' => $conv->message, 'unread' => $unread];
                                    }
                                }
                            }
                        }
                    @endphp
                    
                    @if(count($convList) > 0)
                        @foreach($convList as $item)
                            <a href="{{ url('/chat/' . $item['user']->id) }}" 
                               class="block p-4 hover:bg-gray-100 border-b transition">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($item['user']->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold">{{ $item['user']->name }}</p>
                                        <p class="text-sm text-gray-500 truncate">{{ Str::limit($item['last_message'], 40) }}</p>
                                    </div>
                                    @if($item['unread'] > 0)
                                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $item['unread'] }}</span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="text-center py-8 text-gray-500">
                            No conversations yet
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Chat Area -->
            <div class="md:col-span-2 flex flex-col">
                @if(isset($selectedTrainer) && $selectedTrainer)
                    <div class="p-4 border-b bg-white">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($selectedTrainer->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold">{{ $selectedTrainer->name }}</p>
                                <p class="text-xs text-gray-500">{{ $selectedTrainer->specialization ?? 'Trainer' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div id="chatMessages" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50" style="height: 400px;">
                        <div class="text-center text-gray-500">Loading messages...</div>
                    </div>
                    
                    <div class="p-4 border-t bg-white">
                        <form id="chatForm" class="flex space-x-2" onsubmit="return false;">
                            @csrf
                            <input type="hidden" name="receiver_id" value="{{ $selectedTrainer->id }}" id="receiverId">
                            <input type="text" name="message" id="messageInput" 
                                   class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500"
                                   placeholder="Type your message..." autocomplete="off">
                            <button type="button" id="sendButton" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-2 rounded-lg hover:shadow-lg transition">
                                Send
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center justify-center h-full text-gray-500">
                        Select a conversation to start chatting
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(isset($selectedTrainer) && $selectedTrainer)
<script>
    const trainerId = '{{ $selectedTrainer->id }}';
    const currentUserId = '{{ Auth::id() }}';
    const chatMessagesDiv = document.getElementById('chatMessages');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function loadMessages() {
        fetch('/chat/messages/' + trainerId)
            .then(response => response.json())
            .then(messages => {
                if (!chatMessagesDiv) return;
                
                chatMessagesDiv.innerHTML = '';
                
                if (messages.length === 0) {
                    chatMessagesDiv.innerHTML = '<div class="text-center text-gray-500 py-8">No messages yet. Start the conversation!</div>';
                    return;
                }
                
                messages.forEach(msg => {
                    const isOwn = msg.sender_id == currentUserId;
                    const div = document.createElement('div');
                    div.className = 'flex ' + (isOwn ? 'justify-end' : 'justify-start') + ' mb-3';
                    
                    const date = new Date(msg.created_at);
                    const timeStr = date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    
                    div.innerHTML = `
                        <div class="max-w-[70%] ${isOwn ? 'bg-purple-600 text-white' : 'bg-white border'} rounded-lg p-3 shadow-sm">
                            <p class="text-sm break-words">${escapeHtml(msg.message)}</p>
                            <p class="text-xs ${isOwn ? 'text-purple-200' : 'text-gray-400'} mt-1">${timeStr}</p>
                        </div>
                    `;
                    chatMessagesDiv.appendChild(div);
                });
                chatMessagesDiv.scrollTop = chatMessagesDiv.scrollHeight;
            })
            .catch(error => console.error('Error loading messages:', error));
    }
    
    function sendMessage() {
        const message = messageInput.value.trim();
        const receiverId = document.getElementById('receiverId').value;
        
        if (!message) return;
        
        fetch('/chat/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                receiver_id: receiverId,
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageInput.value = '';
                loadMessages();
            } else {
                console.error('Failed to send:', data);
            }
        })
        .catch(error => console.error('Error sending message:', error));
    }
    
    // Event listeners
    if (sendButton) {
        sendButton.addEventListener('click', sendMessage);
    }
    
    if (messageInput) {
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendMessage();
            }
        });
    }
    
    // Load messages every 3 seconds
    loadMessages();
    const interval = setInterval(loadMessages, 3000);
    
    // Cleanup interval on page unload
    window.addEventListener('beforeunload', function() {
        clearInterval(interval);
    });
</script>
@endif
@endsection