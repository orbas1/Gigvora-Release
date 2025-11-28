class ChatConversation {
  final int id;
  final String? participantName;
  final DateTime? lastMessageAt;
  final int? mutualCount;
  final bool attachmentsAllowed;
  final List<ChatMessage> messages;

  const ChatConversation({
    required this.id,
    this.participantName,
    this.lastMessageAt,
    this.mutualCount,
    this.attachmentsAllowed = false,
    this.messages = const [],
  });

  factory ChatConversation.fromJson(Map<String, dynamic> json) {
    return ChatConversation(
      id: json['conversation_id'] as int? ?? json['id'] as int,
      participantName: json['participant'] as String?,
      mutualCount: json['mutual_count'] as int?,
      lastMessageAt: json['last_message_at'] != null
          ? DateTime.tryParse(json['last_message_at'].toString())
          : null,
      attachmentsAllowed: json['attachments_allowed'] as bool? ?? false,
      messages: (json['messages'] as List? ?? [])
          .map((e) => ChatMessage.fromJson(e as Map<String, dynamic>))
          .toList(),
    );
  }

  Map<String, dynamic> toJson() => {
        'conversation_id': id,
        if (participantName != null) 'participant': participantName,
        if (mutualCount != null) 'mutual_count': mutualCount,
        'attachments_allowed': attachmentsAllowed,
        if (lastMessageAt != null) 'last_message_at': lastMessageAt!.toIso8601String(),
        'messages': messages.map((e) => e.toJson()).toList(),
      };
}

class ChatMessage {
  final int id;
  final int senderId;
  final String content;
  final DateTime? sentAt;

  const ChatMessage({
    required this.id,
    required this.senderId,
    required this.content,
    this.sentAt,
  });

  factory ChatMessage.fromJson(Map<String, dynamic> json) {
    return ChatMessage(
      id: json['id'] as int,
      senderId: json['sender_id'] as int? ?? json['senderId'] as int,
      content: json['content'] as String? ?? json['message'] as String? ?? '',
      sentAt: json['sent_at'] != null
          ? DateTime.tryParse(json['sent_at'].toString())
          : null,
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'sender_id': senderId,
        'content': content,
        'sent_at': sentAt?.toIso8601String(),
      };
}

class ChatSettings {
  final bool readReceipts;
  final bool typingIndicators;
  final bool allowRequests;

  const ChatSettings({
    this.readReceipts = true,
    this.typingIndicators = true,
    this.allowRequests = true,
  });

  factory ChatSettings.fromJson(Map<String, dynamic> json) {
    return ChatSettings(
      readReceipts: json['read_receipts'] as bool? ?? json['readReceipts'] as bool? ?? true,
      typingIndicators:
          json['typing_indicators'] as bool? ?? json['typingIndicators'] as bool? ?? true,
      allowRequests: json['allow_requests'] as bool? ?? json['allowRequests'] as bool? ?? true,
    );
  }

  Map<String, dynamic> toJson() => {
        'read_receipts': readReceipts,
        'typing_indicators': typingIndicators,
        'allow_requests': allowRequests,
      };
}
