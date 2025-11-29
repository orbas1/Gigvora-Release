import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../analytics/analytics_client.dart';
import '../models/models.dart';
import '../state/state.dart';
import 'common.dart';

class ChatListScreen extends StatefulWidget {
  final ChatState? state;
  final AnalyticsClient analytics;

  const ChatListScreen({super.key, required this.analytics, this.state});

  @override
  State<ChatListScreen> createState() => _ChatListScreenState();
}

class _ChatListScreenState extends State<ChatListScreen> {
  ChatState get state => widget.state ?? context.read<ChatState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('ChatList', {});
    state.loadConversations();
    state.loadRequests();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Messages'),
        actions: [
          IconButton(
            icon: const Icon(Icons.inbox),
            onPressed: () => Navigator.of(context).pushNamed('/chat/requests'),
          ),
        ],
      ),
      body: Consumer<ChatState>(
        builder: (context, s, _) {
          if (s.loading && s.conversations.isEmpty) {
            return const LoadingView();
          }
          if (s.error != null) return ErrorView(message: s.error!, onRetry: state.loadConversations);
          if (s.conversations.isEmpty) return const EmptyView(message: 'No conversations yet');
          return ListView(
            children: s.conversations
                .map(
                  (c) => ListTile(
                    leading: const CircleAvatar(child: Icon(Icons.chat_bubble_outline)),
                    title: Text(c.title ?? 'Conversation ${c.id}'),
                    subtitle: Text(c.lastMessage?.content ?? 'No messages'),
                    trailing: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        IconButton(
                          icon: const Icon(Icons.phone),
                          onPressed: () {},
                        ),
                        IconButton(
                          icon: const Icon(Icons.videocam),
                          onPressed: () {},
                        ),
                      ],
                    ),
                    onTap: () => Navigator.of(context)
                        .pushNamed('/chat/detail', arguments: c.id),
                  ),
                )
                .toList(),
          );
        },
      ),
    );
  }
}

class ChatDetailScreen extends StatefulWidget {
  final int conversationId;
  final ChatState? state;
  final AnalyticsClient analytics;

  const ChatDetailScreen({
    super.key,
    required this.conversationId,
    required this.analytics,
    this.state,
  });

  @override
  State<ChatDetailScreen> createState() => _ChatDetailScreenState();
}

class _ChatDetailScreenState extends State<ChatDetailScreen> {
  final TextEditingController _message = TextEditingController();
  ChatState get state => widget.state ?? context.read<ChatState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('ChatDetail', {'conversationId': widget.conversationId});
    state.openConversation(widget.conversationId);
  }

  @override
  void dispose() {
    _message.dispose();
    super.dispose();
  }

  Future<void> _clear() async {
    await state.clearConversation(widget.conversationId);
  }

  Future<void> _delete() async {
    await state.deleteConversation(widget.conversationId);
    if (mounted) Navigator.of(context).pop();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Conversation'),
        actions: [
          IconButton(icon: const Icon(Icons.cleaning_services), onPressed: _clear),
          IconButton(icon: const Icon(Icons.delete_outline), onPressed: _delete),
        ],
      ),
      body: Consumer<ChatState>(
        builder: (context, s, _) {
          if (s.loading && s.active == null) return const LoadingView();
          if (s.error != null) return ErrorView(message: s.error!, onRetry: () => state.openConversation(widget.conversationId));
          final conversation = s.active;
          if (conversation == null) return const EmptyView(message: 'Conversation not found');
          return Column(
            children: [
              Expanded(
                child: ListView(
                  padding: const EdgeInsets.all(12),
                  children: conversation.messages
                      .map(
                        (m) => Align(
                          alignment: m.isMine ? Alignment.centerRight : Alignment.centerLeft,
                          child: Container(
                            margin: const EdgeInsets.symmetric(vertical: 4),
                            padding: const EdgeInsets.all(10),
                            decoration: BoxDecoration(
                              color: m.isMine
                                  ? Theme.of(context).colorScheme.primary.withOpacity(0.1)
                                  : Theme.of(context).colorScheme.surfaceVariant,
                              borderRadius: BorderRadius.circular(12),
                            ),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(m.content),
                                if (m.attachments.isNotEmpty)
                                  Wrap(
                                    spacing: 6,
                                    children: m.attachments.map((a) => Chip(label: Text(a))).toList(),
                                  ),
                              ],
                            ),
                          ),
                        ),
                      )
                      .toList(),
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(12),
                child: Row(
                  children: [
                    IconButton(onPressed: () {}, icon: const Icon(Icons.emoji_emotions_outlined)),
                    IconButton(onPressed: () {}, icon: const Icon(Icons.gif_box_outlined)),
                    IconButton(onPressed: () {}, icon: const Icon(Icons.mic_none)),
                    IconButton(onPressed: () {}, icon: const Icon(Icons.attach_file)),
                    Expanded(
                      child: TextField(
                        controller: _message,
                        decoration: const InputDecoration(hintText: 'Type a message...'),
                        onSubmitted: (_) => _send(),
                      ),
                    ),
                    IconButton(onPressed: () {}, icon: const Icon(Icons.phone)),
                    IconButton(onPressed: () {}, icon: const Icon(Icons.videocam)),
                    IconButton(onPressed: _send, icon: const Icon(Icons.send)),
                  ],
                ),
              )
            ],
          );
        },
      ),
    );
  }

  Future<void> _send() async {
    if (_message.text.trim().isEmpty) return;
    final payload = {'message': _message.text.trim()};
    await state.sendMessage(widget.conversationId, payload);
    widget.analytics.trackAction('chat_message_sent', payload);
    _message.clear();
  }
}

class MessageRequestsScreen extends StatefulWidget {
  final ChatState? state;
  final AnalyticsClient analytics;

  const MessageRequestsScreen({super.key, required this.analytics, this.state});

  @override
  State<MessageRequestsScreen> createState() => _MessageRequestsScreenState();
}

class _MessageRequestsScreenState extends State<MessageRequestsScreen> {
  ChatState get state => widget.state ?? context.read<ChatState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('MessageRequests', {});
    state.loadRequests();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Message Requests')),
      body: Consumer<ChatState>(
        builder: (context, s, _) {
          if (s.loading && s.requests.isEmpty) return const LoadingView();
          if (s.error != null) return ErrorView(message: s.error!, onRetry: state.loadRequests);
          if (s.requests.isEmpty) return const EmptyView(message: 'No pending requests');
          return ListView(
            children: s.requests
                .map(
                  (r) => ListTile(
                    leading: const CircleAvatar(child: Icon(Icons.mail_outline)),
                    title: Text(r.title ?? 'Request ${r.id}'),
                    subtitle: Text(r.lastMessage?.content ?? ''),
                    trailing: Wrap(
                      spacing: 6,
                      children: [
                        OutlinedButton(
                          onPressed: () {
                            state.acceptRequest(r.id);
                            widget.analytics.trackAction('message_request_accepted', {'requestId': r.id});
                          },
                          child: const Text('Accept'),
                        ),
                        TextButton(
                          onPressed: () {
                            state.declineRequest(r.id);
                            widget.analytics.trackAction('message_request_declined', {'requestId': r.id});
                          },
                          child: const Text('Decline'),
                        ),
                      ],
                    ),
                  ),
                )
                .toList(),
          );
        },
      ),
    );
  }
}
