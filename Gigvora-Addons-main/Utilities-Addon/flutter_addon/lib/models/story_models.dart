import 'music_models.dart';

class Story {
  final int id;
  final int userId;
  final String mediaUrl;
  final StoryMetadata metadata;
  final List<StoryViewer> viewers;
  final DateTime? createdAt;

  const Story({
    required this.id,
    required this.userId,
    required this.mediaUrl,
    required this.metadata,
    this.viewers = const [],
    this.createdAt,
  });

  factory Story.fromJson(Map<String, dynamic> json) {
    return Story(
      id: json['id'] as int,
      userId: json['user_id'] as int? ?? json['userId'] as int,
      mediaUrl: json['media_url'] as String? ?? json['mediaUrl'] as String? ?? '',
      metadata:
          StoryMetadata.fromJson((json['metadata'] as Map<String, dynamic>?) ?? {}),
      viewers: (json['viewers'] as List? ?? [])
          .map((e) => StoryViewer.fromJson(e as Map<String, dynamic>))
          .toList(),
      createdAt: json['created_at'] != null
          ? DateTime.tryParse(json['created_at'].toString())
          : null,
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'user_id': userId,
        'media_url': mediaUrl,
        'metadata': metadata.toJson(),
        'viewers': viewers.map((e) => e.toJson()).toList(),
        'created_at': createdAt?.toIso8601String(),
      };
}

class StoryMetadata {
  final String? caption;
  final List<String> tags;
  final MusicTrack? track;
  final bool pinned;
  final DateTime? expiresAt;

  const StoryMetadata({
    this.caption,
    this.tags = const [],
    this.track,
    this.pinned = false,
    this.expiresAt,
  });

  factory StoryMetadata.fromJson(Map<String, dynamic> json) {
    return StoryMetadata(
      caption: json['caption'] as String?,
      tags: List<String>.from(json['tags'] as List? ?? const []),
      track: json['track'] != null
          ? MusicTrack.fromJson(json['track'] as Map<String, dynamic>)
          : null,
      pinned: json['pinned'] as bool? ?? false,
      expiresAt: json['expires_at'] != null
          ? DateTime.tryParse(json['expires_at'].toString())
          : null,
    );
  }

  Map<String, dynamic> toJson() => {
        if (caption != null) 'caption': caption,
        'tags': tags,
        if (track != null) 'track': track!.toJson(),
        'pinned': pinned,
        if (expiresAt != null) 'expires_at': expiresAt!.toIso8601String(),
      };
}

class StoryViewer {
  final int userId;
  final DateTime? viewedAt;
  final String? name;
  final String? avatarUrl;

  const StoryViewer({
    required this.userId,
    this.viewedAt,
    this.name,
    this.avatarUrl,
  });

  factory StoryViewer.fromJson(Map<String, dynamic> json) {
    return StoryViewer(
      userId: json['user_id'] as int? ?? json['userId'] as int,
      name: json['name'] as String?,
      avatarUrl: json['avatar_url'] as String? ?? json['avatarUrl'] as String?,
      viewedAt: json['viewed_at'] != null
          ? DateTime.tryParse(json['viewed_at'].toString())
          : null,
    );
  }

  Map<String, dynamic> toJson() => {
        'user_id': userId,
        if (name != null) 'name': name,
        if (avatarUrl != null) 'avatar_url': avatarUrl,
        'viewed_at': viewedAt?.toIso8601String(),
      };
}

class LiveStream {
  final int id;
  final String title;
  final bool isLive;
  final List<LiveParticipant> participants;
  final List<LiveDonation> donations;
  final List<LiveChatMessage> chatMessages;

  const LiveStream({
    required this.id,
    required this.title,
    required this.isLive,
    this.participants = const [],
    this.donations = const [],
    this.chatMessages = const [],
  });

  factory LiveStream.fromJson(Map<String, dynamic> json) {
    return LiveStream(
      id: json['id'] as int,
      title: json['title'] as String? ?? '',
      isLive: json['is_live'] as bool? ?? json['isLive'] as bool? ?? false,
      participants: (json['participants'] as List? ?? [])
          .map((e) => LiveParticipant.fromJson(e as Map<String, dynamic>))
          .toList(),
      donations: (json['donations'] as List? ?? [])
          .map((e) => LiveDonation.fromJson(e as Map<String, dynamic>))
          .toList(),
      chatMessages: (json['chat'] as List? ?? [])
          .map((e) => LiveChatMessage.fromJson(e as Map<String, dynamic>))
          .toList(),
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'title': title,
        'is_live': isLive,
        'participants': participants.map((e) => e.toJson()).toList(),
        'donations': donations.map((e) => e.toJson()).toList(),
        'chat': chatMessages.map((e) => e.toJson()).toList(),
      };
}

class LiveParticipant {
  final int userId;
  final String role;

  const LiveParticipant({
    required this.userId,
    this.role = 'viewer',
  });

  factory LiveParticipant.fromJson(Map<String, dynamic> json) {
    return LiveParticipant(
      userId: json['user_id'] as int? ?? json['userId'] as int,
      role: json['role'] as String? ?? 'viewer',
    );
  }

  Map<String, dynamic> toJson() => {
        'user_id': userId,
        'role': role,
      };
}

class LiveDonation {
  final int userId;
  final double amount;
  final String? message;

  const LiveDonation({
    required this.userId,
    required this.amount,
    this.message,
  });

  factory LiveDonation.fromJson(Map<String, dynamic> json) {
    return LiveDonation(
      userId: json['user_id'] as int? ?? json['userId'] as int,
      amount: (json['amount'] as num?)?.toDouble() ?? 0,
      message: json['message'] as String?,
    );
  }

  Map<String, dynamic> toJson() => {
        'user_id': userId,
        'amount': amount,
        if (message != null) 'message': message,
      };
}

class LiveChatMessage {
  final int userId;
  final String content;
  final DateTime? sentAt;

  const LiveChatMessage({
    required this.userId,
    required this.content,
    this.sentAt,
  });

  factory LiveChatMessage.fromJson(Map<String, dynamic> json) {
    return LiveChatMessage(
      userId: json['user_id'] as int? ?? json['userId'] as int,
      content: json['content'] as String? ?? json['message'] as String? ?? '',
      sentAt: json['sent_at'] != null
          ? DateTime.tryParse(json['sent_at'].toString())
          : null,
    );
  }

  Map<String, dynamic> toJson() => {
        'user_id': userId,
        'content': content,
        'sent_at': sentAt?.toIso8601String(),
      };
}
