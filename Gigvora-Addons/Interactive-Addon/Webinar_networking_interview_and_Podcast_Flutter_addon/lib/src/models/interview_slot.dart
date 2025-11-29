import 'model_utils.dart';

class InterviewSlot {
  final int id;
  final int interviewId;
  final int interviewerId;
  final int intervieweeId;
  final DateTime startsAt;
  final DateTime endsAt;
  final String status;
  final String? meetingLink;
  final Map<String, dynamic>? metadata;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  InterviewSlot({
    required this.id,
    required this.interviewId,
    required this.interviewerId,
    required this.intervieweeId,
    required this.startsAt,
    required this.endsAt,
    required this.status,
    this.meetingLink,
    this.metadata,
    this.createdAt,
    this.updatedAt,
  });

  factory InterviewSlot.fromJson(Map<String, dynamic> json) {
    return InterviewSlot(
      id: json['id'] as int,
      interviewId: json['interview_id'] as int,
      interviewerId: json['interviewer_id'] as int,
      intervieweeId: json['interviewee_id'] as int,
      startsAt: parseDateTime(json['starts_at']) ?? DateTime.now(),
      endsAt: parseDateTime(json['ends_at']) ?? DateTime.now(),
      status: json['status'] as String? ?? 'scheduled',
      meetingLink: json['meeting_link'] as String?,
      metadata: parseMetadata(json['metadata']),
      createdAt: parseDateTime(json['created_at']),
      updatedAt: parseDateTime(json['updated_at']),
    );
  }
}

class InterviewSlotPayload {
  final int interviewerId;
  final int intervieweeId;
  final DateTime startsAt;
  final DateTime endsAt;
  final String? meetingLink;
  final Map<String, dynamic>? metadata;

  InterviewSlotPayload({
    required this.interviewerId,
    required this.intervieweeId,
    required this.startsAt,
    required this.endsAt,
    this.meetingLink,
    this.metadata,
  });

  Map<String, dynamic> toJson() => {
        'interviewer_id': interviewerId,
        'interviewee_id': intervieweeId,
        'starts_at': startsAt.toIso8601String(),
        'ends_at': endsAt.toIso8601String(),
        'meeting_link': meetingLink,
        if (metadata != null) 'metadata': metadata,
      };
}
