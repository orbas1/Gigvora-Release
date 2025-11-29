import 'model_utils.dart';
import 'interview_slot.dart';
import 'interview_score.dart';

class Interview {
  final int id;
  final int hostId;
  final String title;
  final String? description;
  final DateTime scheduledAt;
  final int durationMinutes;
  final bool isPanel;
  final String? location;
  final Map<String, dynamic>? metadata;
  final DateTime? createdAt;
  final DateTime? updatedAt;
  final List<InterviewSlot> slots;
  final List<InterviewScore> scores;

  Interview({
    required this.id,
    required this.hostId,
    required this.title,
    required this.scheduledAt,
    required this.durationMinutes,
    required this.isPanel,
    this.description,
    this.location,
    this.metadata,
    this.createdAt,
    this.updatedAt,
    this.slots = const [],
    this.scores = const [],
  });

  factory Interview.fromJson(Map<String, dynamic> json) {
    return Interview(
      id: json['id'] as int,
      hostId: json['host_id'] as int,
      title: json['title'] as String,
      description: json['description'] as String?,
      scheduledAt: parseDateTime(json['scheduled_at']) ?? DateTime.now(),
      durationMinutes: json['duration_minutes'] as int? ?? 0,
      isPanel: json['is_panel'] as bool? ?? false,
      location: json['location'] as String?,
      metadata: parseMetadata(json['metadata']),
      createdAt: parseDateTime(json['created_at']),
      updatedAt: parseDateTime(json['updated_at']),
      slots: (json['slots'] as List<dynamic>? ?? [])
          .map((item) => InterviewSlot.fromJson(Map<String, dynamic>.from(item as Map)))
          .toList(),
      scores: (json['scores'] as List<dynamic>? ?? [])
          .map((item) => InterviewScore.fromJson(Map<String, dynamic>.from(item as Map)))
          .toList(),
    );
  }
}

class InterviewPayload {
  final String title;
  final DateTime scheduledAt;
  final int durationMinutes;
  final bool isPanel;
  final String? description;
  final String? location;
  final Map<String, dynamic>? metadata;

  InterviewPayload({
    required this.title,
    required this.scheduledAt,
    required this.durationMinutes,
    this.isPanel = false,
    this.description,
    this.location,
    this.metadata,
  });

  Map<String, dynamic> toJson() => {
        'title': title,
        'description': description,
        'scheduled_at': scheduledAt.toIso8601String(),
        'duration_minutes': durationMinutes,
        'is_panel': isPanel,
        'location': location,
        if (metadata != null) 'metadata': metadata,
      };
}

class InterviewUpdatePayload {
  final String? title;
  final DateTime? scheduledAt;
  final int? durationMinutes;
  final bool? isPanel;
  final String? description;
  final String? location;
  final Map<String, dynamic>? metadata;

  InterviewUpdatePayload({
    this.title,
    this.scheduledAt,
    this.durationMinutes,
    this.isPanel,
    this.description,
    this.location,
    this.metadata,
  });

  Map<String, dynamic> toJson() => {
        if (title != null) 'title': title,
        if (description != null) 'description': description,
        if (scheduledAt != null) 'scheduled_at': scheduledAt!.toIso8601String(),
        if (durationMinutes != null) 'duration_minutes': durationMinutes,
        if (isPanel != null) 'is_panel': isPanel,
        if (location != null) 'location': location,
        if (metadata != null) 'metadata': metadata,
      };
}
